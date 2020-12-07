<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusManager extends Model
{
    protected $fillable = [
        'id',
        'affiliator_id',
        'recruitment_bonus',
        'ranking_bonus',
        'lottery_bonus',
        'month',
        'year',
        'created_at',
        'updated_at'
    ];

    protected $table = 'bonus_manager';
    protected $primaryKey = 'id';
}
