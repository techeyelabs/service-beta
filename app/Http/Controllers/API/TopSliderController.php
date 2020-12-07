<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ApiErrorCodes;
use App\Models\TopSlider;
use DB;

class TopSliderController extends Controller
{
    /**************************
        @Name: Top slider
        @URL: top-slider
        @Method: GET
        @Params: None
    ***************************/
    public function show()
    {
        $ip = \Config::get('app.api_base_url');
        $images = DB::select("SELECT tp.link,
                                CONCAT('". $ip  ."assets/topslider/',tp.image_name) AS img
                                FROM top_slider tp
                                WHERE tp.status = 'ACTIVE' ORDER BY ordering_id ASC");
        if(!empty($images)){
            $data = [
                'status'  => 200  ,
                'payload' => $images
            ];
        } else {
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
}
