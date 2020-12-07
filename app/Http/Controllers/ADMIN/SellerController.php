<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\PurchaseHistory;
use App\Models\UserPointWithdrawal;
use App\Models\AdminRight;

use DataTables;
use Auth;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        return view('admin.seller.list', $data);
    }

    public function listData(Request $request)
    {
        $result = User::where('user_type_id', 2)->get();
        return Datatables::of($result)
            ->addColumn('total_purchase_amount', function ($result){
                return '<a href="'.route('admin-seller-details', ['id' => $result->id, 'active_tab' => 'purchase-list']).'">'.\floor($result->purchase->sum('price')).' 円 </a>';
            })
            // ->addColumn('balance', function ($result){
            //     return '<a href="'.route('admin-seller-details', ['id' => $result->id, 'active_tab' => 'balance-list']).'">'.\floor($result->userPointWithdrawal->remaining_amount).' 円 </a>';
            // })
            ->addColumn('name', function ($result){
                return '<a href="'.route('admin-seller-details', ['id' => $result->id]).'">'.$result->first_name. ' '.$result->last_name.'</a>';
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
                    $output .= '<a href="'.route('admin-affiliator-change-status', ['id' => $result->id, 'status'=> 'INACTIVE']).'" class="btn btn-sm btn-info">Deactivate</a> ';
                    // $output .= '<a href="'.route('admin-affiliator-change-status', ['id' => $result->id, 'status'=> 'SUSPEND']).'" class="btn btn-sm btn-danger">Suspend</a> ';
                }else{
                    $output .= '<a href="'.route('admin-affiliator-change-status', ['id' => $result->id, 'status'=> 'ACTIVE']).'" class="btn btn-sm btn-info">Activate</a> ';
                }
                return $output;
            })
            ->rawColumns(['action', 'status', 'profile_pic', 'name', 'total_purchase_amount', 'balance'])
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
                if(empty($result->product)) return 'no product';
                return '<a href="'.route('admin-service-details', ['id' => $result->product_id]).'">'.$result->product->title.'</a>';
            })
            ->editColumn('price', function ($result){
                return \floor($result->price).' 円';
            })
            
            ->rawColumns(['title', 'seller', 'buyer'])
            ->make(true);
    }

    public function SellerDetails(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        $data['userInfo'] = User::with('profile')->with('creditCards')->find($request->id);
        $data['balance'] = UserPointWithdrawal::where('user_id', $request->id)->first();
        return view('admin.seller.seller_details', $data);
    }

    public function SoldProductListData(Request $request)
    {
        $result = PurchaseHistory::where('seller_id', $request->user_id)->with('product')->with('seller')->with('buyer');
        $result = $result->get();
        return Datatables::of($result)
            ->addColumn('seller', function ($result){
                return '<a href="'.route('admin-seller-details', ['id' => $result->seller_id]).'">'.$result->seller->first_name.' '.$result->seller->last_name.'</a>';
            })
            ->addColumn('buyer', function ($result){
                return '<a href="'.route('admin-buyer-details', ['id' => $result->buyer_id]).'">'.$result->buyer->first_name.' '.$result->buyer->last_name.'</a>';
            })
            ->editColumn('price', function ($result){
                return \floor($result->price).' 円';
            })
            ->editColumn('transaction_fee', function ($result){
                return \floor($result->transaction_fee).' 円';
            })
            ->addColumn('affiliator_earning', function ($result){
                return $result->aff_commission.' 円';
            })
            ->addColumn('seller_earning', function ($result){
                return \floor(($result->price-$result->aff_commission-$result->transaction_fee)).' 円';
            })
            // ->addColumn('system_earning', function ($result){
            //     return (($result->price-$result->aff_commission-$result->transaction_fee)/2).' 円';
            // })
            ->addColumn('product', function ($result){
                if(empty($result->product)) return 'no product';
                return '<a href="'.route('admin-service-details', ['id' => $result->product_id]).'">'.$result->product->title.'</a>';
            })
            
            ->rawColumns(['product', 'line_link', 'web_link', 'seller', 'buyer'])
            ->make(true);
    }
}
