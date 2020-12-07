<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPointWithdrawal extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'affiliate_earning',
        'recruiting_bonus',
        'ranking_bonus',
        'lottery_bunus',
        'total_amount',
        'withdraw_date',
        'last_month_earnings',
        'remaining_amount',
        'created_at',
        'updated_at'
    ];

    protected $table = 'user_point_withdrawal';
    protected $primaryKey = 'id';
}
