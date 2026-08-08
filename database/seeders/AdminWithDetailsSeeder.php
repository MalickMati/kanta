<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AdminWithDetailsSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'Malick',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'name' => 'Mati ur Rehman',
            'phone' => '0349-4134189',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

// php artisan db:seed --class=AdminWithDetailsSeeder
