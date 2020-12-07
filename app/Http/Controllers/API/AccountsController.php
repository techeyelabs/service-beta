<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiErrorCodes;
use App\Models\AgeGroup;
use App\Models\UserPointWithdrawal;
use App\Models\WithdrawalRequest;
use App\Models\User;
use App\Models\Profile;
use DB;

class AccountsController extends Controller
{
    /**************************
        @Name: Get point balance stats
        @URL: accounts-info
        @Method: GET
        @Params: User id
    ***************************/
    public function display_accounts($id=null)
    {
        $user_id = $id;
        $accounts = DB::table('user_point_withdrawal')->select('total_amount', 'last_month_earnings', 'remaining_amount')->where('user_id', $user_id)->first();
        $type_all = User::select('user_type_id')->where('id', $id)->first();
        $t_flag = $type_all->user_type_id;
        if($t_flag == 2){
            $last_months = DB::select("SELECT SUM(price - aff_commission - transaction_fee) AS last
                                    FROM purchase_history
                                    WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
                                    AND YEAR(created_at) = YEAR(CURRENT_DATE())
                                    AND seller_id = '".$user_id."'");
            $last = ($last_months[0]->last > 0)? $last_months[0]->last: 0;
        } else {
            $last_months = DB::select("SELECT SUM(earning_amount) AS last
                                    FROM affiliator_earnings
                                    WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
                                    AND YEAR(created_at) = YEAR(CURRENT_DATE())
                                    AND child_affiliator_id = '".$user_id."'");
            $last = ($last_months[0]->last > 0)? $last_months[0]->last: 0;
        }
       
        if($accounts=='' || $accounts==null){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                         'status'  => $e->error_code,
                         'message' => $e->error_message 
                     ];
        } else {
            $data = [
                'status'  => 200 ,
                'payload' => $accounts ,
                'last' => $last
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_age_group()
    {
        $group = AgeGroup::all();
        if($group=='' || $group==null){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200,
                'group' => $group
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function withdraw(Request $request)
    {
        $user = $request->id;
        $amount = $request->amount;
        if($user=='' || $user==null || $amount=='' || $amount==null){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $is_enough = UserPointWithdrawal::select('remaining_amount')->where('user_id', $user)->first();
            if($amount > $is_enough->remaining_amount){
                $e = ApiErrorCodes::where('error_code',10072)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            } else {
                $req = new WithdrawalRequest();
                $req->user_id = $user;
                $req->requested_amount = $amount; 
                $req->request_status = 'PENDING';
                $req->save();

                UserPointWithdrawal::where('user_id', $user)
                ->update(
                    [
                        'remaining_amount' => $is_enough->remaining_amount - $amount
                    ]
                );
                $e = ApiErrorCodes::where('error_code',10043)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            }
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function withdraw_history($id=null)
    {
        $user = $id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10043)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $history = DB::table('withdrawal_requests')->where('user_id', $user)->get();
            $data = [
                'status'  => 200  ,
                'history' => $history
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function profile_check($id)
    {
        $user = $id;
        $status = Profile::where('user_id', $user)->first();
        $flag = 0;
        if($status->address == 'null' || $status->address == NULL || $status->address == '')
            $flag = 1;
        if($status->age_group == 'null' || $status->age_group == NULL || $status->age_group == '')
            $flag = 1;
        if($status->photo_id == 'null' || $status->photo_id == NULL || $status->photo_id == '')
            $flag = 1;
        if($status->profession == 'null' || $status->profession == NULL || $status->profession == '')
            $flag = 1;
        if($status->residential_area == 'null' || $status->residential_area == NULL || $status->residential_area == '')
            $flag = 1;
        if($status->sex == 'null' || $status->sex == NULL || $status->sex == '')
            $flag = 1;
        if($flag == 1)
            $data = [
                'status' => 200
            ];
        else 
            $data = [
                'status' => 404
            ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
}
