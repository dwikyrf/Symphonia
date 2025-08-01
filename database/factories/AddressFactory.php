<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'address',
        'province',
        'province_code',
        'city',
        'city_code',
        'district',
        'district_code',
        'village',          // Jika Anda akan menambahkan kelurahan/desa
        'village_code',     // Jika Anda akan menambahkan kelurahan/desa
        'postal_code',
        'destination_id',
        'is_default',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Mengembalikan alamat lengkap dalam format string.
     */
    public function fullAddress(): string
    {
        $parts = [
            $this->address,
            $this->district,
            $this->city,
            $this->province,
            $this->postal_code,
        ];

        // Tambahkan desa/kelurahan jika ada
        if (!empty($this->village)) {
            array_splice($parts, 1, 0, $this->village); // Masukkan setelah alamat utama
        }

        return implode(', ', array_filter($parts)); // Gunakan array_filter untuk menghilangkan bagian kosong
    }
}