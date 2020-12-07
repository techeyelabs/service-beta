<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\RatingStar;
use App\Models\PurchaseHistory;
use App\Models\Notification;
use DB;

class ReviewController extends Controller
{
     /**************************
        @Name: Add a review
        @URL: review
        @Method: POST
    ***************************/
    public function add_review(Request $request)
    {
        $purchase_id = $request->purchase_id;
        $product = $request->product_id;
        $buyer = $request->buyer_id;
        $comment = $request->comment;
        $star = $request->rating;
        if($product==null || $product==''){
            $e = ApiErrorCodes::where('error_code',10052)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
        } else if($buyer==null || $buyer==''){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
        } else {
            $all_pro = Product::where('id', $product)->first();
            $seller = $all_pro->seller_id;
            if($buyer == $seller){
                $e = ApiErrorCodes::where('error_code',10068)->first(); 
                $data = [
                            'status'  => $e->error_code ,
                            'message' => $e->error_message 
                        ];
            } else {
                    //Add review
                    $rev = new Review();
                    $rev->product_id = $product;
                    $rev->seller_id = $seller;
                    $rev->buyer_id = $buyer;
                    $rev->comment = $comment;
                    $rev->purchase_id = $purchase_id;
                    $rev->created_at = date('Y-m-d H:i:s');
                    $rev->updated_at = date('Y-m-d H:i:s');
                    $rev->save();

                    //Add rating
                    $st = new RatingStar();
                    $st->product_id = $product;
                    $st->buyer_id = $buyer;
                    $st->rating_star = $star;
                    $st->review_id = $rev->id;
                    $st->purchase_id = $purchase_id;
                    $st->created_at = date('Y-m-d H:i:s');
                    $st->updated_at = date('Y-m-d H:i:s');
                    $st->save();
                    
                    //Get names
                    $s = User::where('id', $seller)->first();
                    $b = User::where('id', $buyer)->first();
                    $serv = Product::where('id', $product)->first();

                    //Notification generate
                    $not = new Notification();
                    $not->user_id = $seller;
                    $not->product_id = $product;
                    $not->request_id = 0;
                    $not->response_id = 0;
                    $not->seller_id = $seller;
                    $not->buyer_id = $buyer;
                    $not->notification_text = $b->first_name.'さんに'.$serv->title.'のレビューされました';
                    $not->opening_status = 'UNOPENED';
                    $not->notification_type = 'REVIEW';
                    $not->save();


                    $e = ApiErrorCodes::where('error_code',10051)->first(); 
                    $data = [
                                'status'  => 200 ,
                                'message' => $e->error_message 
                            ];
            }
            
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    /**************************
        @Name: Get all reviews of a product
        @URL: get-all-review
        @Method: GET
        @Params: Product ID
    ***************************/
    public function get_review($id=null, $buyer=null)
    {
        $ip = \Config::get('app.api_base_url');	
        $user = $buyer; 
        $product = $id;
        if($product=='' || $product==null){
            $e = ApiErrorCodes::where('error_code',10052)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
        } else {
            $revs = DB::table('product_review AS pr')
                        ->select('pr.*', 'us.first_name', 'us_sell.first_name', 'us.first_name AS buyer',
                                DB::raw('(CASE 
                                        WHEN EXISTS(SELECT 1 FROM product_rating WHERE review_id = pr.id AND product_id = '.$product.' LIMIT 1)
                                            THEN 
                                                (SELECT rating_star FROM product_rating WHERE review_id = pr.id AND product_id = '.$product.' LIMIT 1)
                                        ELSE 0 
                                        END) AS stars'),
                                DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_buyer'), 
                                DB::raw('CONCAT("'.$ip.'","assets/images/users/", us_sell.profile_pic) AS img_seller'))
                        ->leftjoin('users as us', 'us.id', '=', 'pr.buyer_id')
                        ->leftjoin('users as us_sell', 'us_sell.id', '=', 'pr.seller_id')
                        ->where('pr.product_id', $product)
                        ->orderBy('pr.created_at', 'desc')
                        ->get();
            $total_star = DB::table('product_rating AS pr')
                            ->where('pr.product_id', '=', $product)
                            ->sum('pr.rating_star');
            $total_rating = DB::table('product_rating')->where('product_id', $product)->count();
            $overall = $total_star/$total_rating;
            $flag = PurchaseHistory::where('product_id', $product)->where('buyer_id', $user)->count();
            $f = ($flag>0)?1:0;
            $data = [
                'status'  => 200 ,
                'payload' => $revs,
                'flag' => $f,
                'overall' => $overall
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
}
