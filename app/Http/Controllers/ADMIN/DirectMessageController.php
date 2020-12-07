<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Chat;
use App\Models\Notification;
use App\Models\User;
use App\Models\AdminRight;
use DB;
use DataTables;
use Auth;

class DirectMessageController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        return view('admin.direct_message.list', $data);
    }

    public function listData(Request $request)
    {
        $result = Chat::where('sender_id', 0)->orWhere('receiver_id', 0)
                    ->select(\DB::raw('(CASE 
                    WHEN chat.sender_id = "0" THEN chat.receiver_id 
                    ELSE chat.sender_id 
                    END) AS user_id'), 'users.*', 'chat.*')
                    ->join('users', function($join)
                    {
                        $join->on('users.id', '=', 'chat.sender_id')->orOn('users.id', '=', 'chat.receiver_id');
                    })                 
                    ->orderBy('chat.created_at', 'DESC')
                    ->where('chat.type', 'CHAT')
                    ->get()
                    ->unique('user_id');

        return Datatables::of($result)
            ->addColumn('type', function ($result){
                if($result->user_type_id == 1){
                    return 'BUYER';
                }elseif($result->user_type_id == 2){
                    return 'SELLER';
                }else{
                    return 'AFFILIATOR';
                }
            })
            ->addColumn('status', function($result){
                if($result->receiver_id == 0) return $result->opening_status_receiver;
                return 'Read';
            })
            ->addColumn('name', function ($result){
                if($result->user_type_id == 1){
                    $route = route('admin-buyer-details', ['id' => $result->user_id]);
                }elseif($result->user_type_id == 2){
                    $route = route('admin-seller-details', ['id' => $result->user_id]);
                }else{
                    $route = route('admin-affiliator-details', ['id' => $result->user_id]);
                }
                return '<a href="'.$route.'">'.$result->first_name. ' '.$result->last_name.'</a>';
            })
            ->editColumn('profile_pic', function ($result) use($request){
                return '<img width="50" height="50" src="'.$request->root().'/assets/images/users/'.$result->profile_pic.'">';
            })
            ->editColumn('content', function ($result) use($request){
                return '<a href="'.route('admin-direct-message-details', ['user_id' => $result->user_id]).'">'.\strip_tags($result->content).'</a>';
            })
            
            ->rawColumns(['profile_pic', 'balance', 'name', 'content'])
            ->make(true);
    }

    public function details(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        $data['userInfo'] = User::find($request->user_id);
        return view('admin.direct_message.details', $data);
    }

    public function messageList(Request $request)
    {
        $user_id = $request->user_id;
        $data['messages'] = $result = Chat::where(function($query) use ($user_id){
            return $query->where('sender_id', 0)->where('receiver_id', $user_id);
        })->orWhere(function($query) use ($user_id){
            return $query->where('receiver_id', 0)->where('sender_id', $user_id);
        })
        ->select(\DB::raw('(CASE 
        WHEN chat.sender_id = "0" THEN chat.receiver_id 
        ELSE chat.sender_id 
        END) AS user_id'), 'users.*', 'chat.*')
        ->join('users', function($join)
        {
            $join->on('users.id', '=', 'chat.sender_id')->orOn('users.id', '=', 'chat.receiver_id');
        })                 
        ->orderBy('chat.created_at', 'ASC')
        ->where('chat.type', 'CHAT')
        ->get();
        Chat::where('sender_id', $user_id)->where('receiver_id', 0)->update(['opening_status_receiver' => 'Read']);
        // $result->content = \nl2br($result->content);
        foreach($result as $key=>$value){
            $result[$key]->content = \nl2br($result[$key]->content);
        }
        return response()->json(['status' => true, 'data' => $result->toArray()]);
    }
    public function messageSend(Request $request)
    {
        $Chat = new Chat();
        $Chat->type = 'CHAT';
        $Chat->sender_id = 0;
        $Chat->seller_id = 0;
        $Chat->buyer_id = 0;
        $Chat->receiver_id = $request->user_id;
        $Chat->opening_status_receiver = 'Unread';
        $Chat->opening_status_sender = 'Read';
        // $Chat->content = \nl2br($request->message);
        $Chat->content = $request->message;
        $Chat->created_at = date('Y-m-d H:i:s');
        $Chat->save();
        return response()->json(['status' => true, 'message' => \nl2br($Chat->content)]);
    }

    public function bulkMessage(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        $data['users'] = User::all();
        return view('admin.direct_message.bulk_message', $data);
    }
    public function bulkMessageAction(Request $request)
    {
        if(empty($request->user)) return redirect()->back()->withInput()->with('error_message', 'No user selected');
        
            foreach($request->user as $key => $value){
                $Chat = new Chat();
                $Chat->sender_id = 0;
                $Chat->seller_id = 0;
                $Chat->buyer_id = 0;
                $Chat->receiver_id = $value;
                // $Chat->content = \nl2br($request->message);
                $Chat->content = $request->message;
                $Chat->type = 'CHAT';
                $Chat->opening_status_receiver = 'Unread';
                $Chat->opening_status_sender = 'Read';
                $Chat->created_at = date('Y-m-d H:i:s');
                $Chat->save();
            }  
        
        return redirect()->back()->withInput()->with('success_message', 'successfully sent');
    }
}
