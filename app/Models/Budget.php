<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'id',
        'budget_range',
        'created_at',
        'updated_at'
    ];

    protected $table = 'budget';
    protected $primaryKey = 'id';
}
