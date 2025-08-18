<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'order_id',
        'order_date',
        'total_payment',
        'payment_method',
        'payment_stage',
        'transfer_proof_dp',
        'is_verified_dp',
        'transfer_proof_full',
        'is_verified_full',
        'status',
    ];
protected $casts = [
        'order_date' => 'datetime',   
        'is_verified_dp'   => 'boolean',
        'is_verified_full' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $tx) {

            if (empty($tx->transaction_id)) {
                $tx->transaction_id = (string) Str::uuid();
            }

        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeStatus($q, string $stat)
    {
        return $q->where('status', $stat);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending', 'pending_dp'         => 'bg-yellow-100 text-yellow-800',
            'pending_full', 'pending_po'    => 'bg-orange-100 text-orange-800',
            'paid_dp'                       => 'bg-blue-100  text-blue-800',
            'paid', 'approved'              => 'bg-green-100 text-green-800',
            'failed', 'rejected'            => 'bg-red-100   text-red-800',
            default                         => 'bg-gray-100  text-gray-800',
        };
    }
}
