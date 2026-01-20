<?php

namespace App\Services;

use App\Models\DiscountOffer;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OffersNotification;
use Illuminate\Support\Facades\Notification;

class DiscountOfferService
{
    public function storeDiscountOffer($data)
    {

        $productIds = $data['products'] ?? [];

        // Send notification to all users
        $users = User::all();
        Notification::send($users, new OffersNotification($data));

        foreach ($productIds as $productId) {

            $product = Product::find($productId);
            if ($product->price <= $data['value'] && $data['type'] === 'fixed') {
                continue;
            }

            DiscountOffer::updateOrCreate(
                [
                    'discountable_type' => Product::class,
                    'discountable_id' => $productId,
                ],
                [
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'value' => $data['value'],
                    'starts_at' => $data['starts_date'] ?? null,
                    'ends_at' => $data['ends_date'] ?? null,
                    'is_active' => ($data['status'] ?? 'active') === 'active',
                ]
            );
        }

        return true;

    }

    public function showCreateDiscountForm($search = null)
    {
        $productList = Product::with('discountOffers')->
        // whereDoesntHave('discountOffers', function ($query) {
        //     $query->where('is_active', true);
        // })

            when($search, function ($query, $search) {
                return $query->where('name', 'like', '%'.$search.'%');
            })->latest()->get();

        return $productList;
    }

    public function getDiscountedProducts($perPage = 5)
    {
        $discountedOffers = DiscountOffer::with('discountable')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $discountedOffers;

    }

    public function toggleDiscountStatus($discount)
    {
        if ($discount->is_active && $discount->ends_at && $discount->ends_at->isPast()) {
            return redirect()
                ->route('discounts')
                ->with('error', 'Cannot activate an expired discount offer.');
        }
        if (! $discount->is_active && $discount->starts_at && $discount->starts_at->isFuture()) {
            return redirect()
                ->route('discounts')
                ->with('error', 'Cannot activate a discount offer that has not started yet.');
        }

        $isAlreadyActive = DiscountOffer::where('discountable_type', Product::class)
            ->where('discountable_id', $discount->discountable_id)
            ->where('id', '!=', $discount->id)
            ->where('is_active', true)
            ->exists();
        if ($isAlreadyActive && ! $discount->is_active) {
            return redirect()
                ->route('discounts')
                ->with('error', 'A discount offer for the selected product is already active.');
        }

        // if ($discount->is_active != true) {
        //     // Send notification to all users
        //     $users = User::all();
        //     Notification::send($users, new OffersNotification([
        //         'name' => $discount->name,
        //         'type' => $discount->type,
        //         'value' => $discount->value,
        //         'starts_date' => $discount->starts_at,
        //         'ends_date' => $discount->ends_at,
        //     ]));
        // }

        $discount->is_active = ! $discount->is_active;
        $discount->save();

        return true;

    }

    public function updateDiscountOffer($data, DiscountOffer $discount)
    {

        $exist = DiscountOffer::where('discountable_type', Product::class)
            ->where('discountable_id', $data['product_id'])
            ->where('id', '!=', $discount->id)
            ->where('is_active', true)
            ->exists();
        if ($exist) {
            return redirect()
                ->back()
                ->with('error', 'A discount offer for the selected product already exists.');
        }
        $discount->update([
            'discountable_type' => Product::class,
            'discountable_id' => $data['product_id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'value' => $data['value'],
            'starts_at' => $data['starts_date'] ?? null,
            'ends_at' => $data['ends_date'] ?? null,
            'is_active' => ($data['status'] ?? 'active') === 'active',
        ]);

        return true;
    }
}
