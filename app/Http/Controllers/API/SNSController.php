<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ApiErrorCodes;
use App\Models\User;

class SNSController extends Controller
{
    public function check_email(Request $request)
    {
        $mail = $request->email;
        if($mail=='' || $mail==null){
            $e = ApiErrorCodes::where('error_code',10021)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else {
            $count = User::where('email', $mail)->count();
            if($count>0){
                
            }
        }
    }
}
