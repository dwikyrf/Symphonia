<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada user ditemukan untuk seeding alamat.');
            return;
        }
            foreach ($users as $user) {
                $addressCount = rand(1, 3);
                for ($i = 0; $i < $addressCount; $i++) {
                Address::create([
                    'user_id'         => $user->id,
                    'recipient_name'  => $user->name,
                    'phone'           => '08' . rand(1000000000, 9999999999),
                    'address'         => fake()->streetAddress(),
                    'province'        => fake()->state(),
                    'province_code'   => strtoupper(Str::random(4)),
                    'city'            => fake()->city(),
                    'city_code'       => strtoupper(Str::random(4)),
                    'district'        => fake()->citySuffix(),
                    'district_code'   => strtoupper(Str::random(6)),
                    'village'         => fake()->streetName(),
                    'village_code'    => strtoupper(Str::random(6)),
                    'postal_code'     => fake()->postcode(),
                    'destination_id'  => rand(10000, 20000),
                    'is_default'      => $i === 0,
                ]);
            }
        }

        $this->command->info('✅ Alamat untuk semua user berhasil dibuat.');
    }
}
