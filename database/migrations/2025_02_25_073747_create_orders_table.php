<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // 📦 Identitas dan Relasi
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null');
            $table->foreignId('shipping_id')->nullable()->constrained('shippings')->onDelete('set null');

            // 👤 Tipe User
            $table->enum('role', ['user', 'corporate'])->default('user');

            // 💰 Nilai Transaksi
            $table->decimal('price', 10, 2)->default(0); // subtotal produk
            $table->decimal('total_price', 10, 2)->default(0); // produk + ongkir
            $table->decimal('dp_paid', 10, 2)->default(0);
            $table->decimal('remaining_balance', 10, 2)->default(0);
            $table->integer('total_quantity')->default(0);

            // 📌 Status
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->enum('selected_payment_type', ['dp', 'full'])->nullable();

            // 📎 Data Tambahan
            $table->string('corporate_proof')->nullable();
            $table->string('design')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();

            // 🕒 Timestamps
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
