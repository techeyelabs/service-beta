<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'requested_amount',
        'request_status',
        'created_at',
        'updated_at'
        
    ];
    protected $table = 'withdrawal_requests';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
