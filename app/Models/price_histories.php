<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class price_histories extends Model
{
    protected $fillable = [
        'token_metric_id',
        'price',
    ];

    /**
     * Get the token metric that this price history belongs to.
     */
    public function tokenMetric(): BelongsTo
    {
        return $this->belongsTo(TokenMetric::class, 'token_metric_id');
    }
}
