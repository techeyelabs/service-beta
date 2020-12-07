<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Notification;
use App\Models\AffiliatorPersonalPage;
use App\Models\UserPointWithdrawal;
use App\Models\PurchaseHistory;
use App\Models\AffiliatorEarning;
use App\Models\AdminRight;

use DataTables;
use Auth;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        return view('admin.news.list', $data);
    }

    public function listData(Request $request)
    {
        $result = Notification::where('notification_type', 'SYSTEM')->get()->unique('created_at');

        return Datatables::of($result)
            ->editColumn('notification_text', function ($result){
                // return '<a href="'.route('admin-news-details', ['id' => $result->id]).'">'.\substr(\strip_tags($result->notification_text), 0, 50).'hello</a>';
                return '<a href="'.route('admin-news-details', ['id' => $result->id]).'">'.\mb_substr(\strip_tags($result->notification_text), 0, 50, "utf-8").'hello</a>';
            })
            
            ->rawColumns(['notification_text'])
            ->make(true);
    }

    public function newsDetails(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        $data['news'] = Notification::find($request->id);
        return view('admin.news.news_details', $data);
    }

    public function newNews(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        $data['users'] = User::all();
        return view('admin.news.new_news', $data);
    }
    public function newNewsAction(Request $request)
    {
        $users = User::all();
        $created_at = date('Y-m-d H:i:s');
        foreach($users as $key => $value){
            $Notification = new Notification();
            $Notification->user_id = $value->id;
            $Notification->product_id = 0;
            $Notification->request_id = 0;
            $Notification->response_id = 0;
            $Notification->seller_id = 0;
            $Notification->buyer_id = 0;
            $Notification->notification_text = \nl2br($request->message);
            $Notification->notification_type = 'SYSTEM';
            $Notification->opening_status = 'UNOPENED';
            $Notification->created_at = $created_at;
            $Notification->save();

            //Confirmation mail
            $data = [
                'link' => '',
                'name' => $value->first_name,
                'email' => $value->email,
                'content' => \nl2br($request->message)
            ];
            \Mail::send('newsFromAdmin', $data, function($message) use ($value){
                $message->to($value->email, 'News')->subject
                   ('[share-work] 管理者からのお知らせ');
                $message->from('noreply@share-work.jp','ShareWork');
             });
            //Confirmation mail ends
        }  
        
        return redirect()->back()->withInput()->with('success_message', 'successfully sent');
    }
}
