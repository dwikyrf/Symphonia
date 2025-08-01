<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'title',       // ⬅️ ini wajib sesuai dengan migration
        'content',     // ⬅️ ganti dari 'comment' ke 'content'
        'images',      // ⬅️ jika kamu menyimpan sebagai json
        'verified'
    ];

    protected $casts = [
        'images' => 'array',     // ⬅️ otomatis diconvert dari JSON ke array
        'verified' => 'boolean'
    ];

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
