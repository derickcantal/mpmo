<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'mpmo.users';  
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
        'wallcode',
        'trxaddress',
        'usdtaddress',
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
