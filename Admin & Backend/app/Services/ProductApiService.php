<?php

namespace App\Services;

use App\Models\Product;

class ProductApiService
{
    public static function index($request)
    {
        $page = $request->query('page', 2);
        $sortBy = $request->query('sortBy', null);
        $order = $request->query('sortOrder', 'asc');
        $searchTerm = $request->query('searchTerm', null);
        $perPage = $request->query('perPage', 1);
        $categoryId = $request->query('categoryId', null);

        $products = Product::with(['category', 'images', 'discountOffers' => function ($query) {
            $query->where('is_active', true)->limit(1);
        }])
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })

            ->orderBy(
                $sortBy ?? 'id',
                $order
            )

            ->when($searchTerm, function ($query, $searchTerm) {
                $query
                    ->where('name', 'like', "%$searchTerm%")
                    ->orwhereHas('category', function ($q) use ($searchTerm) {
                        $q->where('categories.name', 'like', "%$searchTerm%");
                    });

            })

            ->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->get();

        $products->each(function ($product) {
            $product->images->transform(function ($image) {
                $image->image = url('storage/'.$image->image);

                return $image;
            });
        });

        $pagintationData = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => Product::count(),
            'last_page' => ceil(Product::count() / $perPage),
        ];

        return [$products, $pagintationData];
    }
}
