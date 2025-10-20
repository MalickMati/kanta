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
        $faker = Faker::create();

        // Create one admin user
        $adminUsername = 'Malick';

        DB::table('users')->insert([
            'username' => $adminUsername,
            'password' => Hash::make('password'),
            'role' => 'admin',
            'name' => 'Mati ur Rehman',
            'phone' => $faker->phoneNumber,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create 200 details linked to the admin
        // for ($i = 0; $i < 200; $i++) {
        //     $firstWeight = $faker->numberBetween(1000, 5000);
        //     $secondWeight = $faker->numberBetween($firstWeight, 10000);
        //     $netWeight = $secondWeight - $firstWeight;

        //     DB::table('details')->insert([
        //         'vehicle_number' => strtoupper($faker->bothify('??###??')),
        //         'party' => $faker->company,
        //         'description' => $faker->sentence,
        //         'first_weight' => $firstWeight,
        //         'first_date' => $faker->date(),
        //         'second_weight' => $secondWeight,
        //         'second_date' => $faker->date(),
        //         'net_weight' => (string) $netWeight,
        //         'amount' => $netWeight * 50, // example calculation
        //         'created_by' => $adminUsername,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}

// php artisan db:seed --class=AdminWithDetailsSeeder
