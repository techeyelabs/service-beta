<?php

namespace App\Libraries;
use App\Models\User;
use App\Models\UserPointWithdrawal;
use App\Models\SystemBalance;
use App\Models\AffiliatorEarning;
use App\Models\Notification;
use App\Models\BonusManager;


class Affiliator
{

    public function __construct()
    {
        $this->rankAUsers = [];
        $this->rankBUsers = [];
        $this->rankCUsers = [];
    }
    public function commission($price, $aff_id, $percentage, $product)
    {
        $root_selling_aff = $aff_id;
        $system_balance = $price*20/100;
        $system_income = $system_balance * 60/100; //For balance column
        $tax = $system_balance * 40/100; //For tax column
        $excess_balance = 0;
        $remaining_balance = $price - $system_balance;
        $remaining_balance *= $percentage/100;
        $aff_commission = $remaining_balance * 50/100; //Direct affiliator commission
        $aff_bonus = $aff_commission * (70/100); //reanking bonus
        $recruiting_bonus = $aff_commission * (20/100); //Recruiting bonus
        $lottery = $aff_commission * (10/100); //Lottery

        $commission_percentage = [
            60, 14, 10, 4, 4, 2, 2, 2, 2
        ];
        $excess = $aff_commission;
        foreach($commission_percentage as $per){
            $commission_amount = $aff_commission * $per/100;
            $user = User::find($aff_id);
            if($user){
                $this->store($user->id, $commission_amount);
                $this->store_aff_earning($user->id, $user->parent_id, $product, $price, $commission_amount, $per, $root_selling_aff);
                $excess -= $commission_amount;
                $aff_id = $user->parent_id;
            }else{
                break;
            }
        }
        $system = SystemBalance::first();
        $system->balance += $system_income;
        $system->excess_parent_bonus += $excess;
        $system->tax += $tax;
        $system->aff_bonus_balance += $aff_bonus;
        $system->recruiting_bonus_balance += $recruiting_bonus;
        $system->aff_lottery_balance += $lottery;
        $system->save();

        return;
    }

    public function store($user, $amount)
    {
        $UserPointWithdrawal = UserPointWithdrawal::where('user_id', $user)->first();
        $UserPointWithdrawal->remaining_amount += $amount;
        $UserPointWithdrawal->last_month_earnings += $amount;
        $UserPointWithdrawal->total_amount += $amount;
        $UserPointWithdrawal->save();

        //Get buyer name
        $buyer = User::select('first_name')->where('id', $user)->first();
        //Notification generation
        $flw_not = new Notification();
        $flw_not->user_id = $user;
        $flw_not->seller_id = 0;
        $flw_not->buyer_id = 0;
        $flw_not->notification_text = 'アフィリエイト報酬はいりました' ;
        $flw_not->opening_status = 'UNOPENED';
        $flw_not->notification_type = 'COMMISSIONRECEIVED';
        $flw_not->save();
        //Notification ends    
        
        return;
    }

    public function store_aff_earning($child, $parent, $product, $price, $amount, $percent, $root)
    {
        $aff_earning = new AffiliatorEarning();
        $aff_earning->child_affiliator_id = $child;
        $aff_earning->root_selling_aff = $root;
        $aff_earning->parent_affiliator_id = $parent;
        $aff_earning->product_id = $product;
        $aff_earning->price = $price;
        $aff_earning->earning_amount = $amount;
        $aff_earning->percent = $percent;
        $aff_earning->save();

        return;
    }

    //Recruiting bonus starts
    public function recruiting_bonus()
    {
        $balance = SystemBalance::first()->recruiting_bonus_balance;
        if(empty($balance) || $balance == 0) 
            return;
        //Primary division
        $for_rank_A = $balance * (((100/70)*40)/100);
        $for_rank_B = $balance * (((100/70)*20)/100);
        $for_rank_C = $balance * (((100/70)*10)/100);
        $User = User::where('status', 'ACTIVE')->where('user_type_id', 3)->get();
        foreach($User as $u){
            $count = $this->rankCheck($u);
            if($count >= 5 && $count <= 49){
                $this->rankCUsers[] = $u;
            }elseif($count >= 50 && $count <= 99){
                $this->rankBUsers[] = $u;
            }elseif($count >= 100){
                $this->rankAUsers[] = $u;
            }
        }
        $each_rank_A = $for_rank_A / (count($this->rankAUsers));
        $each_rank_B = $for_rank_B / (count($this->rankBUsers));
        $each_rank_C = $for_rank_C / (count($this->rankAUsers));
        //Deposite amount
        foreach($this->rankAUsers as $a){
            $this->deposite_recruting_bonus($a->id, $each_rank_A);
            $this->store_aff_earning($a->id, 0, 0, 0, $each_rank_A , 0, 0);
        }
        foreach($this->rankBUsers as $b){
            $this->deposite_recruting_bonus($b->id, $each_rank_B);
            $this->store_aff_earning($b->id, 0, 0, 0, $each_rank_B , 0, 0);
        }
        foreach($this->rankCUsers as $c){
            $this->deposite_recruting_bonus($c->id, $each_rank_C);
            $this->store_aff_earning($c->id, 0, 0, 0, $each_rank_C , 0, 0);
        }
        //Deposite ends


        $sys_update = SystemBalance::first();
        $sys_update->recruiting_bonus_balance = 0;
        $sys_update->save();
        return;
    }

