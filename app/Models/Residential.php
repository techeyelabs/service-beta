<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Residential extends Model
{
    protected $fillable = [
        'id',
        'area'
    ];

    protected $table = 'residential_area';
    protected $primaryKey = 'id';
}
