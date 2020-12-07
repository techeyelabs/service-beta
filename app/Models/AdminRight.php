<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminRight extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id', 'service_list', 'total_sale', 'withdraw_request', 'buyers', 'sellers', 'affiliates', 'lottery', 'banners', 'news', 'direct_message', 'contact_us', 'add_admin', 'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];
    
    protected $table = 'admin_rights';
    protected $primaryKey = 'id';
}
