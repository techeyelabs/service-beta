<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\WithdrawalRequest;
use App\Models\UserPointWithdrawal;
use App\Models\Notification;
use App\Models\AdminRight;

use DataTables;
use Auth;


class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        return view('admin.withdraw.list', $data);
    }

    public function listData(Request $request)
    {
        $result = WithdrawalRequest::with('user');
        if(!empty($request->user_id)){
            $result = $result->where('user_id', $request->user_id);
        }
        $result = $result->get();
        return Datatables::of($result)
            ->addColumn('name', function ($result){
                $route = 'admin-seller-details';
                if($result->user->user_type_id == 3) $route = 'admin-affiliator-details';
                return '<a href="'.route($route, ['id' => $result->user_id]).'">'.$result->user->first_name. ' '.$result->user->last_name.'</a>';
            })
            ->addColumn('email', function ($result){
                return $result->user->email;
            })
            
            ->editColumn('requested_amount', function ($result){
                return \floor($result->requested_amount).' 円';
            })
            ->editColumn('receivable_amount', function ($result){
                return \floor($result->requested_amount - 756).' 円';
            })
            ->editColumn('request_status', function ($result) {
                $output = '';
                if ($result->request_status == 'PENDING'){
                    return '<span class="text-warning">'.$result->request_status.'</span>';
                }elseif($result->request_status == 'DEPOSITED'){
                    return '<span class="text-success">'.$result->request_status.'</span>';
                }elseif($result->request_status == 'REJECTED'){
                    return '<span class="text-danger">'.$result->request_status.'</span>';
                }
                return $output;
            })
            ->addColumn('action', function ($result) {
                $output = '';
                if ($result->request_status == 'PENDING'){
                    $output .= '<a href="'.route('admin-withdraw-change-status', ['id' => $result->id, 'status'=> 'DEPOSITED']).'" class="btn btn-sm btn-info">Deposit</a> ';
                    // $output .= '<a href="'.route('admin-withdraw-change-status', ['id' => $result->id, 'status'=> 'REJECTED']).'" class="btn btn-sm btn-danger">Reject</a> ';
                }
                return $output;
            })
            ->rawColumns(['action', 'request_status', 'name'])
            ->make(true);
    }

    public function changeStatus(Request $request)
    {
        $WithdrawalRequest = WithdrawalRequest::find($request->id);
        $Notification = new Notification();

        $UserPointWithdrawal = UserPointWithdrawal::where('user_id', $WithdrawalRequest->user_id)->first();
        if($request->status == 'DEPOSITED'){
            
            
            $UserPointWithdrawal->withdraw_date = date('Y-m-d H:i:s');
            

            $Notification->notification_text = 'Withdraw request accepted';
            
        }elseif($request->status == 'REJECTED'){
            $UserPointWithdrawal->remaining_amount += $WithdrawalRequest->requested_amount;
            $Notification->notification_text = 'Withdraw request rejected';
        }
        $UserPointWithdrawal->save();

        $WithdrawalRequest->request_status = $request->status;
        $WithdrawalRequest->save();
        
        $Notification->notification_type = 'SYSTEM';
        $Notification->save();

        return redirect()->back()->with('success_message', 'action completed successfully');
    }
}
