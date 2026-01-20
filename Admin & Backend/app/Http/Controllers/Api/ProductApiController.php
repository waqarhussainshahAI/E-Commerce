<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductApiService;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function __construct(private ProductApiService $productApiService) {}

    public function index(Request $request)
    {

        [$products, $pagintationData] = $this->productApiService->index($request);

        return response()->json([
            'products' => $products,
            'pagination' => $pagintationData,
        ]);
    }
}
