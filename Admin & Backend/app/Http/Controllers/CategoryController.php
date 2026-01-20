<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreCategory;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;
    
    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }

    public function createForm()
    {
        $parents = Category::getAll();

        return view('pages.category.create', compact('parents'));
    }

    public function addCategory(StoreCategory $req)
    {
       
        Category::create($req->all());

        return redirect()->route('category')->with('success', 'Category created successfully!');

    }

    public function updateCategory(UpdateCategoryRequest $req, $id)
    {
       return $this->categoryService->updateCategory($req,$id);

    }


    public function allCategory(Request $request)
    {
        return $this->categoryService->getAll($request);

    }

    public function delete($id)
    {
        return $this->categoryService->delete($id);

    }
}
