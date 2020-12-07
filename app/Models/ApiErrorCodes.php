<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiErrorCodes extends Model
{
    protected $fillable = [
         
        'error_code',
        'error_message'
];

protected $table = 'api_error_codes';
protected $primaryKey = 'error_code';
}
