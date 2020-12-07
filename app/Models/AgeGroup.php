<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    protected $fillable = [
        'id',
        'age_group'
    ];

    protected $table = 'age_group';
    protected $primaryKey = 'id';
}
