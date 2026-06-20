<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class metadataseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('metadatas')->insert([
            'name' => 'Address',
            'value' => 'USMAN RICE MILL HAFIZABAD ROAD, VANIKE TARAR',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('metadatas')->insert([
            'name' => 'Company_Name',
            'value' => 'AL HAMD COMPUTERIZED KANTA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

// php artisan db:seed --class=metadataseeder
