<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Support\Money;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    'order_number',
    'user_id',
    'address_id',
    'role',
    'total_price',
    'shipping_cost',
    'shipping_id', // ✅ tambahkan ini
    'dp_paid',
    'remaining_balance',
    'total_quantity',
    'payment_status',
    'status',
    'corporate_proof',
    'selected_payment_type',
    'design',
    'logo',
    'description',
    'price',
];

    public function getDpAmount(): int
    {
        // hitung grand-total = harga barang + ongkir
        $grandTotal = $this->price + ($this->shipping->shipping_cost ?? 0);

        // 25 % dibulatkan ke bawah → sama dengan Math.floor() di JS
        return intdiv($grandTotal * 25, 100);
    }

    public function applyPayment(int $paid, string $stage): void
    {
        $this->remaining_balance -= $paid;

        if ($stage === 'dp') {
            $this->payment_status = 'partial';
        } else { // full / pelunasan
            $this->remaining_balance = 0;
            $this->payment_status    = 'paid';
        }

        $this->save();
    }
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = self::generateUniqueOrderNumber();
        });
    }

    private static function generateUniqueOrderNumber()
    {
        do {
            $randomNumber = 'ORD-' . date('Ymd') . '-' . Str::upper(Str::random(6));
        } while (self::where('order_number', $randomNumber)->exists());

        return $randomNumber;
    }

    public function getRemainingPaymentAttribute()
    {
        return $this->total_price - $this->dp_paid;
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-500 text-white',
            'processing' => 'bg-blue-500 text-white',
            'completed' => 'bg-green-500 text-white',
            'cancelled' => 'bg-gray-500 text-white',
            default => 'bg-gray-500 text-white',
        };
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function tracking()
    {
        return $this->hasMany(OrderTracking::class)->orderBy('created_at', 'desc');
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }
     public function reviews()
    {
        return $this->hasMany(Review::class); // Asumsi model ulasan Anda bernama 'Review'
    }
}
