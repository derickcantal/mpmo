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
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'rfid',
        'avatar',
        'username',
        'password',
        'firstname',
        'middlename',
        'lastname',
        'cwid',
        'cwaddress',
        'qrcwaddress',
        'ownercwaddress',
        'ownerqrcwaddress',
        'mpmobal',
        'trxbal', 
        'usdtbal',
        'totalbal',
        'dailyin',
        'availbal',
        'pets',
        'birthdate',
        'email',
        'mobile_primary',
        'mobile_secondary',
        'homeno',
        'rnotes',
        'email_verified_at',
        'accesstype',
        'timerecorded',
        'created_by',
        'updated_by',
        'mod',
        'copied',
        'rfidby',
        'walletstatus',
        'status',
    ];

}
