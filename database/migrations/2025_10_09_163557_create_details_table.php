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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number');
            $table->string('party');
            $table->string('description')->nullable()->default(null);
            $table->integer('first_weight');
            $table->dateTime('first_date');
            $table->integer('second_weight')->nullable()->default(null);
            $table->dateTime('second_date')->nullable()->default(null);
            $table->string('net_weight')->nullable()->default(null);
            $table->integer('amount')->nullable()->default(null);
            $table->string('created_by');
            $table->foreign('created_by')->references('username')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
