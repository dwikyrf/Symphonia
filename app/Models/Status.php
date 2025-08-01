<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'color'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

