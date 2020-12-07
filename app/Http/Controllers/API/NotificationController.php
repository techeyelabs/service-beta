<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\Notification;
use App\Models\User;
use App\Models\Product;
use App\Models\RequestBoard;
use DB;

class NotificationController extends Controller
{
    public function system_notification($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $notify==null){
            $e = ApiErrorCodes::where('error_code',10074)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $not = Notification::where('user_id', $user)->where('id', $notify)->first();
            $data = [
                'status'  => 200 ,
                'payload' => $not
            ];
        }   
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function purchase_notification($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $notify==null){
            $e = ApiErrorCodes::where('error_code',10074)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $not = Notification::where('user_id', $user)->where('id', $notify)->first();
            $data = [
                'status'  => 200 ,
                'payload' => $not
            ];
        }   
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function request_receive_notification($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $notify==null){
            $e = ApiErrorCodes::where('error_code',10074)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $not = Notification::where('user_id', $user)->where('id', $notify)->first();
            $data = [
                'status'  => 200 ,
                'payload' => $not
            ];
        }   
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    } 

    public function request_accept_notification($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $notify==null){
            $e = ApiErrorCodes::where('error_code',10074)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $not = Notification::where('user_id', $user)->where('id', $notify)->first();
            $data = [
                'status'  => 200 ,
                'payload' => $not
            ];
        }   
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    } 

    public function request_decline_notification($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $notify==null){
            $e = ApiErrorCodes::where('error_code',10074)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $not = Notification::where('user_id', $user)->where('id', $notify)->first();
            $data = [
                'status'  => 200 ,
                'payload' => $not
            ];
        }   
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function response_accept_notification($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $notify==null){
            $e = ApiErrorCodes::where('error_code',10074)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $not = Notification::where('user_id', $user)->where('id', $notify)->first();
            $data = [
                'status'  => 200 ,
                'payload' => $not
            ];
        }   
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function service_served_notification($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $notify==null){
            $e = ApiErrorCodes::where('error_code',10074)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $not = Notification::where('user_id', $user)->where('id', $notify)->first();
            $data = [
                'status'  => 200 ,
                'payload' => $not
            ];
        }   
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_notification()
    {

    }

    public function change_notification_status($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $notify==null){
            $e = ApiErrorCodes::where('error_code',10074)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            Notification::where('user_id', $user)->where('id', $notify)
            ->update(
                [
                    'opening_status' => 'OPENED'
                ]
            );
            $e = ApiErrorCodes::where('error_code',10051)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }   
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');   
    }

    public function get_all_notification($user_id=null)
    {
        $user = $user_id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $notify = Notification::select('*', DB::raw('SUBSTRING(notification_text, 1, 25) AS trimed_text'))->where('user_id', $user)->orderBy('id', 'DESC')->limit(100)->get();
            $count = Notification::where('user_id', $user)->where('opening_status', 'UNOPENED')->count();
            $data = [
                'status'  => 200  ,
                'payload' => $notify,
                'count' => $count
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');      
    }

    public function rec_expired($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $notify = Notification::where('user_id', $user)->where('id', $notify)->first();
            $count = Notification::where('user_id', $user)->where('opening_status', 'UNOPENED')->count();
            $data = [
                'status'  => 200  ,
                'payload' => $notify,
                'count' => $count
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');      
    }

    public function new_estimation($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $notify = Notification::where('user_id', $user)->where('id', $notify)->first();
            $count = Notification::where('user_id', $user)->where('opening_status', 'UNOPENED')->count();
            $data = [
                'status'  => 200  ,
                'payload' => $notify,
                'count' => $count
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');      
    }

    public function following($user_id=null, $notification_id=null)
    {
        $user = $user_id;
        $notify = $notification_id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $notify = Notification::where('user_id', $user)->where('id', $notify)->first();
            $count = Notification::where('user_id', $user)->where('opening_status', 'UNOPENED')->count();
            $data = [
                'status'  => 200  ,
                'payload' => $notify,
                'count' => $count
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');      
    }

    public function open_all($id=null)
    {
        $notification_id = $id;
        if($notification_id=='' || $notification_id==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            Notification::where('id', $id)
            ->update(
                [
                    'opening_status' => 'OPENED'
                ]
            );
            $e = ApiErrorCodes::where('error_code',10043)->first(); 
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
