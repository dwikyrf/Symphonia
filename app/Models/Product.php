<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'description', 'slug', 'price', 'image'];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {

        $query->when(
            $filters['search'] ?? false, 
            fn($query, $search) =>
                $query->where('name', 'like', '%' . $search . '%')
        );

        $query->when(
            $filters['category'] ?? false, 
            fn($query, $category) =>
                $query->whereHas('category', fn($query) => 
                    $query->where('slug', $category)
                )
        );
    }

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
