<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatorApplication extends Model
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'app_body',
        'created_at',
        'updated_at'
    ];

    protected $table = 'affiliator_application';
    protected $primaryKey = 'id';
}
