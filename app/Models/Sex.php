<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sex extends Model
{
    protected $fillable = [
        'id',
        'description'
    ];

    protected $table = 'sex';
    protected $primaryKey = 'id';
}