    public function rankCheck($User, $count = 0)
    {
        foreach($User->child as $c){
            if($c->purchaseAmount() >= 980){
                $count ++;
            }
            // $count = $this->rankCheck($c, $count);
        }
        return $count;
    }

    public function deposite_recruting_bonus($user, $amount)
    {
        $UserPointWithdrawal = UserPointWithdrawal::where('user_id', $user)->first();
        $UserPointWithdrawal->remaining_amount += $amount;
        $UserPointWithdrawal->last_month_earnings += $amount;
        $UserPointWithdrawal->total_amount += $amount;
        $UserPointWithdrawal->recruiting_bonus += $amount;
        $UserPointWithdrawal->save();

        //Insert into bonus manager
        $month = date('m');
        $year = date('Y');
        $bonusmanager = new BonusManager();
        $bonusmanager->affiliator_id = $user;
        $bonusmanager->recruitment_bonus = $amount;
        $bonusmanager->month = $month;
        $bonusmanager->year = $year;
        $bonusmanager->save();
        //Bonus insertion ends

        //Get affs name
        $aff = User::select('first_name')->where('id', $user)->first();
        //Notification generation
        $flw_not = new Notification();
        $flw_not->user_id = $user;
        $flw_not->seller_id = 0;
        $flw_not->buyer_id = 0;
        $flw_not->notification_text = 'リクルーティングボーナス入りました' ;
        $flw_not->opening_status = 'UNOPENED';
        $flw_not->notification_type = 'RECRUITINGBONUSRECEIVED';
        $flw_not->save();
        //Notification ends  

        return;
    }
    //Recruiting bonus ends

    //Ranking bonus section
    public function rankingBonus()
    {
        $balance = SystemBalance::first()->aff_bonus_balance;
        if(empty($balance)) 
            return;
        //Primary division
        $rewards = [];
        $rewards[] = $balance * (50/100);
        $rewards[] = $balance * (30/100);
        $rewards[] = $balance * (10/100);
        $rewards[] = $balance * (7/100);
        $rewards[] = $balance * (3/100);
        // $rewards[] = $balance * (2/100);

        //Get list
        $us = new User();
        $rewarded_affs = $us->get_list();
        for($r = 0; $r < count($rewarded_affs); $r++){
            if($r != 4){
                if($rewarded_affs[$r] == $rewarded_affs[$r+1]){
                    $c1 = $this->rankCheck($rewarded_affs[$r]->child_affiliator_id);
                    $c2 = $this->rankCheck($rewarded_affs[$r+1]->child_affiliator_id);
                    if($c2 < $c1 || $c1 == $c2){
                        continue;
                    } else if($c2 > $c1) {
                        $temp[] = $rewarded_affs[$r];
                        $rewarded_affs[$r] = $rewarded_affs[$r+1];
                        $rewarded_affs[$r+1] = $temp;
                    }
                }
            }
        }
        for($x = 0; $x < count($rewarded_affs); $x++){
            $this->store_ranking_bonus($rewarded_affs[$x]->child_affiliator_id, $rewards[$x]);
            $this->store_aff_earning($rewarded_affs[$x]->child_affiliator_id, 0, 0, 0, $rewards[$x] , 0, 0);
        }
        $sys_update = SystemBalance::first();
        $sys_update->aff_bonus_balance = 0;
        $sys_update->save();
        return;
    }

    public function store_ranking_bonus($user, $amount)
    {
        $UserPointWithdrawal = UserPointWithdrawal::where('user_id', $user)->first();
        $UserPointWithdrawal->remaining_amount += $amount;
        $UserPointWithdrawal->last_month_earnings += $amount;
        $UserPointWithdrawal->total_amount += $amount;
        $UserPointWithdrawal->ranking_bonus += $amount;
        $UserPointWithdrawal->save();

        //Insert into bonus manager
        $month = date('m');
        $year = date('Y');
        $bonusmanager = new BonusManager();
        $bonusmanager->affiliator_id = $user;
        $bonusmanager->ranking_bonus = $amount;
        $bonusmanager->month = $month;
        $bonusmanager->year = $year;
        $bonusmanager->save();
        //Bonus insertion ends

        //Get affs name
        $aff = User::select('first_name')->where('id', $user)->first();
        //Notification generation
        $flw_not = new Notification();
        $flw_not->user_id = $user;
        $flw_not->seller_id = 0;
        $flw_not->buyer_id = 0;
        $flw_not->notification_text = 'ランキングボーナス入りました' ;
        $flw_not->opening_status = 'UNOPENED';
        $flw_not->notification_type = 'RANKINGBONUSRECEIVED';
        $flw_not->save();
        //Notification ends  

        return;
    }
    //Ranking bonus section ends

}


