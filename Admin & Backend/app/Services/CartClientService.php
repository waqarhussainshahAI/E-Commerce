<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Gate;

class CartClientService
{
    public function addToCart($req)
    {
        $userId = $req->user()->id;

        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $req->product_id)
            ->first();
        if ($cart) {
            $cart->quantity += $req->quantity;
            $cart->save();

            return $cart;
        }

        $cart = Cart::create([
            'product_id' => $req->product_id,
            'quantity' => $req->quantity,
            'user_id' => $userId,
        ]);

        return $cart;
    }

    public function getCart($req)
    {
        $cart = Cart::where('user_id', $req->user()->id)->with('product.primaryImage', 'product.category')->get();

        foreach ($cart as $c) {
            if ($c->product && $c->product->primaryImage) {
                $c->product->primaryImage->image = url('storage/'.$c->product->primaryImage->image);
            }
        }

        return $cart;
    }

    public function incORdecCart($req)
    {
        $cart = Cart::where('product_id', $req->product_id)
            ->firstOrFail();

        if (! $cart) {
            return response()->json(['message' => 'Cart item not found'], 400);

        }

        if (! $req->user()->can('update', $cart)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cart->increment('quantity', $req->quantity);
        if ($cart->quantity <= 0) {
            $cart->delete();
        }

        return $cart;
    }

    public function remove($request, $id)
    {
        $cart = Cart::where('product_id', $id)
            ->first();
        if (! $cart) {
            return response()->json(['message' => 'Cart item not found'], 400);

        }

        if (Gate::denies('removeCart', $cart)) {

            return response()->json(['message' => 'Unauthorized'], 403);

        }

        $cart->delete();

    }
}
