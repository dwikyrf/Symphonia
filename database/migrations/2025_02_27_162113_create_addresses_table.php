<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke users

            $table->string('recipient_name'); // Nama penerima
            $table->string('phone');          // Nomor HP
            $table->string('address');        // Alamat lengkap (jalan, rumah, gedung)

            // Wilayah administratif
            $table->string('province');        // Nama provinsi
            $table->string('province_code');   // Kode provinsi (Komerce API)
            $table->string('city');            // Nama kota/kabupaten
            $table->string('city_code');       // Kode kota/kabupaten (Komerce API)
            $table->string('district');        // Nama kecamatan
            $table->string('district_code');   // Kode kecamatan (Komerce API)
            $table->string('village')->nullable();        // Nama kelurahan
            $table->string('village_code')->nullable();   // Kode kelurahan dari API

            $table->string('postal_code');     // Kode pos
            $table->unsignedBigInteger('destination_id'); // ID tujuan dari Komerce (untuk ongkir)

            $table->boolean('is_default')->default(false); // Penanda alamat default
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
