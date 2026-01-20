<?php

namespace App\Http\Controllers;

use App\DTOs\AdminProductDto;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\storeImageRequest;
use App\Http\Requests\updateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getAll(Request $request)
    {

        $result = $this->productService->allProduct($request);
        $products = $result['products'];
        $parents = $result['parents'];

        return view('pages.product', compact('products', 'parents'));
    }

    public function store(CreateProductRequest $req, storeImageRequest $imageReq)
    {
        $dto = AdminProductDto::fromArray($req->validated());

        // return $data;
        $this->productService->createProduct($dto, $imageReq);

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    public function updateProduct(updateProductRequest $req, $id)
    {
        $this->productService->updateProduct($req, $id);

        return redirect()->route('product')->with('success', 'Product updated Successfully');

    }

    public function delete($id)
    {

        $this->productService->deleteProduct($id);

        return redirect()->back()->with('success', 'Product Delete Successfully');

    }
}
