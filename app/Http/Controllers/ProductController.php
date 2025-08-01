<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Menampilkan semua produk (publik)
    public function index(Request $request)
    {
        $products = Product::latest()
            ->filter($request->only(['search', 'category']))
            ->paginate(20)
            ->withQueryString();   // agar link pagination tetap menyertakan keyword

        return view('product.index', [
            'title'    => 'Semua Produk',
            'products' => $products,
        ]);
    }

public function show($slug)
{
    $product = Product::where('slug', $slug)->with('category')->firstOrFail();
    return view('product.show', [
        'title' => $product->name,
        'product' => $product
    ]);
}

}
