<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\RequestBoard;
use DB;

class TransactionController extends Controller
{
    public function my_estimates($id=null)
    {
        if($id!=null && $id!=''){
            $estimates = DB::table('request_board')->select('*')->where('buyer_id', $id)->where('current_status', 'UNDERESTIMATION')->get();
            if($estimates!=null && $estimates!=''){
                $data = [
                    'status'  => 200,
                    'payload' => $estimates
                ];
            } else {
                $e = ApiErrorCodes::where('error_code',10044)->first(); 
                $data = [
                    'status'  => $e->error_code,
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

    public function all_estimates()
    {
        $all_estimations = RequestBoard::where('current_status', 'UNDERESTIMATION')->get(); 
        if($all_estimations!=null && $all_estimations!=''){
            $data = [
                'status'  => 200,
                'message' => $all_estimations
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
}
