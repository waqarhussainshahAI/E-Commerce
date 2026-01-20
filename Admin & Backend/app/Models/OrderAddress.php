<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $fillable = [
        'order_id',
        'full_name',
        'email',
        'street_address',
        'city',
        'province',
        'zip_code',
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->street_address}, {$this->city}, {$this->province}, {$this->zip_code}";
    }
}
