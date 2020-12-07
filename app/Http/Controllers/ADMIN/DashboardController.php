<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\PurchaseHistory;
use App\Models\WithdrawalRequest;
use App\Models\Chat;
use App\Models\ContactUs;
use App\Models\AdminRight;
use Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
		$id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
    	$data['total_sales'] = PurchaseHistory::sum('price');
		$data['total_services'] = Product::count();
		
	 
		$data['total_buyer']      = User::where('user_type_id' , 1)->count();
		$data['total_seller']     = User::where('user_type_id' , 2)->count();
		$data['total_affiliator'] = User::where('user_type_id' , 3)->count();
		$data['withdraw_request'] = WithdrawalRequest::where('request_status', 'PENDING')->count();

		// $data['direct_message'] = Chat::where('sender_id', 0)->orWhere('receiver_id', 0)
		// ->select(\DB::raw('(CASE 
		// WHEN chat.sender_id = "0" THEN chat.receiver_id 
		// ELSE chat.sender_id 
		// END) AS user_id'), 'users.*', 'chat.*')
		// ->join('users', function($join)
		// {
		// 	$join->on('users.id', '=', 'chat.sender_id')->orOn('users.id', '=', 'chat.receiver_id');
		// })                 
		// ->orderBy('chat.created_at', 'DESC')
		// ->where('chat.type', 'CHAT')
		// ->get()
		// ->unique('user_id')->count();

		$data['direct_message'] = Chat::where('receiver_id', 0)->where('opening_status_receiver', 'Unread')->count();
		$data['contacts'] = ContactUs::count();

		$data['lottery'] = User::where('user_type_id', 3)->where('status', 'ACTIVE')->get()->reject(function ($user) {
            return $user->lotteryEligible() == false;
        })->count();
		
    	return view('admin.dashboard' ,$data);
    }
}
