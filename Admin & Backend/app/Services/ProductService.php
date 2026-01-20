<?php

namespace App\Services;

use App\DTOs\AdminProductDto;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Spatie\QueryBuilder\QueryBuilder;

class ProductService
{
    public function allProduct($request)
    {
        $search = $request->search;
        $parents = Category::all();

        $query = QueryBuilder::for(Product::class)
            ->allowedSorts(['name', 'price', 'stock'])
            ->with(['category', 'primaryImage', 'images', 'discountOffers'])
            ->withCount(['orderItems as delivered_orders_count' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', 'DELIVERED');
                });
            }]);

        $query->when($search, function ($query) use ($search) {
            $query->where('products.name', 'LIKE', "%{$search}%")
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('categories.name', 'LIKE', "%{$search}%");
                });
        });

        $products = $query->paginate(5);

        return ['parents' => $parents, 'products' => $products];

    }

    public function createProduct(AdminProductDto $dto, $imageReq)
    {

        $product = Product::create($dto->toArray());
        if ($imageReq->hasFile('images')) {

            foreach ($imageReq->file('images') as $index => $image) {

                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return true;

    }

    public function updateProduct($req, $id)
    {

        $product = Product::findOrFail($id);

        if ($req->removed_images) {
            $removeIds = json_decode($req->removed_images, true);
            foreach ($removeIds as $id) {

                $image = ProductImage::find($id);
                if ($image) {
                    Storage::disk('public')->delete($image->image);
                    $image->delete();
                }
            }

            $remaining = $product->images()->count();
            if ($remaining === 1) {
                $image = $product->images()->first();
                $image->update([
                    'is_primary' => true,
                ]);
            }

        }
        // dd($req->file('new_images'));

        if ($req->hasFile('new_images')) {
            $isPrimary = false;
            if (! $product->images()->exists()) {
                $isPrimary = true;
            }
            foreach ($req->file('new_images') as $img) {
                $path = $img->store('products', 'public');
                $product->images()->create([
                    'image' => $path,
                    'is_primary' => $isPrimary,
                ]);
                $isPrimary = false;
            }
        }

        $product->update($req->all());

        return true;
    }

    public function deleteProduct($id)
    {
        $product = Product::with('images')->findOrFail($id);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }
        $product->delete();

        return true;
    }
}
