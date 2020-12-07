<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \DB;
use App\Models\AffiliatorEarning;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\Profile', 'user_id');
    }

    public function creditCards()
    {
        return $this->hasMany('App\Models\CreditCredential', 'user_id');
    }

    public function bankAccounts()
    {
        return $this->hasMany('App\Models\UserBankAccountInfo', 'user_id');
    }


    public function parent()
    {
        if(empty($this->parent_id)) return false;
        return User::find($this->parent_id);
    }

    public function child()
    {
        return $this->hasMany('App\Models\User', 'parent_id');
    }

    public function allChild()
    {
        return $this->child()->with('allChild')->with(['purchaseAmount' => function($query){
            return $query->sum('price');
        }]);
    }

    public function userPointWithdrawal()
    {
        return $this->hasOne('App\Models\UserPointWithdrawal', 'user_id');
    }

    public function purchase()
    {
        return $this->hasMany('App\Models\PurchaseHistory', 'buyer_id');
    }

    public function purchaseAmount()
    {
        //return $this->hasMany('App\Models\PurchaseHistory', 'buyer_id');
        return PurchaseHistory::where('buyer_id', $this->id)->where('created_at', '>=', date('Y-m-d 00:00:01', strtotime('first day of last month')))->where('created_at', '<=', date('Y-m-d 12:59:59', strtotime('last day of previous month')))->sum('price');
    }

    public function purchaseAmountAtMonth($month, $year)
    {
        //return $this->hasMany('App\Models\PurchaseHistory', 'buyer_id');
        return PurchaseHistory::where('buyer_id', $this->id)->where('created_at', '>=', date('Y-m-01 00:00:01', strtotime('01-'.$month.'-'.$year)))->where('created_at', '<=', date('Y-m-t 23:59:59', strtotime('01-'.$month.'-'.$year)))->where('current_status', 'COMPLETE')->sum('price');
    }

    public function lotteryEligible()
    {
        $months = [
            ['january', 'february', 'march', 'april', 'may', 'june'],
            ['july', 'august', 'september', 'october', 'november', 'december']
        ];
        $month = date('m');
        $year = date('Y');
        if($month > 6){
            $months = $months[0];
        }else{
            $months = $months[1];
            $year -= 1;
        }
        foreach($months as $key => $value){
            if($this->purchaseAmountAtMonth($value, $year) <= 980){
                return false; 
                break;
            }
        }
        return true;
    }

    public function childWithMinExpense()
    {
        // return PurchaseHistory::
    }

    public function get_list()
    {
        $month = date('m');
        $year = date('Y');
        $eligibles = AffiliatorEarning::select(DB::raw('SUM(earning_amount) AS spnd'), 'child_affiliator_id')
                                    ->where('created_at', '>=', date('Y-m-d 00:00:01', strtotime('first day of last month')))
                                    ->where('created_at', '<=', date('Y-m-d 12:59:59', strtotime('last day of previous month')))
                                    ->groupBy('child_affiliator_id')
                                    ->orderBy('spnd', 'DESC')
                                    ->limit(5)
                                    ->get();
        return $eligibles;
    }
}
