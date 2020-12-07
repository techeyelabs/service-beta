<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidService extends Model
{
    protected $fillable = [
                        'id',
                        'product_id',
                        'service_name',
                        'price',
                        'created_at',
                        'updated_at',
                        ];

    protected $table = 'additional_paid_services';
    protected $primaryKey = 'id';
}
