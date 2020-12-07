<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\RatingStar;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function give_rating(Request $request)
    {
        $buyer_id = $request->buyer_id;
        $product_id = $request->product_id;
        $rating_star = $request->rating_star;
        
        if($buyer_id==null || $buyer_id=='') {
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else if($product_id==null || $product_id==''){
            $e = ApiErrorCodes::where('error_code',10052)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else if($rating_star==null || $rating_star==''){
            $e = ApiErrorCodes::where('error_code',10064)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else {
            $is_exist = RatingStar::where('buyer_id', $buyer_id)->where('product_id', $product_id)->first();
            if($is_exist!=null || $is_exist!=''){
                RatingStar::where('buyer_id', $buyer_id)
                            ->where('product_id', $product_id)
                            ->update(
                                [
                                    'rating_star' => $rating_star
                                ]
                            );
                $e = ApiErrorCodes::where('error_code',10051)->first(); 
                $data = [
                                'status'  => $e->error_code ,
                                'message' => $e->error_message 
                            ];
            } else {
                $star = new RatingStar();
                $star->buyer_id = $buyer_id;
                $star->product_id = $product_id;
                $star->rating_star = $rating_star;
                $star->save();
                $e = ApiErrorCodes::where('error_code',10051)->first(); 
                $data = [
                                'status'  => $e->error_code ,
                                'message' => $e->error_message 
                            ];
            }            
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_rating()
    {
        
    }
}
