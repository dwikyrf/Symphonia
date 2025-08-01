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
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment
            $table->string('title');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade')->index('posts_author_id');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade')->index('posts_category_id');
            $table->string('slug')->unique();
            $table->text('body');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
