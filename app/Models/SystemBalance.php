<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemBalance extends Model
{
    protected $fillable = [
        'id',
        'balance',
        'tax',
        'aff_bonus_balance',
        'aff_lottery_balance',
        'created_at',
        'updated_at'
    ];

    protected $table = 'system_balance';
    protected $primaryKey = 'id';
}
