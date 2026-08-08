<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicle_rates', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type')->unique();
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        // Insert initial default rates
        DB::table('vehicle_rates')->insert([
            ['vehicle_type' => 'Rickshaw', 'amount' => 100.00, 'created_at' => now(), 'updated_at' => now()],
            ['vehicle_type' => 'Truck', 'amount' => 1000.00, 'created_at' => now(), 'updated_at' => now()],
            ['vehicle_type' => 'Passcco', 'amount' => 800.00, 'created_at' => now(), 'updated_at' => now()],
            ['vehicle_type' => 'Tractor', 'amount' => 500.00, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_rates');
    }
};
