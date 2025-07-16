<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class temp_users extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'mpmo.temp_users';  
    protected $primaryKey = 'userid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    public function getEmailForVerification(): string
    {
        return $this->email;
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'avatar',
        'referral_code',
        'fullname',
        'cwid',
        'trx_balance',
        'mpmo_balance',
        'birthdate',
        'email',
        'mobile_primary',
        'mobile_secondary',
        'homeno',
        'notes',
        'email_verified_at',
        'password',
        'accesstype',
        'role',
        'timerecorded',
        'created_by',
        'updated_by',
        'mod',
        'copied',
        'referred_by',
        'status',
    ];

}
