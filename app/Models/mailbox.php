<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mailbox extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $connection = 'mysqldb2';
    protected $primaryKey = 'username';
    protected $table = 'postfixadmin.mailbox';
    
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'username',
        'password',
        'name',
        'maildir',
        'quota',
        'local_part',
        'domain',
        'created',
        'modified',
        'active',
        'phone',
        'email_other',
        'token',
        'token_validity',
        'password_expiry',
        'totp_secret',
        'smtp_active',
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
        'modi',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}
