<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartClient;
use App\Services\CartClientService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartClientService;

    public function __construct(CartClientService $cartClientService)
    {
        $this->cartClientService = $cartClientService;
    }

    public function addToCart(CartClient $req)
    {

        $cart = $this->cartClientService->addToCart($req);

        return response()->json([
            'message' => 'Product added to cart successfully.',
            'cart' => $cart,
        ], 201);
    }

    public function getCart(Request $req)
    {

        $cart = $this->cartClientService->getCart($req);

        return response()->json(['cart' => $cart]);

    }

    public function incORdecCart(CartClient $req)
    {

        $cart = $this->cartClientService->incORdecCart($req);

        return response()->json(['cart' => $cart]);

    }

    public function remove(Request $request, $id)
    {
        $this->cartClientService->remove($request, $id);

        return response()->json(['message' => 'Item removed from cart successfully'], 200);
    }
}
