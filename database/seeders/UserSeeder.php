<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '081271371277',
            'role' => 'admin',
            'remember_token' => Str::random(10)
        ]);
        User::create([
            'name' => 'Dwiky Rahmat Fadhila',
            'username' => 'dwikyrf',
            'email' => 'dwikyrf@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '081271371277',
            'role' => 'user',
            'remember_token' => Str::random(10)
        ]);
        User::create([
            'name' => 'Alfi Basiroh',
            'username' => 'alfibsrh',
            'email' => 'alfibsrh@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '081271334217',
            'role' => 'user',
            'remember_token' => Str::random(10)
        ]);
        User::create([
            'name' => 'Bukyt Anugerah',
            'username' => 'keithhh',
            'email' => 'bukytanugerah@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '081271551277',
            'role' => 'user',
            'remember_token' => Str::random(10)
        ]);
        User::create([
            'name' => 'Faturahman Wahabi',
            'username' => 'hb204',
            'email' => 'faturwahabi@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '08121112317',
            'role' => 'user',
            'remember_token' => Str::random(10)
        ]);
        User::create([
            'name' => 'PT UWU',
            'username' => 'uwuuuuuu',
            'email' => 'ptuwu@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '08121222317',
            'role' => 'corporate',
            'remember_token' => Str::random(10)
        ]);
    }
}
