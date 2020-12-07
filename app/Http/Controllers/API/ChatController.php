<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\Chat;
use DB;


class ChatController extends Controller
{
    public function store_plain_msg(Request $request)
    {
        $attchmnt = isset($_FILES['attchmnt']['name'])?$_FILES['attchmnt']['name']: '';
        $seller_id = $request->seller;
        $buyer_id = $request->buyer;
        $msg_text = $request->content;
        $sender = $request->sender;
        $receiver = $request->receiver;
        $type = 'CHAT';
        // $product_id = $request->product;
        $product_id = '';
        if($seller_id==null || $seller_id==''){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($buyer_id==null || $buyer_id=='') {
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            if($attchmnt != ''){
                move_uploaded_file($_FILES['attchmnt']['tmp_name'],"assets/attachments/".$attchmnt);
            }
            $chat = new Chat();
            $chat->sender_id = $sender;
            $chat->receiver_id = $receiver;
            $chat->seller_id = $seller_id;
            $chat->buyer_id = $buyer_id;
            $chat->content = $msg_text;
            $chat->product_id = $product_id;
            $chat->file = $attchmnt;
            $chat->type = $type;
            $chat->save();
            // return response()->json($chat);
            $e = ApiErrorCodes::where('error_code',10043)->first(); 
            $data = [
                'status'  => 200,
                'message' => $e->error_message
            ];
        }
        return response()->json($data);
    }

    public function store_automated_msg(Request $request)
    {
        $seller_id = $request->$seller;
        $buyer_id = $request->$buyer;
        $msg_text = $request->msg;
        $type = 'NOTIFICATION';
        $product_id = $request->product;
    }

    public function get_chated_list_as_buyer($buyer_id=null, $seller_id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $seller = $seller_id;
        $buyer = $buyer_id;
        if($seller=='' || $seller==null || $buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $sub = Chat::orderBy('created_at', 'DESC');
            $chated = DB::table(DB::raw("({$sub->toSql()}) as c"))
                        ->mergeBindings($sub->getQuery())
                        ->select('c.*', 'us.first_name', 
                            DB::raw('CONCAT("'.$ip.'","assets/attachments/", c.file) AS attchmnt'),
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_sender'),
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", usb.profile_pic) AS img_receiver'))
                        ->leftjoin('users AS us', 'us.id', '=', 'c.sender_id')
                        ->leftjoin('users AS usb', 'usb.id', '=', 'c.receiver_id')
                        ->where('c.seller_id', $seller)
                        ->where('c.buyer_id', $buyer)
                        ->orderBy('c.id', 'ASC')
                        // ->groupBy('c.receiver_id')
                        ->get();
            $other_side = DB::table('users AS us')
                        ->select('us.first_name', 'us.id', DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_buyer'))
                        ->where('us.id', $seller)
                        ->first();
            $data = [
                'status'  => 200,
                'payload' => $chated,
                'other_side' => $other_side
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_all_chated_sys($user=null)
    {
        $ip = \Config::get('app.api_base_url');
        $user_id = $user;
        $buyer = 0;
        if($user_id=='' || $user_id==null){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $sub = Chat::where('seller_id', 0)->where('buyer_id', 0)->orderBy('created_at', 'DESC');
            $chated = DB::table(DB::raw("({$sub->toSql()}) as c"))
                        ->mergeBindings($sub->getQuery())
                        ->select('c.*', 'us.first_name', 
                            DB::raw('CONCAT("'.$ip.'","assets/attachments/", c.file) AS attchmnt'),
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_sender'),
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", usb.profile_pic) AS img_receiver'))
                        ->leftjoin('users AS us', 'us.id', '=', 'c.sender_id')
                        ->leftjoin('users AS usb', 'usb.id', '=', 'c.receiver_id')
                        ->where('c.sender_id', $user_id)
                        ->orWhere('c.receiver_id', $user_id)
                        ->orderBy('c.id', 'ASC')
                        // ->groupBy('c.receiver_id')
                        ->get();
            $other_side = DB::table('users AS us')
                        ->select('us.first_name', 'us.id', DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_buyer'))
                        ->where('us.id', 0)
                        ->first();
            $data = [
                'status'  => 200,
                'payload' => $chated,
                'other_side' => $other_side
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_chated_list_as_seller($buyer_id=null, $seller_id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $seller = $seller_id;
        $buyer = $buyer_id;
        if($seller=='' || $seller==null || $buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $sub = Chat::orderBy('created_at', 'DESC');
            $chated = DB::table(DB::raw("({$sub->toSql()}) as c"))
                        ->mergeBindings($sub->getQuery())
                        ->select('c.*', 'us.first_name AS sender', 'usb.first_name AS receiver',
                            DB::raw('CONCAT("'.$ip.'","assets/attachments/", c.file) AS attchmnt'),
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_sender'),
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", usb.profile_pic) AS img_receiver'))
                        ->leftjoin('users AS us', 'us.id', '=', 'c.sender_id')
                        ->leftjoin('users AS usb', 'usb.id', '=', 'c.receiver_id')
                        ->where('c.seller_id', $seller)
                        ->where('c.buyer_id', $buyer)
                        ->orderBy('c.id', 'ASC')
                        // ->groupBy('c.receiver_id')
                        ->get();
            $other_side = DB::table('users AS us')
                                ->select('us.first_name', 'us.id', DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_buyer'))
                                ->where('us.id', $buyer)
                                ->first();
            $data = [
                'status'  => 200,
                'payload' => $chated,
                'other_side' => $other_side
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_threads_buyer($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $user = $id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            // $threads = Chat::where('buyer_id', $user)->groupBy('seller_id')->get();
            $threads = DB::table('chat')
                        ->select('chat.buyer_id', 'chat.created_at', 'us.first_name', 'us.profile_pic', 'chat.seller_id',
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_buyer'))
                        ->leftjoin('users AS us', 'us.id', '=', 'chat.seller_id')
                        ->where('chat.buyer_id', $user)
                        ->groupBy('chat.seller_id')
                        ->get();
            $data = [
                'status'  => 200,
                'payload' => $threads
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_threads_seller($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $user = $id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $threads = Chat::where('seller_id', $user)->groupBy('buyer_id')->get();
            $threads = DB::table('chat')
                            ->select('chat.buyer_id', 'chat.created_at', 'us.first_name', 'us.profile_pic',
                                DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_buyer'))
                            ->leftjoin('users AS us', 'us.id', '=', 'chat.buyer_id')
                            ->where('chat.seller_id', $user)
                            ->groupBy('chat.buyer_id')
                            ->get();
            $data = [
                'status'  => 200,
                'payload' => $threads
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_chated_list_as_buyer_solo($buyer_id=null, $seller_id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $seller = $seller_id;
        $buyer = $buyer_id;
        if($seller=='' || $seller==null || $buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $sub = Chat::orderBy('created_at', 'DESC');
            $chated = DB::table(DB::raw("({$sub->toSql()}) as c"))
                        ->mergeBindings($sub->getQuery())
                        ->select('c.*', 'us.first_name AS sender', 'usb.first_name AS receiver',
                            DB::raw('CONCAT("'.$ip.'","assets/attachments/", c.file) AS attchmnt'),
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_sender'),
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", usb.profile_pic) AS img_receiver'))
                        ->leftjoin('users AS us', 'us.id', '=', 'c.sender_id')
                        ->leftjoin('users AS usb', 'usb.id', '=', 'c.receiver_id')
                        ->where('c.seller_id', $seller)
                        ->where('c.buyer_id', $buyer)
                        ->orderBy('c.id', 'ASC')
                        // ->groupBy('c.receiver_id')
                        ->get();
            $other_side = DB::table('users AS us')
                        ->select('us.first_name', 'us.id', DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_buyer'))
                        ->where('us.id', $seller)
                        ->first();
            $data = [
                'status'  => 200,
                'payload' => $chated,
                'other_side' => $other_side
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_threads_buyer_solo($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $user = $id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $threads = DB::table('chat')
                        ->select('chat.buyer_id', 'chat.created_at', 'us.first_name', 'us.profile_pic', 'chat.seller_id',
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_buyer'))
                        ->leftjoin('users AS us', 'us.id', '=', 'chat.seller_id')
                        ->where('chat.buyer_id', $user)
                        ->groupBy('chat.seller_id')
                        ->get();
            $data = [
                'status'  => 200,
                'payload' => $threads
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function initiate_chat(Request $request)
    {
        $buyer = $request->buyer;
        $seller = $request->seller;
        $sender = $request->sender;
        $receiver = $request->receiver;
        $content = $request->content;
        if($buyer=='' || $seller=='' || $sender=='' || $receiver=='' || $content==''){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => 200  ,
                'message' => $e->error_message
            ];
        } else {
            $ch = new Chat();
            $ch->sender_id = $sender;
            $ch->receiver_id = $receiver;
            $ch->seller_id = $seller;
            $ch->buyer_id = $buyer;
            $ch->content = $content;
            $ch->product_id = 0;
            $ch->type = 'CHAT';
            $ch->save();
            $e = ApiErrorCodes::where('error_code',10043)->first(); 
            $data = [
                'status'  => 200  ,
                'message' => $e->error_message
            ];
        }        
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_unread_notification_count($id)
    {
        $user = $id;
        $all = Chat::where('receiver_id', $user)->where('opening_status_receiver', 'Unread')->count();
        $text = Chat::select(
            'id',
            'sender_id', 
            'receiver_id', 
            'opening_status_sender', 
            'opening_status_receiver',
            DB::raw("SUBSTRING(content, 1, 25) AS content"),
            'seller_id', 
            'buyer_id')
            ->where('receiver_id', $user)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();
        $data = [
            'status'  => 200  ,
            'count' => $all,
            'text' => $text
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function open($id, $sender_id)
    {
        $user = $id;
        $sender = $sender_id;
        Chat::where('receiver_id', $user)->where('sender_id', $sender)
        ->update(
            [
                'opening_status_receiver' => 'Read'
            ]
        );
        $e = ApiErrorCodes::where('error_code',10043)->first(); 
        $data = [
            'status'  => 200  ,
            'message' => $e->error_message
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function check_unopened($receiver, $sender)
    {
        $rec = $receiver;
        $sen = $sender;
        $total = Chat::where('receiver_id', $receiver)->where('sender_id', $sender)->where('opening_status_receiver', 'Unread')->count();
        $data = [
            'status'  => 200,
            'counter' => $total
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
}
