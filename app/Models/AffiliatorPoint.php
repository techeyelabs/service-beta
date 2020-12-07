<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatorPoint extends Model
{
    protected $fillable = [
        'id',
        'point_value'
    ];

    protected $table = 'affiliator_points';
    protected $primaryKey = 'id';
}
