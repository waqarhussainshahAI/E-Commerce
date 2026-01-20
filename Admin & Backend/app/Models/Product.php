<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'slug',
        'description',
        'category_id',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);

    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    //  public function images()
    // {
    //     return $this->hasMany(ProductImage::class);
    // }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function discountOffers()
    {
        return $this->morphOne(DiscountOffer::class, 'discountable');
    }

    protected $appends = ['product_discount_value'];

    public function getProductDiscountValueAttribute()
    {
        $discount = $this->discountOffers()
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())->first();

        if (! $discount) {
            return $this->price;
        }

        return $discount->type === 'percentage'
            ? $this->price * (1 - ($discount->value / 100))
            : max(0, $this->price - $discount->value);

    }

    public function getProductAllDiscounts()
    {
        $discount = $this->discountOffers()->first();

        if (! $discount) {
            return $this->price;
        }

        return $discount->type === 'percentage'
            ? $this->price * (1 - ($discount->value / 100))
            : max(0, $this->price - $discount->value);
    }
}
