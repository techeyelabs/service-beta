<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ApiErrorCodes;
use App\Models\Favourite;
use DB;

class FavouriteController extends Controller
{
     /**************************
        @Name: Add to favourites
        @URL: add-to-favourite
        @Method: GET
        @Params: product id, user id
    ***************************/
    public function add_to_favourite($product_id=null, $user_id=null)
    {
        $product = $product_id;
        $user = $user_id;
        if(!empty($product) && !empty($user)){
            $count = Favourite::where('product_id', $product)->where('user_id', $user_id)->count();
            if($count==0){
                $fav = new Favourite;
                $fav->product_id = $product;
                $fav->user_id = $user;
                $fav->status = 'ACTIVE';
                $fav->save();
                $e = ApiErrorCodes::where('error_code',10043)->first(); 
                $data = [
                            'status'  => 200,
                            'message' => $e->error_message,
                            'flag' => 0
                        ];
            } else {
                $fav = Favourite::where('product_id', $product)->where('user_id', $user)->where('status', 'INACTIVE')->first();
                $fav->status = 'ACTIVE';
                $fav->update();
                $e = ApiErrorCodes::where('error_code',10043)->first(); 
                $data = [
                            'status'  => 200,
                            'message' => $e->error_message,
                            'flag' => 0
                        ];
            }           
        } else {
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message 
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }


     /**************************
        @Name: Get total favourites
        @URL: get-favourite-count
        @Method: GET
        @Params: product id
    ***************************/
    public function get_favourite_count($product_id=null, $user_id=null)
    {
        $user = $user_id;
        $product = $product_id;
        if($product!='' && $product!=null && $user!='' && $user!=null){
            $count = Favourite::where('product_id', $product)->where('status', 'ACTIVE')->count();
            $flag_count = Favourite::where('product_id', $product)->where('user_id', $user)->where('status', 'ACTIVE')->count();
            $flag = ($flag_count>0)? 0: 1;
            $data = [
                'status'  => 200,
                'payload' => $count,
                'flag' => $flag
            ];
        } else {
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message 
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }


    /**************************
        @Name: Remove from favourites
        @URL: remove-from-favourites
        @Method: GET
        @Params: product id, user id
    ***************************/
    public function remove_from_fav($product_id=null, $user_id=null)
    {
        $product = $product_id;
        $user = $user_id;
        if(!empty($product) && !empty($user)){
            $count = Favourite::where('product_id', $product)->where('user_id', $user)->where('status', 'ACTIVE')->count();
            if($count>0){ 
                // Favourite::update()->where('product_id', $product)->where('user_id', $user)->where('status', 'ACTIVE')->set('status', 'INACTIVE');
                $fav = Favourite::where('product_id', $product)->where('user_id', $user)->where('status', 'ACTIVE')->first();
                $fav->status = 'INACTIVE';
                $fav->save();
                $e = ApiErrorCodes::where('error_code',10051)->first(); 
                $data = [
                    'status'  => 200,
                    'message' => $e->error_message,
                    'flag' => 1
                ];
            } else {
                $e = ApiErrorCodes::where('error_code',10059)->first(); 
                $data = [
                    'status'  => 200,
                    'message' => $e->error_message
                ];
            }
        } else {
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message 
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

     /**************************
        @Name: Get list of my favourites
        @URL: get-my-favourites
        @Method: GET
        @Params: user id
    ***************************/
    public function get_my_favourites($id=null, $layer)
    {
        $ip = \Config::get('app.api_base_url');
        $user = $id;
        $leave = ($layer-1)*10;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message 
            ];
        } else {
            $favourites = DB::table('favourites')
                        ->select('favourites.id', 'favourites.product_id', 'favourites.user_id', 'p.*', 'pp.price',
                                'u.first_name', DB::raw('CONCAT("'.$ip.'","assets/images/users/", u.profile_pic) AS img'),
                                DB::raw('CONCAT("'.$ip.'","assets/images/products/", p.image_name) AS proimg'),
                                DB::raw('(CASE 
                                WHEN EXISTS(SELECT 1 FROM product_rating WHERE product_id = p.id LIMIT 1)
                                    THEN 
                                        (SELECT AVG(rating_star) FROM product_rating WHERE product_id = p.id LIMIT 1)
                                    ELSE 0 
                                    END) AS stars')
                                )
                        ->skip($leave)->take(10)
                        ->leftjoin('products as p', 'p.id', '=', 'favourites.product_id')
                        ->leftjoin('users as u', 'u.id', '=', 'p.seller_id')
                        ->leftjoin('product_prices AS pp', 'pp.id', '=', 'p.price_id')
                        ->where('favourites.user_id', $user)
                        ->where('favourites.status', 'ACTIVE')
                        ->orderBy('favourites.id', 'DESC')
                        ->get();
            $total = DB::table('favourites')->where('user_id', $user)->where('status', 'ACTIVE')->count();
            $data = [
                'status'  => 200,
                'payload' => $favourites,
                'total' =>$total
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
}
