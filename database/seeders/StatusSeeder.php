<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['name' => 'Belum Bayar', 'slug' => 'belum-bayar', 'color' => 'bg-red-500 text-white'],
            ['name' => 'Di Proses', 'slug' => 'di-proses', 'color' => 'bg-yellow-500 text-white'],
            ['name' => 'Pelunasan', 'slug' => 'pelunasan', 'color' => 'bg-blue-500 text-white'],
            ['name' => 'Pendistribusian', 'slug' => 'pendistribusian', 'color' => 'bg-purple-500 text-white'],
            ['name' => 'Selesai', 'slug' => 'selesai', 'color' => 'bg-green-500 text-white'],
            ['name' => 'Di Batalkan', 'slug' => 'di-batalkan', 'color' => 'bg-gray-500 text-white'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}

