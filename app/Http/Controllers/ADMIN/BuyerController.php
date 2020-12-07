<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Models\PurchaseHistory;
use App\Models\AdminRight;

use DataTables;
use Auth;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        return view('admin.buyer.list', $data);
    }

    public function listData(Request $request)
    {
        $result = User::where('user_type_id', 1)->get();
        return Datatables::of($result)
            ->addColumn('total_purchase_amount', function ($result){
                return '<a href="'.route('admin-buyer-details', ['id' => $result->id, 'active_tab' => 'purchase-list']).'">'.\floor($result->purchase->sum('price')).' 円 </a>';
            })
            ->addColumn('name', function ($result){
                return '<a href="'.route('admin-buyer-details', ['id' => $result->id]).'">'.$result->first_name. ' '.$result->last_name.'</a>';
            })
            ->editColumn('profile_pic', function ($result) use($request){
                return '<img width="50" height="50" src="'.$request->root().'/assets/images/users/'.$result->profile_pic.'">';
            })
            ->editColumn('status', function ($result) {
                $output = '';
                if ($result->status == 'ACTIVE'){
                    return '<span class="text-info">'.$result->status.'</span>';
                }elseif($result->status == 'INACTIVE'){
                    return '<span class="text-danger">'.$result->status.'</span>';
                }elseif($result->status == 'SUSPEND'){
                    return '<span class="text-warning">'.$result->status.'</span>';
                }
                return $output;
            })
            ->addColumn('action', function ($result) {
                $output = '';
                if ($result->status == 'ACTIVE'){
                    $output .= '<a href="'.route('admin-buyer-change-status', ['id' => $result->id, 'status'=> 'INACTIVE']).'" class="btn btn-sm btn-info">Deactivate</a> ';
                    $output .= '<a href="'.route('admin-buyer-change-status', ['id' => $result->id, 'status'=> 'SUSPEND']).'" class="btn btn-sm btn-danger">Suspend</a> ';
                }else{
                    $output .= '<a href="'.route('admin-buyer-change-status', ['id' => $result->id, 'status'=> 'ACTIVE']).'" class="btn btn-sm btn-info">Activate</a> ';
                }
                return $output;
            })
            ->rawColumns(['action', 'status', 'profile_pic', 'total_purchase_amount', 'name'])
            ->make(true);
    }

    public function changeStatus(Request $request)
    {
        $User = User::find($request->id);        
        $User->status = $request->status;
        $User->save();

        $Notification = new Notification();
        $Notification->notification_text = 'Your account is '.$request->status.' now.Contact administrator for further query';
        $Notification->notification_type = 'SYSTEM';
        $Notification->save();

        return redirect()->back()->with('success_message', 'action completed successfully');
    }



    public function buyerDetails(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        $data['userInfo'] = User::find($request->id);
        return view('admin.buyer.buyer_details', $data);
    }

    public function purchaseListData(Request $request)
    {
        $result = PurchaseHistory::with('buyer')->with('seller')->with('product');
        if(!empty($request->buyer_id)){
            $result = $result->where('buyer_id', $request->buyer_id);
        }        
        $result = $result->get();
        return Datatables::of($result)
            ->addColumn('buyer', function ($result){
                return '<a href="'.route('admin-buyer-details', ['id' => $result->buyer_id]).'">'.$result->buyer->first_name.' '.$result->buyer->last_name.'</a>';
            })
            ->addColumn('seller', function ($result){
                return '<a href="'.route('admin-seller-details', ['id' => $result->seller_id]).'">'.$result->seller->first_name.' '.$result->seller->last_name.'</a>';
            })
            ->addColumn('title', function ($result){
                if(empty($result->product_id)) return 'no product';
                return '<a href="'.route('admin-service-details', ['id' => $result->product_id]).'">'.$result->product->title.'</a>';
            })
            ->editColumn('price', function ($result){
                return \floor($result->price).' 円';
            })
            
            ->rawColumns(['title', 'seller', 'buyer'])
            ->make(true);
    }
}