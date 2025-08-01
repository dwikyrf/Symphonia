<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // relasi
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            // data review
            $table->unsignedTinyInteger('rating');      // 1..5
            $table->string('title');
            $table->text('content');
            $table->json('images')->nullable();
            $table->boolean('verified')->default(false);

            $table->timestamps();

            /* --------------------------------------------------
             | UNIQUE INDEX                                     |
             | Satu order hanya boleh satu review per product   |
             -------------------------------------------------- */
            $table->unique(['order_id', 'product_id']);
            // Kalau mau super-eksplisit:
            // $table->unique(['order_id','product_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
