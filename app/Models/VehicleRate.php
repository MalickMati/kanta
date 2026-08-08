<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_type',
        'amount',
    ];
}
