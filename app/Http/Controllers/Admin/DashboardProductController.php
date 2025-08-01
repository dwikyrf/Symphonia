<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class DashboardProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Jika ada pencarian, filter berdasarkan title atau body
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->paginate(20);
        $categories = Category::all();

        return view('dashboard.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // Ambil kategori dari database
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {

        // **Validasi Input**
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price'       => 'required|numeric|min:0',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // **Slug Unik**
        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $validatedData['slug'] = $slug;

        // **Proses Upload Gambar**
        if ($request->hasFile('image')) {
            \Log::info('📸 Gambar ditemukan, mulai upload...');
            
            $file = $request->file('image');
            $imageName = time() . '-' . $file->getClientOriginalName();
            
            // Buat direktori jika belum ada
            $directory = public_path("img/product/{$slug}/");
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Simpan ke folder img/product/{slug}
            $file->move($directory, $imageName);
            
            $validatedData['image'] = "img/product/{$slug}/" . $imageName; // Simpan path dalam database
            \Log::info('📁 Gambar disimpan sebagai: ' . $validatedData['image']);
        }

        // **Simpan Produk ke Database**
        Product::create([
            'name'       => $validatedData['name'],
            'slug'        => $validatedData['slug'],
            'category_id' => $validatedData['category_id'],
            'description' => $validatedData['description'],
            'price'       => $validatedData['price'],
            'image'      => $validatedData['image'] ?? null
        ]);

        \Log::info('🚀 Produk berhasil disimpan!');

        return redirect()->route('dashboard.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        return view('dashboard.products.show', [
            'product' =>  $product->slug
        ]);
    }


    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {


        // **Validasi Data**
        $validatedData = $request->validate([
            'name'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        \Log::info('✅ Data yang akan diupdate:', $validatedData);

        // **Slug tetap sesuai dengan produk sebelumnya**
        $validatedData['slug'] = Str::slug($validatedData['name']);


        // **Cek apakah ada gambar baru**
        if ($request->hasFile('image')) {
            \Log::info('📸 Gambar baru ditemukan');

            // Hapus gambar lama jika ada
            if ($product->image) {
                $oldImagePath = public_path('img/' . $product->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Buat direktori jika belum ada
            $directory = public_path("img/product/{$product->slug}/");
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Simpan gambar baru di folder img/product/{slug}
            $file = $request->file('image');
            $imageName = time() . '-' . $file->getClientOriginalName();
            $file->move($directory, $imageName);

            // Simpan path dalam database
            $validatedData['image'] = "img/product/{$product->slug}/" . $imageName;
        } else {
            \Log::info('⚠️ Tidak ada gambar baru yang dikirim');
        }

        \Log::info('🚀 Data yang final untuk update:', $validatedData);

        // **Update data produk**
        $product->update($validatedData);

        return redirect()->route('dashboard.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Pastikan hanya pemilik produk atau admin yang dapat menghapus

        // Hapus gambar dari storage jika ada
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
