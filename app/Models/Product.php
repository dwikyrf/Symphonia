<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;


    // Menambahkan 'gambar' ke dalam fillable agar bisa diisi melalui mass assignment
    protected $fillable = ['name', 'category_id', 'description', 'slug', 'price', 'image'];

    // Relasi ke kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Filter produk berdasarkan pencarian dan kategori
    public function scopeFilter(Builder $query, array $filters): void
    {
        // Filter berdasarkan search
        $query->when(
            $filters['search'] ?? false, 
            fn($query, $search) =>
                $query->where('name', 'like', '%' . $search . '%')
        );

        // Filter berdasarkan kategori
        $query->when(
            $filters['category'] ?? false, 
            fn($query, $category) =>
                $query->whereHas('category', fn($query) => 
                    $query->where('slug', $category)
                )
        );
    }

    // Menggunakan slug sebagai route key
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }


}
