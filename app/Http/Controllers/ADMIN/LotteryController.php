<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Notification;
use App\Models\AffiliatorPersonalPage;
use App\Models\UserPointWithdrawal;
use App\Models\PurchaseHistory;
use App\Models\LotteryWinners;
use App\Models\SystemBalance;
use App\Models\AffiliatorEarning;
use App\Libraries\Affiliator;
use App\Models\AdminRight;

use DataTables;
use Auth;

class LotteryController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        $Affiliator = new Affiliator();
        $stored_balance = SystemBalance::first();
        $data['total_for_lottery'] = $stored_balance->aff_lottery_balance;
        return view('admin.lottery.list', $data);
    }

    public function listData(Request $request)
    {
        $result = User::where('user_type_id', 3)->where('status', 'ACTIVE')->get()->reject(function ($user) {
            return $user->lotteryEligible() == false;
        });
        return Datatables::of($result)
            ->addColumn('total_purchase_amount', function ($result){
                return '<a href="'.route('admin-affiliator-details', ['id' => $result->id, 'active_tab' => 'purchase-list']).'">'.\floor($result->purchase->sum('price')).' 円 </a>';
            })
            ->addColumn('balance', function ($result){
                return '<a href="'.route('admin-affiliator-details', ['id' => $result->id, 'active_tab' => 'balance-list']).'">'.$result->userPointWithdrawal->remaining_amount.' 円 </a>';
            })
            ->addColumn('name', function ($result){
                return '<a href="'.route('admin-affiliator-details', ['id' => $result->id]).'">'.$result->first_name. ' '.$result->last_name.'</a>';
            })
            ->addColumn('id', function ($result){
                return '<a href="'.route('admin-affiliator-details', ['id' => $result->id]).'">'.$result->id.'</a>';
            })
            ->editColumn('profile_pic', function ($result) use($request){
                return '<img width="50" height="50" src="'.$request->root().'/assets/images/users/'.$result->profile_pic.'">';
            })
            
            ->rawColumns(['profile_pic', 'balance', 'name', 'total_purchase_amount'])
            ->make(true);
    }

    public function reward(Request $request)
    {
        $result = User::select('id')->where('user_type_id', 3)->where('status', 'ACTIVE')->get()->reject(function ($user) {
            return $user->lotteryEligible() == false;
        });
        $all_eligibles = array();
        foreach($result as $r){
            array_push($all_eligibles, $r->id);
        }
        $all_winners = array();
        $total = 0;
        for($x = 0; $x < 200 ; $x++){
            if(isset($_POST['amount'.$x]))
                if($_POST['amount'.$x] > 0)
                    $total += $_POST['amount'.$x];
                else 
                    return redirect()->back()->withErrors(['Invalid lucky amount!']);
            if(isset($_POST['winner'.$x])) {
                $is_exist = User::where('id', $_POST['winner'.$x])->where('user_type_id', 3)->count();
                if($is_exist == 0){
                    return redirect()->back()->withErrors([$_POST['winner'.$x].' is not an affiliate id!']);
                } else {
                    if( in_array( $_POST['winner'.$x] , $all_eligibles )) {
                        array_push($all_winners, $_POST['winner'.$x]);
                    } else {
                        return redirect()->back()->withErrors([$_POST['winner'.$x].' is not an eligible affiliate!']);
                    }
                }
            }
                
        }
        $device = SystemBalance::first();
        $total_lucky_balance = $device->aff_lottery_balance;
        if($total > $total_lucky_balance){
            return redirect()->back()->withErrors(['Total reward amount is bigger then deposit.']);
        }

        $latest = LotteryWinners::latest('created_at')->first();
        // if($latest){
        //     if($stringed = strtotime($latest) !== false){
        //         $mnth = date("m", $stringed);
        //         $yr = date("Y", $stringed);
        //         $day = date("d", $stringed);
        //     } else {
        //         return redirect()->back()->withErrors(['Something wrong! try again.']);
        //     }
        //     if($mnth == 1){
        //         $today = date('Y-m-d');
        //     } else if($mnth == 7){

        //     }
        // }
        $flag = 0;
        foreach($_POST as $p){
            if(!empty($p)){
                $is_exist = User::where('id', $p)->where('user_type_id', 3)->count();
                if($is_exist == 0){
                    $flag = 1;
                    break;
                }
            } else {
                continue;
            }
        }
        if(count($all_winners) !== count(array_unique($all_winners)))
            return redirect()->back()->withErrors(['Duplicate users! Check again']);
        if($flag == 0){
            //Distribution
            for($x = 0; $x < 200 ; $x++){
                if(isset($_POST['amount'.$x]) && isset($_POST['winner'.$x])){
                    $user = $_POST['winner'.$x];
                    //Winner entry
                    $lw = new LotteryWinners();
                    $lw->user_id = $user;
                    $lw->amount = $_POST['amount'.$x];
                    $lw->save();
                    //Winner entry ends

                    //Earning table entry
                    $earn = new AffiliatorEarning();
                    $earn->child_affiliator_id = $user;
                    $earn->earning_amount = $_POST['amount'.$x];
                    $earn->source = 'LOTTERY';
                    $earn->save();
                    //Earning table entry ends

                    //Pointwithdrawal table entry
                    $point_calc = UserPointWithdrawal::where('user_id', $user)->first();
                    $point_calc->lottery_bunus = $point_calc->lottery_bunus + $_POST['amount'.$x];
                    $point_calc->last_month_earnings = $point_calc->last_month_earnings + $_POST['amount'.$x];
                    $point_calc->total_amount = $point_calc->total_amount + $_POST['amount'.$x];
                    $point_calc->remaining_amount = $point_calc->remaining_amount + $_POST['amount'.$x];
                    $point_calc->save();
                    //Pointwithdrawal table entry ends
                }     
            }
            //Distribution ends
            $winners_count = count($_POST)-1;
            $total = SystemBalance::select('aff_lottery_balance')->first();
            $amount = ($total->aff_lottery_balance) / $winners_count;
            foreach($_POST as $p){
                if($p == '' || $p == null)
                    continue;
                else 
                    {
                        $lw = new LotteryWinners();
                        $lw->user_id = $p;
                        $lw->amount = $amount;
                        $lw->save();
                    }
            }
            $device = SystemBalance::first();
            $device->update([
                'aff_lottery_balance' => 0
            ]);
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['Invalid users! Check again']);
        }
    }

}
