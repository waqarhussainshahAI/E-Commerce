<?php

namespace App\Services;
use App\Models\Category;

class CategoryService
{
    public function getAll($request)
    {

        $parents = Category::whereNull('parent_id')->get();
        $search = $request->search;

        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
        })
            ->paginate(5)
            ->withQueryString();

        return view('pages.category.index', compact('categories', 'parents'));
    }

    public function updateCategory($req,$id){

        $category = Category::findOrFail($id);
        $category->update($req->validated());
        return redirect()->route('category')->with('success', 'Category updated Successfully');

    }
    public function delete($id){
        $category = Category::findorFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category Delete Successfully');
    }
}
