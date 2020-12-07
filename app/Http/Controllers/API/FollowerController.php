<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\Follow;
use App\Models\Notification;
use App\Models\User;
use DB;

class FollowerController extends Controller
{
    /**************************
        @Name: Show my follwers
        @URL: seller-followers
        @Method: GET
        @Params: user id
    ***************************/
    public function display_my_followers($id = null)
    {
        $ip = \Config::get('app.api_base_url');
        if($id!='' || $id!=null){
            $is_exist = DB::table('follow')
                ->select('follow.id', 'follow.buyer_id', 'users.first_name', DB::raw('CONCAT("'. $ip  .'assets/images/users/", users.profile_pic) AS img'))
                ->join('users', 'follow.buyer_id', '=', 'users.id')
                ->where('follow.seller_id', $id)
                ->where('follow.status', 'FOLLOWING')
                ->get();
            if(empty($is_exist)){
                $e = ApiErrorCodes::where('error_code',10041)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            } else {
                $data = [
                    'status'  => 200,
                    'payload' => $is_exist
                ];
            }
        } else {
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    /**************************
        @Name: Show sellers I am following
        @URL: buyer-followings
        @Method: GET
        @Params: buyer id (logged in user)
    ***************************/
    public function display_my_followings($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        if($id!='' || $id!=null){
            $is_exist = DB::select("SELECT  follow.id, 
                                    follow.seller_id,  
                                    users.first_name, 
                                    CONCAT('". $ip  ."assets/images/users/',users.profile_pic) AS img,
                                    SUBSTRING(users.personal_detail, 1, 250) AS trimed_long_desc
                                    FROM follow 
                                    LEFT JOIN users  ON (follow.seller_id = users.id )
                                    WHERE follow.buyer_id = ".$id."  AND follow.status= 'FOLLOWING' ORDER BY follow.id DESC");
            if(empty($is_exist)){
                $e = ApiErrorCodes::where('error_code',10041)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            } else {
                $data = [
                    'status'  => 200,
                    'payload' => $is_exist
                ];
            }
        } else {
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function my_favourites($id=null)
    {
        if(isset($id)){
            $is_exist = DB::table('favourites')
                ->select('follow.id', 'follow.seller_id', 'users.first_name')
                ->join('users', 'follow.seller_id', '=', 'users.id')
                ->where('follow.buyer_id', $id)
                ->where('follow.status', 'FOLLOWING')
                ->get();
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

    public function follow($seller_id=null, $buyer_id=null)
    {
        $buyer = $buyer_id;
        $seller = $seller_id;
        if($buyer==null || $buyer==''){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($seller==null || $seller==''){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $is_exist = Follow::where('seller_id', $seller)->where('buyer_id', $buyer)->first();
            if($is_exist=='' || $is_exist==null){
                $flw = new Follow();
                $flw->buyer_id = $buyer;
                $flw->seller_id = $seller;
                $flw->status = 'FOLLOWING';
                $flw->save();
            } else {
                Follow::where('seller_id', $seller)->where('buyer_id', $buyer)
                    ->update(
                        [
                            'status' => 'FOLLOWING',
                        ]
                    );
            }
            $buyer_full = User::where('id', $buyer)->first();
            $buyer_name = $buyer_full->first_name;
            $flw_not = new Notification();
            $flw_not->user_id = $seller;
            $flw_not->seller_id = $seller;
            $flw_not->buyer_id = $buyer;
            $flw_not->notification_text = $buyer_name.'さんにフォローされました';
            $flw_not->opening_status = 'UNOPENED';
            $flw_not->notification_type = 'FOLLOW';
            $flw_not->save();

            $e = ApiErrorCodes::where('error_code',10043)->first(); 
            $data = [
                'status'  => 200  ,
                'message' => $e->error_message,
                'flag' => 1
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function check_status($seller=null, $buyer=null)
    {
        if($seller=='' || $seller==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $is_exist = Follow::where('seller_id', $seller)->where('buyer_id', $buyer)->first();
            if($is_exist=='' || $is_exist==null){
                $e = ApiErrorCodes::where('error_code',10044)->first(); 
                $data = [
                    'status'  => 200  ,
                    'flag' => 0
                ];
            } else {
                if($is_exist->status=='FOLLOWING'){
                    $data = [
                        'status'  => 200  ,
                        'flag' => 1
                    ];
                } else {
                    $data = [
                        'status'  => 200  ,
                        'flag' => 0
                    ];
                } 
            }
                 
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function unfollow($seller_id=null, $buyer_id=null)
    {
        $buyer = $buyer_id;
        $seller = $seller_id;
        if($buyer==null || $buyer==''){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($seller==null || $seller==''){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $is_exist = Follow::where('seller_id', $seller)->where('buyer_id', $buyer)->first();
            if($is_exist=='' || $is_exist==null){
                $e = ApiErrorCodes::where('error_code',10041)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            } else {
                Follow::where('seller_id', $seller)->where('buyer_id', $buyer)
                    ->update(
                        [
                            'status' => 'NOTFOLLOWING',
                        ]
                    );
                $buyer_full = User::where('id', $buyer_id)->first();
                $buyer_name = $buyer_full->first_name;
                $flw_not = new Notification();
                $flw_not->user_id = $seller_id;
                $flw_not->seller_id = $seller_id;
                $flw_not->buyer_id = $buyer_id;
                $flw_not->notification_text = $buyer_name.' さんにフォロー解除されました';
                $flw_not->opening_status = 'UNOPENED';
                $flw_not->notification_type = 'FOLLOW';
                $flw_not->save();
                $e = ApiErrorCodes::where('error_code',10043)->first(); 
                $data = [
                    'status'  => 200  ,
                    'message' => $e->error_message,
                    'flag' => 0
                ];
            }
           
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
}
