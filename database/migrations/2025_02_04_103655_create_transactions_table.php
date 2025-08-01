<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Schema: transactions
     * ────────────────────
     * • payment_method  : saat ini hanya “bank_transfer”
     * • payment_stage   : dp | full
     * • status (enum)   :
     *      pending        → menunggu DP / PO
     *      paid_dp        → DP terverifikasi
     *      pending_full   → menunggu pelunasan
     *      pending_po     → PO di-upload (korporat), menunggu verifikasi
     *      approved       → PO disetujui
     *      paid           → lunas (DP + full, atau PO approved)
     *      failed         → pembayaran ditolak / gagal
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            /* identitas & relasi */
            $table->string('transaction_id')->unique();        // nomor unik (mis. TRX-XXXX)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            /* informasi dasar */
            $table->date('order_date');
            $table->decimal('total_payment', 10, 2);

            /* metode & tahap pembayaran */
            $table->enum('payment_method', ['bank_transfer'])->default('bank_transfer');
            $table->enum('payment_stage',  ['dp', 'full'])->default('dp');

            /* bukti transfer */
            $table->string('transfer_proof_dp')->nullable();
            $table->boolean('is_verified_dp')->default(false);

            $table->string('transfer_proof_full')->nullable();
            $table->boolean('is_verified_full')->default(false);

            /* status transaksi (enum saja, tidak pakai tabel statuses) */
            $table->enum('status', [
                'pending',
                'paid_dp',
                'pending_full',
                'pending_po',
                'approved',
                'paid',
                'failed',
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
