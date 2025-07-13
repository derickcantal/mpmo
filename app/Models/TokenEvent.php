<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenEvent extends Model
{
    protected $fillable = [
        'token_metric_id',
        'user_id',
        'type',
        'amount',
        'fee',
        'meta'
    ];

    protected $casts = [
        'meta'=>'array'
    ];

    public function tokenMetric() { 
        return $this->belongsTo(TokenMetric::class); 
    }
}
