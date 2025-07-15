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

    /** all events (conversion, burn, buyback…) */
    public function events() { 
        return $this->hasMany(TokenEvent::class, 'token_metric_id');
    }

    // so we can do $metric->circulating_supply, etc.
    protected $appends = [
      'circulating_supply',
      'remaining_supply',
      'burned_amount',
    ];


    /** tokens in circulation = total minted net of treasury holdings */
    public function getCirculatingSupplyAttribute(): int
    {
        return $this->total_supply - $this->treasury_balance;
    }

    /** how many tokens left before hitting max_supply */
    public function getRemainingSupplyAttribute(): int
    {
        return $this->max_supply - $this->total_supply;
    }

    /** total tokens that have been burned */
    public function getBurnedAmountAttribute(): int
    {
        return $this->events()
                    ->where('type', 'burn')
                    ->sum('amount');
    }

    public function priceHistories()
    {
        return $this->hasMany(price_histories::class, 'token_metric_id')->orderBy('created_at');
    }
    
}
