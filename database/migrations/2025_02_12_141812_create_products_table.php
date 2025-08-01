<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Ganti dari title → name
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade')->index();
            $table->string('slug')->unique();
            $table->text('description'); // Ganti dari body → description
            $table->integer('price');
            $table->string('image')->nullable(); // Ganti dari gambar → image
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
