<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();

            $table->string('courier_name');              // ex: NINJA, J&T, JNE
            $table->string('service_code');              // ex: REG, YES, Standard
            $table->integer('shipping_cost');            // dalam satuan Rupiah
            $table->string('tracking_number')->nullable();
            $table->string('estimated_days')->nullable();
            $table->string('status')->default('pending'); // pending, dikirim, diterima
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
