<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use \Carbon\Carbon;

class transactions extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'mpmo.transactions';
    protected $primaryKey = 'txnid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'tokenid',
        'tokenname',
        'txnhash',
        'txnimg',
        'txntype',
        'addresssend',
        'addressreceive',
        'user_id',
        'trx_amount',
        'mpmo_gross',
        'mpmo_fee',
        'mpmo_net',
        'type',
        'meta',
        'cwid',
        'userid',
        'timerecorded',
        'created_by',
        'updated_by',
        'mod',
        'copied',
        'status',
    ];

    protected $casts = [
        'meta'=>'array'
    ];
    
    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }


    public function wallet()
    {
        return $this->belongsTo(cwallet::class, 'cwid', 'cwid');
    }
}
