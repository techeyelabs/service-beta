<?php



namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiErrorCodes;
use App\Models\Profile;
use App\Models\User;
use DB;



class DashboardController extends Controller
{
    public function buyer_dashboard($id=null, $layer=null)
    {    
        $ip = \Config::get('app.api_base_url');	 
        $buyer = $id;
        $leave = ($layer-1)*10;
        if($buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                            'status'  => $e->error_code ,
                            'message' => $e->error_message 
                        ];
        } else {
                $products = DB::table('purchase_history as ph')
                        ->select('ph.service_id', 'ph.id AS purchase_flag',  'ph.transaction_id', 'pro.seller_id', 'pro.category_id', 'ph.product_id', 'ph.price AS actual_price',
                                'pro.sub_category_id', 'pro.short_desc', 'pro.long_desc', 'pro.price_id', 'pro.title', 'u.first_name', 'ph.created_at',
                        DB::raw('(CASE 
                                WHEN EXISTS(
                                        SELECT 1 
                                                FROM product_review 
                                                WHERE buyer_id = '.$buyer.' AND 
                                                product_id = pro.id AND 
                                                purchase_id = ph.id
                                                LIMIT 1
                                        ) THEN 1
                                ELSE 0 
                                END) AS review_status'),
                        DB::raw('(CASE 
                                WHEN EXISTS(
                                        SELECT 1 
                                                FROM product_rating 
                                                WHERE buyer_id = '.$buyer.' AND 
                                                product_id = pro.id AND 
                                                purchase_id = ph.id
                                                LIMIT 1
                                        )
                                     THEN 
                                        (
                                                SELECT rating_star 
                                                FROM product_rating 
                                                WHERE buyer_id = '.$buyer.' AND 
                                                product_id = pro.id AND
                                                purchase_id = ph.id
                                                LIMIT 1
                                        )
                                ELSE 0 
                                END) AS stars'),
                        DB::raw('CONCAT("'.$ip.'","assets/images/users/", u.profile_pic) AS img'), 
                        DB::raw('CONCAT("'.$ip.'","assets/images/products/", pro.image_name) AS proimg'), 
                        'pro.expected_delivery_days', 'pp.price')
                        ->skip($leave)->take(10)
                        ->leftjoin('products as pro', 'pro.id', '=', 'ph.product_id')
                        ->leftjoin('product_prices as pp', 'pp.id', '=', 'pro.price_id')
                        ->leftjoin('users as u', 'u.id', '=', 'pro.seller_id')
                        ->where('ph.buyer_id', $buyer)
                        ->where('ph.product_id', '>', 0)
                        ->orderBy('ph.id', 'DESC')
                        ->get();
                $requests = DB::table('purchase_history as ph')
                        ->select('ph.*', 'rb.*', 'us.first_name AS seller', DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS seller_img'))
                        ->leftjoin('request_board AS rb', 'rb.id', '=', 'ph.service_id')
                        ->leftjoin('users AS us', 'us.id', '=', 'ph.seller_id')
                        ->where('ph.product_id', '=', 0)
                        ->where('ph.buyer_id', $buyer)
                        ->get();
            $total = DB::table('purchase_history')->where('buyer_id', $buyer)->count();
            $data = [
                        'status'  => 200 ,
                        'message' => $products,
                        'requests' => $requests,
                        'total' => $total
                    ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

}