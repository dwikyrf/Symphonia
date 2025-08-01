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
        'village',
        'village_code',
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

    public function fullAddress(): string
    {
        return trim("{$this->address}, {$this->village}, {$this->district}, {$this->city}, {$this->province}, {$this->postal_code}", ', ');
    }
}
