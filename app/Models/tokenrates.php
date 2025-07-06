<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tokenrates extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'mpmo.tokenrates';
    protected $primaryKey = 'trid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'trdescription',
        'mpmototrx', 
        'trxtompmo', 
        'trxtophp', 
        'phptotrx', 
        'timerecorded',
        'created_by',
        'updated_by',
        'mod',
        'copied',
        'status',
    ];
}
