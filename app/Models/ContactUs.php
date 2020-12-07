<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'content',
        'created_at',
        'updated_at'
    ];

    protected $table = 'contactus';
    protected $primaryKey = 'id';

   
}
