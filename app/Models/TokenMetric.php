<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenMetric extends Model
{
    protected $fillable = [
        'symbol',
        'max_supply',
        'total_supply',
        'burned_supply',
        'locked_supply',
        'treasury_balance'
    ];
    public function events() { 
        return $this->hasMany(TokenEvent::class); 
    }
}
