<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Menampilkan semua produk (publik)
    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::latest()
            ->filter(request(['search', 'category']))
            ->paginate(12); // ✅ PAGINATE!

        return view('product.index', compact('products', 'categories'));
    }


    // Menampilkan form tambah produk (admin)
    public function create()
    {
        $categories = Category::all();
        return view('dashboard.products.create', compact('categories'));
    }

    // Simpan produk baru (admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('img');
        }

        Product::create($validated);

        return redirect()->route('dashboard.products.index')->with('success', 'Product added successfully.');
    }

    // Menampilkan produk tunggal (admin)
    public function show(Product $product)
    {
        return view('dashboard.products.show', compact('product'));
    }

    // Form edit produk (admin)
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    // Update produk (admin)
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('gambar')) {
            if ($product->image && Storage::exists($product->image)) {
                Storage::delete($product->image);
            }
            $validated['image'] = $request->file('')->store('img');
        }

        $product->update($validated);

        return redirect()->route('dashboard.products.index')->with('success', 'Product updated successfully.');
    }

    // Hapus produk (admin)
    public function destroy(Product $product)
    {
        if ($product->image && Storage::exists($product->image)) {
            Storage::delete($product->image);
        }

        $product->delete();

        return redirect()->route('dashboard.products.index')->with('success', 'Product deleted successfully.');
    }
}
