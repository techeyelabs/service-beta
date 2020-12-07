<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\User;
use App\Models\BonusManager;
use App\Models\RequestBoard;
use DB;
use Illuminate\Http\Request;

class AffiliatorBonusController extends Controller
{
    public function get_rbonus_aff($user_id=null)
    {
        $aff = $user_id;
        if($aff=='' || $aff==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message 
            ];
        } else {
            $rec_bonus = BonusManager::where('affiliator_id', $aff)->where('recruitment_bonus', '!=', 0)->orderBy('id', 'DESC')->get();
            if($rec_bonus=='' || $rec_bonus==null){
                $total = 0;
            } else {
                $total = BonusManager::where('affiliator_id',$aff)
                        ->groupBy('affiliator_id')
                        ->sum('recruitment_bonus');
            }
            $data = [
                'status'  => 200,
                'payload' => $rec_bonus,
                'total_bonus' => $total 
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
    public function get_rankbonus_aff($user_id=null)
    {
        $aff = $user_id;
        if($aff=='' || $aff==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message 
            ];
        } else {
            $rank_bonus = BonusManager::where('affiliator_id', $aff)->where('ranking_bonus', '!=', 0)->get();
            if($rank_bonus=='' || $rank_bonus==null){
                $total = 0;
            } else {
                $total = BonusManager::where('affiliator_id',$aff)
                        ->groupBy('affiliator_id')
                        ->sum('ranking_bonus');
            }
            $data = [
                'status'  => 200,
                'payload' => $rank_bonus,
                'total_bonus' => $total 
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
    public function get_aff($user_id=null)
    {
        $aff = $user_id;
        if($aff=='' || $aff==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message 
            ];
        } else {
            $lottery_bonus = BonusManager::where('affiliator_id', $aff)->where('lottery_bonus', '!=', 0)->get();
            if($rank_bonus=='' || $rank_bonus==null){
                $total = 0;
            } else {
                $total = BonusManager::where('affiliator_id',$aff)
                        ->groupBy('affiliator_id')
                        ->sum('lottery_bonus');
            }
            $data = [
                'status'  => 200,
                'payload' => $lottery_bonus,
                'total_bonus' => $total 
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function for_dashboard($id=null)
    {
        $aff = $id;
        $rec_bonus = BonusManager::where('affiliator_id',$aff)
                    ->groupBy('affiliator_id')
                    ->sum('recruitment_bonus');
        $rank_bonus = BonusManager::where('affiliator_id',$aff)
                    ->groupBy('affiliator_id')
                    ->sum('ranking_bonus');
        $lottery_bonus = BonusManager::where('affiliator_id',$aff)
                    ->groupBy('affiliator_id')
                    ->sum('lottery_bonus');
        $data = [
            'status' => 200,
            'rec_bonus' => $rec_bonus,
            'rank_bonus' => $rank_bonus,
            'lottery_bonus' => $lottery_bonus,
            'commission' => 100
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function search_bonus_ranking(Request $request)
    {
        $aff = $request->user;
        $filtered_bonus = BonusManager::where('affiliator_id', $aff)
                                        ->where('ranking_bonus', '!=', 0)
                                        ->when($request->month, function($query) use ($request){
                                            return $query->where('month', $request->month);
                                        })
                                        ->when($request->year, function($query) use ($request){
                                            return $query->where('year', $request->year);
                                        })
                                        ->get();
        $data = [
            'status' => 200,
            'rec_bonus' => $filtered_bonus,
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function search_bonus_recruit(Request $request)
    {
        $aff = $request->user;
        $filtered_bonus = BonusManager::where('affiliator_id', $aff)
                                        ->where('recruitment_bonus', '!=', 0)
                                        ->when($request->month, function($query) use ($request){
                                            return $query->where('month', $request->month);
                                        })
                                        ->when($request->year, function($query) use ($request){
                                            return $query->where('year', $request->year);
                                        })
                                        ->get();
        $data = [
            'status' => 200,
            'rec_bonus' => $filtered_bonus,
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_eli_stat($id)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $new = User::where('parent_id', $id)
                    ->where('user_type_id', 3)
                    ->whereRaw('MONTH(created_at) = ?',[$currentMonth])
                    ->whereRaw('YEAR(created_at) = ?',[$currentYear])
                    ->count();
        $active = DB::table('users')
                        ->select('rb.buyer_id', DB::raw('SUM(rb.proposed_budget) AS expense'), 'users.id', 'users.first_name')
                        ->leftjoin('request_board as rb', 'rb.buyer_id', '=', 'users.id')
                        ->where('users.parent_id', $id)
                        ->where('rb.current_status', 'COMPLETE')
                        ->whereRaw('MONTH(rb.created_at) = ?',[$currentMonth])
                        ->whereRaw('YEAR(rb.created_at) = ?',[$currentYear])
                        ->groupBy('rb.accepted_seller_id')
                        ->get();

        $total_actives = 0;
        $current= '';
        // foreach($active as $act){
        //     if($act->expense >= 980){
        //         $total_actives++;
        //     }
        // }
        $total_actives = $active->where('expense', '>=', 980)->count();
        if($new >= 100 && $total_actives >= 100){
            $current = 'A';
        } elseif ($new >= 50 && $total_actives >= 50){
            $current = 'B';
        } elseif ($new >= 5 && $total_actives >= 5){
            $current = 'C';
        } else {
            $current = 'なし';
        }
        $month = date('m');
        $year = date('Y');
        if($month <= 6){
            $session_start = 1;
            $session_ends = 6;
        } else {
            $session_start = 7;
            $session_ends = 12;
        }
        $flag = 1;
        for($i=$session_start; $i <= $session_ends; $i++){
            $expend = RequestBoard::where('buyer_id', $id)
                                    ->whereRaw('MONTH(created_at) = ?',[$i])
                                    ->whereRaw('YEAR(created_at) = ?', [$year])
                                    ->sum('proposed_budget');

            if($expend < 980){
                $flag = 0;
            }
        }
        $data = [
            'status' => 200,
            'newuser' => $new,
            'activeusers' => $total_actives,
            'current_status' => $current,
            'lucky_eli' => $flag
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_new_child($id)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $new = User::select('first_name', 'created_at', 'id')
                    ->where('parent_id', $id)
                    ->where('user_type_id', 3)
                    ->whereRaw('MONTH(created_at) = ?',[$currentMonth])
                    ->whereRaw('YEAR(created_at) = ?',[$currentYear])
                    ->orderBy('created_at', 'DESC')
                    ->get();
        $data = [
            'list' => $new
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
    public function get_new_active_child($id)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $children = DB::table('users')
                        ->select('rb.buyer_id', DB::raw('SUM(rb.proposed_budget) AS expense'), 'users.id', 'users.first_name')
                        ->leftjoin('request_board as rb', 'rb.buyer_id', '=', 'users.id')
                        ->where('users.parent_id', $id)
                        ->where('rb.current_status', 'COMPLETE')
                        ->whereRaw('MONTH(rb.created_at) = ?',[$currentMonth])
                        ->whereRaw('YEAR(rb.created_at) = ?',[$currentYear])
                        ->groupBy('rb.accepted_seller_id')
                        ->get();
        $active = $children->where('expense', '>=', 980);
        $data = [
            'list' => $active
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
}
