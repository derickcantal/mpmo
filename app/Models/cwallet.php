<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use \Carbon\Carbon;

class cwallet extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'mpmo.cwallets';
    protected $primaryKey = 'cwid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'cwaddress',
        'qrcwaddress',
        'wallcode',
        'qrwallcode',
        'private_key', 
        'public_key',
        'timerecorded',
        'created_by',
        'updated_by',
        'mod',
        'copied',
        'walletstatus',
        'userid',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }

    public function transactions()
    {
        return $this->hasMany(transactions::class, 'cwid', 'cwid');
    }
}
