<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    static function shippings($id)
    {
        return DB::table('users')
                ->select('shipping_address', 'shipping_street_address', 'shipping_city', 'shipping_state',
                        'shipping_postal_code', 'shipping_country', 'shipping_prefecture', 'shipping_municipility',
                        'shipping_room_num', 'shipping_phone_num')
                ->where('id', $id)
                ->first();
    }
}
