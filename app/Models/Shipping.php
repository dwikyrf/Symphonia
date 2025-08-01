<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'courier_name',
        'service_code',
        'shipping_cost',
        'tracking_number',
        'estimated_days',
        'status',
        'shipped_at',
        'delivered_at',
    ];
    public function order()
    {
        return $this->hasOne(Order::class); // optional, sekadar mempermudah eloquent
    }
}
