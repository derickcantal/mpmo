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
        'amount',
        'amountvalue',
        'amountfee',
        'userid',
        'cwid',
        'timerecorded',
        'created_by',
        'updated_by',
        'mod',
        'copied',
        'status',
    ];

    public function wallet()
    {
        return $this->belongsTo(cwallet::class, 'cwid', 'cwid');
    }
}
