<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Buat transaksi baru untuk DP atau pelunasan.
     */
    public function createStageTransaction(Order $order, string $stage): Transaction
    {
        $amount = $stage === 'dp'
            ? $order->getDpAmount()        // 25 %
            : $order->remaining_balance;   // sisa

        return $order->transactions()->create([
            'user_id'           => $order->user_id,
            'transaction_uuid'  => Str::uuid(),
            'order_date'        => now(),
            'total_payment'     => $amount,
            'payment_method'    => 'bank_transfer',
            'payment_stage'     => $stage,
            'status_id'         => config('status.pending'), // ganti dgn konstanta Anda
        ]);
    }
    
}
