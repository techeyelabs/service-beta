<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ApiErrorCodes;
use App\Models\ProductPrice;
use App\Models\Residential;
use App\Models\Sex;
use DB;

class DisplayController extends Controller
{
    public function temp_top()
    {
        $ip = \Config::get('app.api_base_url');
        $cards = DB::select("SELECT 
                            pro.*,
                            (
                                SELECT IFNULL(AVG(rating_star),0)
                                FROM product_rating
                                WHERE product_id = pro.id
                            ) AS stars,
                            sale.popularity_scale,
                            SUBSTRING(pro.title, 1, 35) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 65) AS long_title,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            CONCAT('". $ip  ."assets/images/users/','',u.profile_pic) AS seller_img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            SUBSTRING(u.first_name, 1, 60) AS seller_short_name,
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
                            LEFT JOIN (
                                SELECT product_id, COUNT('*') AS popularity_scale
                                FROM request_board
                                WHERE current_status = 'COMPLETE'
                                GROUP BY product_id 
                                ) as sale on sale.product_id = pro.id 
                                WHERE pro.is_draft = 'PUBLISHED'
                                AND pro.status = 'ACTIVE'
                                AND pro.no_of_order_at_atime > 0
                                ORDER BY sale.popularity_scale DESC, id DESC
                                LIMIT 10");
        $data = [
            'status'  => 200,
            'payload' => $cards
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function display($id=null, $layer=null, $flag)
    {
        // echo '<pre>'; print_r($request->); return;
        $ip = \Config::get('app.api_base_url');
        $product_cat = isset($id)? $id: '';
        if($flag == 0){
            $offset = 0;
            $limit = 6;
            if($layer==2)
                $offset = 6;
        } else if($flag == 1){
            $offset = 0;
            $limit = 2;  
            $offset = (2 * $layer) - 2;
        } else {
            $offset = 0;
            $limit = 3;  
            $offset = (3 * $layer) - 3;
        }
        
        // $seller = isset($seller_id)? $seller_id: '';
        if($product_cat!=''){
            $cards = DB::select("SELECT 
                            pro.*,
                            sale.popularity_scale,
                            SUBSTRING(pro.title, 1, 15) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 25) AS long_title,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            SUBSTRING(u.first_name, 1, 6) AS seller_short_name,
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
                            LEFT JOIN (
                                SELECT product_id, COUNT('*') AS popularity_scale
                                FROM request_board
                                WHERE current_status = 'COMPLETE'
                                GROUP BY product_id 
                                ) as sale on sale.product_id = pro.id 
                                WHERE pro.category_id = '".$product_cat."' 
                                AND pro.is_draft = 'PUBLISHED'
                                AND pro.status = 'ACTIVE'
                                AND pro.no_of_order_at_atime > 0
                                ORDER BY sale.popularity_scale DESC
                                LIMIT ".$offset.", ".$limit."
                                ");
            $total = DB::table('products')->select('*')->where('category_id', $product_cat)->where('status', 'ACTIVE')->count();
        } 
        if(empty($cards)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'payload' => $cards,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function display_only_affs($id=null, $layer=null, $flag)
    {
        // echo '<pre>'; print_r($request->); return;
        $ip = \Config::get('app.api_base_url');
        $product_cat = isset($id)? $id: '';
        if($flag == 0){
            $offset = 0;
            $limit = 6;
            if($layer==2)
                $offset = 6;
        } else {
            $offset = 0;
            $limit = 2;
            if($layer==2)
                $offset = 2;
        }
        // $seller = isset($seller_id)? $seller_id: '';
        if($product_cat!=''){
            $cards = DB::select("SELECT 
                            pro.*,
                            sale.popularity_scale,
                            SUBSTRING(pro.title, 1, 15) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 25) AS long_title,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            SUBSTRING(u.first_name, 1, 6) AS seller_short_name,
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
                            LEFT JOIN (
                                SELECT product_id, COUNT('*') AS popularity_scale
                                FROM request_board
                                WHERE current_status = 'COMPLETE'
                                GROUP BY product_id 
                                ) as sale on sale.product_id = pro.id 
                                WHERE pro.category_id = '".$product_cat."' 
                                AND pro.is_draft = 'PUBLISHED'
                                AND pro.status = 'ACTIVE'
                                AND pro.affiliator_commission > 0
                                AND pro.no_of_order_at_atime > 0
                                ORDER BY sale.popularity_scale DESC
                                LIMIT ".$offset.", ".$limit."
                                ");
            $total = DB::table('products')->select('*')->where('category_id', $product_cat)->where('status', 'ACTIVE')->count();
        } 
        if(empty($cards)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'payload' => $cards,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function display_see_more_cat_temp()
    {
        $ip = \Config::get('app.api_base_url');
        // echo '<pre>'; print_r($request->); return;
        // $seller = isset($seller_id)? $seller_id: '';
            $cards = DB::select("SELECT 
                            pro.*,
                            SUBSTRING(pro.title, 1, 25) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 150) AS long_description,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            u.first_name AS seller,
                            CONCAT('". $ip  ."assets/images/users/','',prof.profile_image) AS seller_img,
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
                            LEFT OUTER JOIN profile AS prof 
                                ON prof.user_id = pro.seller_id
                                WHERE pro.is_draft = 'PUBLISHED'
                                AND pro.status = 'ACTIVE'
                                AND pro.no_of_order_at_atime > 0
                                ORDER BY pro.id DESC
                                ");
            $total = DB::table('products')->where('status', 'ACTIVE')->where('no_of_order_at_atime', '>', 0)->count();
        if(empty($cards)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'payload' => $cards,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
    public function display_see_more_cat($id=null, $layer=null)
    {
        $ip = \Config::get('app.api_base_url');
        $offset = ($layer-1)*10;
        $limit = 10;
        // echo '<pre>'; print_r($request->); return;
        $product_cat = isset($id)? $id: '';
        // $seller = isset($seller_id)? $seller_id: '';
        if($product_cat!=''){
            $cards = DB::select("SELECT 
                            pro.*,
                            SUBSTRING(pro.title, 1, 25) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 150) AS long_description,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            u.first_name AS seller,
                            CONCAT('". $ip  ."assets/images/users/','',prof.profile_image) AS seller_img,
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
                            LEFT OUTER JOIN profile AS prof 
                                ON prof.user_id = pro.seller_id
                                WHERE pro.category_id = '".$product_cat."'
                                AND pro.is_draft = 'PUBLISHED'
                                AND pro.status = 'ACTIVE'
                                AND pro.no_of_order_at_atime > 0
                                ORDER BY pro.id DESC
                                LIMIT ".$offset.", ".$limit."
                                ");
            $cat_name = DB::table('category')->select('id', 'cat_name')->where('id', $product_cat)->get();
            $total = DB::table('products')->where('status', 'ACTIVE')->where('no_of_order_at_atime', '>', 0)->where('category_id', $product_cat)->count();
        } 
        if(empty($cards)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'payload' => $cards,
                'cat_name' => $cat_name,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function display_see_more_cat_aff_temp()
    {
        $ip = \Config::get('app.api_base_url');
        // echo '<pre>'; print_r($request->); return;
        // $seller = isset($seller_id)? $seller_id: '';
            $cards = DB::select("SELECT 
                            pro.*,
                            SUBSTRING(pro.title, 1, 25) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 150) AS long_description,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            u.first_name AS seller,
                            CONCAT('". $ip  ."assets/images/users/','',prof.profile_image) AS seller_img,
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
                            LEFT OUTER JOIN profile AS prof 
                                ON prof.user_id = pro.seller_id
                                WHERE pro.is_draft = 'PUBLISHED'
                                AND pro.status = 'ACTIVE'
                                AND pro.affiliator_commission > 0
                                AND pro.no_of_order_at_atime > 0
                                ORDER BY pro.id DESC
                                ");
            $total = DB::table('products')->where('status', 'ACTIVE')->where('affiliator_commission', '>', 0)->count();

        if(empty($cards)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'payload' => $cards,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function display_see_more_cat_aff($id=null, $layer=null)
    {
        $ip = \Config::get('app.api_base_url');
        $offset = ($layer-1)*10;
        $limit = 10;
        // echo '<pre>'; print_r($request->); return;
        $product_cat = isset($id)? $id: '';
        // $seller = isset($seller_id)? $seller_id: '';
        if($product_cat!=''){
            $cards = DB::select("SELECT 
                            pro.*,
                            SUBSTRING(pro.title, 1, 25) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 150) AS long_description,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            u.first_name AS seller,
                            CONCAT('". $ip  ."assets/images/users/','',prof.profile_image) AS seller_img,
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
                            LEFT OUTER JOIN profile AS prof 
                                ON prof.user_id = pro.seller_id
                                WHERE pro.category_id = '".$product_cat."'
                                AND pro.is_draft = 'PUBLISHED'
                                AND pro.status = 'ACTIVE'
                                AND pro.affiliator_commission > 0
                                AND pro.no_of_order_at_atime > 0
                                ORDER BY pro.id DESC
                                LIMIT ".$offset.", ".$limit."
                                ");
            $cat_name = DB::table('category')->select('id', 'cat_name')->where('id', $product_cat)->get();
            $total = DB::table('products')->where('status', 'ACTIVE')->where('affiliator_commission', '>', 0)->where('category_id', $product_cat)->count();
        } 
        if(empty($cards)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'payload' => $cards,
                'cat_name' => $cat_name,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function display_see_more_sub_cat($id=null, $layer=null)
    {
        $ip = \Config::get('app.api_base_url');
        $offset = ($layer-1)*10;
        $limit = 10;
        // echo '<pre>'; print_r($request->); return;
        $product_cat = isset($id)? $id: '';
        // $seller = isset($seller_id)? $seller_id: '';
        if($product_cat!=''){
            $cards = DB::select("SELECT 
                            pro.*,
                            SUBSTRING(pro.title, 1, 25) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 150) AS long_description,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            u.first_name AS seller,
                            CONCAT('". $ip  ."assets/images/users/','',prof.profile_image) AS seller_img,
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
                            LEFT OUTER JOIN profile AS prof 
                                ON prof.user_id = pro.seller_id
                                WHERE pro.sub_category_id = '".$product_cat."'
                                AND pro.is_draft = 'PUBLISHED'
                                AND pro.status = 'ACTIVE'
                                AND pro.no_of_order_at_atime > 0
                                LIMIT ".$offset.", ".$limit."
                                ");
            $total = DB::table('products')->where('status', 'ACTIVE')->where('sub_category_id', $product_cat)->count();
            $cat_name = DB::table('category')->select('id', 'cat_name')->where('id', $product_cat)->get();
        } 
        if(empty($cards)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'payload' => $cards,
                'cat_name' => $cat_name,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function display_see_more_sub_cat_affs($id=null, $layer=null)
    {
        $ip = \Config::get('app.api_base_url');
        $offset = ($layer-1)*10;
        $limit = 10;
        // echo '<pre>'; print_r($request->); return;
        $product_cat = isset($id)? $id: '';
        // $seller = isset($seller_id)? $seller_id: '';
        if($product_cat!=''){
            $cards = DB::select("SELECT 
                            pro.*,
                            SUBSTRING(pro.title, 1, 25) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 150) AS long_description,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            u.first_name AS seller,
                            CONCAT('". $ip  ."assets/images/users/','',prof.profile_image) AS seller_img,
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
                            LEFT OUTER JOIN profile AS prof 
                                ON prof.user_id = pro.seller_id
                                WHERE pro.sub_category_id = '".$product_cat."'
                                AND pro.is_draft = 'PUBLISHED'
                                AND pro.status = 'ACTIVE'
                                AND pro.affiliator_commission > 0
                                AND pro.no_of_order_at_atime > 0
                                LIMIT ".$offset.", ".$limit."
                                ");
            $total = DB::table('products')->where('status', 'ACTIVE')->where('sub_category_id', $product_cat)->where('affiliator_commission', '>', 0)->count();
            $cat_name = DB::table('category')->select('id', 'cat_name')->where('id', $product_cat)->get();
        } 
        if(empty($cards)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'payload' => $cards,
                'cat_name' => $cat_name,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function display_seller_products($id=null, $layer=null)
    {
        // echo '<pre>'; print_r($request->); return;
        $limit = 10;
        $offset = ($layer-1)*$limit;
        $ip = \Config::get('app.api_base_url');
        $seller = isset($id)? $id: '';
        // $seller = isset($seller_id)? $seller_id: '';
        if($seller!=''){
            $cards = DB::select("SELECT 
                            pro.*,
                            SUBSTRING(pro.title, 1, 25) AS top_title,
                            SUBSTRING(pro.long_desc, 1, 100) AS trimed_long_desc,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
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
                                WHERE pro.seller_id = '".$seller."'                                
                                ORDER BY pro.id DESC
                                LIMIT ".$offset.", ".$limit."
                                ");
            $total = DB::table('products')
                        ->select('*')
                        ->where('seller_id', $seller)
                        ->count();
        } 
        if(empty($cards)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'payload' => $cards,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }


    public function display_product_details($id=null, $device)
    {
        $ip = \Config::get('app.api_base_url');
        $product = $id;
        $product_all = DB::select(DB::raw("SELECT 
                            pro.*,
                            SUBSTRING(pro.long_desc, 1, 100) AS trimed_long_desc,
                            SUBSTRING(pro.long_desc, 101, 1500) AS read_more,
                            CONCAT('". $ip  ."assets/images/products/','',pro.image_name) AS img,
                            cat.cat_name AS category_main,
                            cat_sub.cat_name AS category_sub,
                            pp.price,
                            u.first_name AS seller
                            FROM `products` AS pro 
                            LEFT OUTER JOIN users AS u 
                                ON pro.seller_id = u.id
                            LEFT OUTER JOIN category AS cat 
                                ON pro.category_id = cat.id
                            LEFT OUTER JOIN category AS cat_sub 
                                ON pro.sub_category_id = cat_sub.id
                            LEFT OUTER JOIN product_prices AS pp 
                                ON pro.price_id = pp.id
                            WHERE pro.id = '".$product."'
                            "));
        
        $seller = DB::select(DB::raw("SELECT 
                            us.id, 
                            us.first_name, 
                            us.personal_detail, 
                            us.email, 
                            us.phone,
                            us.last_login_time, 
                            us.profile_pic, 
                            pro.profession,
                            pro.profile_image,
                            pro.sex,
                            x.description AS sex_text,
                            CONCAT('". $ip  ."assets/images/users/','',pro.profile_image) AS img,
                            pro.personal_details,
                            age.age_group AS age, 
                            ra.area
                            FROM users AS us 
                            LEFT OUTER JOIN profile AS pro ON pro.user_id=us.id
                            LEFT OUTER JOIN sex AS x ON pro.sex=x.id
                            LEFT OUTER JOIN age_group AS age ON age.id=pro.age_group
                            LEFT OUTER JOIN residential_area AS ra ON ra.id=pro.residential_area
                            WHERE status='ACTIVE' AND us.id='". $product_all[0]->seller_id ."' LIMIT 1"));
                            //  print_r($seller);
        if($device == 1){
            $multiples = DB::select(DB::raw("SELECT 
                            CONCAT('". $ip  ."assets/images/products/','',mi.image) AS img
                            FROM `multiple_image` AS mi 
                            WHERE mi.product_id = '".$product."'
                            LIMIT 5
                            "));
        } else if($device == 3){
            $multiples = DB::select(DB::raw("SELECT 
                            CONCAT('". $ip  ."assets/images/products/','',mi.image) AS img
                            FROM `multiple_image` AS mi 
                            WHERE mi.product_id = '".$product."'
                            LIMIT 3
                            "));
        } else {
            $multiples = DB::select(DB::raw("SELECT 
                            CONCAT('". $ip  ."assets/images/products/','',mi.image) AS img
                            FROM `multiple_image` AS mi 
                            WHERE mi.product_id = '".$product."'
                            LIMIT 4
                            "));
        }
        
        $current_time = date('Y-m-d H:i:s');
        $hours_since_last_login = strtotime($current_time) - strtotime($seller[0]->last_login_time);
        $hours_since_last_login = $hours_since_last_login/86400;
        // $hour_flag = $hours_since_last_login;
        // $hour_flag = $hour_flag*60;
        $hours_since_last_login = floor($hours_since_last_login);
        // if($hours_since_last_login<1) {
        //     $hours_since_last_login = $hour_flag;
        // }
        if(empty($product)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'product' => $product_all,
                'product_single' => $product_all[0],
                'seller' => $seller,
                'seller_single' => $seller[0],
                'hours_since_last_login' => $hours_since_last_login,
                'multiples' => $multiples
                // 'hours_since_last_login' => $seller[0]->last_login_time
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function details_slider($id, $layer, $device)
    {
        if($device == 1){
            $ip = \Config::get('app.api_base_url');
            $layer--;
            $multiples = DB::select(DB::raw("SELECT 
                        CONCAT('". $ip  ."assets/images/products/','',mi.image) AS img
                        FROM `multiple_image` AS mi 
                        WHERE mi.product_id = '".$id."'
                        LIMIT $layer, 5 
                        "));
            $data = [
                'status' => 200,
                'images' => $multiples
            ];
        } else if($device == 3){
            $ip = \Config::get('app.api_base_url');
            $layer--;
            $multiples = DB::select(DB::raw("SELECT 
                        CONCAT('". $ip  ."assets/images/products/','',mi.image) AS img
                        FROM `multiple_image` AS mi 
                        WHERE mi.product_id = '".$id."'
                        LIMIT $layer, 3
                        "));
            $data = [
                'status' => 200,
                'images' => $multiples
            ];
        } else {
            $ip = \Config::get('app.api_base_url');
            $layer--;
            $multiples = DB::select(DB::raw("SELECT 
                        CONCAT('". $ip  ."assets/images/products/','',mi.image) AS img
                        FROM `multiple_image` AS mi 
                        WHERE mi.product_id = '".$id."'
                        LIMIT $layer, 4
                        "));
            $data = [
                'status' => 200,
                'images' => $multiples
            ];
        }
        
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function display_pro_sellers()
    {
        $ip = \Config::get('app.api_base_url');
        $is_exist = User::where('user_type_id', 2)->get();
        if(!empty($is_exist)){
            $data = [
                'status'  => 200,
                'payload' => $is_exist
            ];
        } else {
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function residentials()
    {
        $ip = \Config::get('app.api_base_url');
        $area = Residential::all();
        if($area=='' || $area==null){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'area' => $area
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function sex()
    {
        $sex = Sex::all();
            $data = [
                'status'  => 200,
                'sex' => $sex
            ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_porifolio($id)
    {
        $ip = \Config::get('app.api_base_url');
        $portfolio = DB::table('portfolio AS prt')
                        ->select('prt.*', 'cat.cat_name', DB::raw('CONCAT("'.$ip.'","assets/images/portfolio/", prt.service_image) AS img'))
                        ->leftjoin('category AS cat', 'cat.id', '=', 'prt.category')
                        ->where('prt.status', '=', 'ACTIVE')
                        ->where('prt.id', '=', $id)
                        ->first();
        $data = [
            'status' => 200,
            'payload' => $portfolio
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
}
