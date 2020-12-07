<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryWinners extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'amount',
        'created_at',
        'updated_at'
    ];

    protected $table = 'lottery_winners';
    protected $primaryKey = 'id';
}
