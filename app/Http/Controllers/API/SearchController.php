<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ApiErrorCodes;
use App\Models\ProductPrice;
use DB;

class SearchController extends Controller
{
    public function search_result($searchkey=null, $layer=null)
    {
        $ip = \Config::get('app.api_base_url');
        $skey = $searchkey;
        $leave = ($layer-1)*10;
        $take = $leave + 10;
        if($skey =='' || $skey==null){
            $e = ApiErrorCodes::where('error_code',10050)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
            return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
        }
        $sellers = DB::select("SELECT
                                 u.*, 
                                 u.id, 
                                 u.first_name,
                                 SUBSTRING(u.personal_detail, 1, 200) AS details,
                                 CONCAT('".$ip."assets/images/users/','',u.profile_pic) AS img
                                 FROM users AS u 
                                 WHERE u.first_name LIKE '%".$skey."%' AND u.status='ACTIVE' AND u.user_type_id=2
                                ");
        $products = DB::select("SELECT 
                                pro.*,
                                SUBSTRING(pro.title, 1, 25) AS top_title,
                                SUBSTRING(pro.long_desc, 1, 25) AS trimed_long_desc,
                                CONCAT('".$ip."assets/images/products/','',pro.image_name) AS img,
                                cat.cat_name AS category_main,
                                cat_sub.cat_name AS category_sub,
                                u.first_name AS seller,
                                pp.price
                                FROM `products` AS pro 
                                LEFT OUTER JOIN users AS u 
                                    ON pro.seller_id = u.id
                                LEFT OUTER JOIN category AS cat 
                                    ON pro.category_id = cat.id
                                LEFT OUTER JOIN category AS cat_sub 
                                    ON pro.sub_category_id = cat_sub.id
                                LEFT OUTER JOIN product_prices AS pp 
                                    ON pro.price_id = pp.id 
                                WHERE pro.title LIKE '%".$skey."%'
                                    OR pro.short_desc LIKE '%".$skey."%'
                                    OR pro.long_desc LIKE '%".$skey."%'
                                LIMIT $leave, 10
                                ");
        $products_count = DB::select("SELECT COUNT(*) AS total
                                FROM `products` AS pro 
                                WHERE pro.title LIKE '%".$skey."%'
                                    OR pro.short_desc LIKE '%".$skey."%'
                                    OR pro.long_desc LIKE '%".$skey."%'
                                ");
        $data = [
            'status'  => 200,
            'sellers' => $sellers,
            'products' => $products,
            'total' => $products_count[0]->total
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
}
