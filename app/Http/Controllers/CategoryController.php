<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
{

    return view('category.index');
}
    public function show($slug)
{
    $category = \App\Models\Category::where('slug', $slug)->firstOrFail();
    $products = \App\Models\Product::where('category_id', $category->id)->latest()->paginate(12);

    return view('category.show', [
        'title' => 'Kategori: ' . $category->name,
        'category' => $category,
        'products' => $products,
    ]);
}

}
