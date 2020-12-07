<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\User;
use DB;


class AffiliatorLinkController extends Controller
{
    public function get_link($id)
    {
        $ip = \Config::get('app.api_base_url');
        $affiliator = $id;
        if($affiliator){
            $aff = User::where('id', $affiliator)->first();
            $link = 'affiliator/'.$aff->affiliator_links_id;
            $link = trim($link);
            $data = [
                'status' => 200,
                'link' => $link,
                'universal_link' => $aff->affiliator_links_id
            ];
        } else {
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
}
