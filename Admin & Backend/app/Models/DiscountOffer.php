<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountOffer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function discountable()
    {
        return $this->morphTo();
    }
}
