<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Membuat tabel statuses
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();  // Nama status (unik)
            $table->string('slug')->unique();  // Slug status (unik)
            $table->string('color')->default('bg-gray-500 text-white'); // Warna status
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Hapus tabel statuses
        Schema::dropIfExists('statuses');
    }
};
