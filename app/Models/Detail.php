<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail extends Model
{
    protected $fillable = [
        'vehicle_number',
        'party',
        'description',
        'first_weight',
        'first_date',
        'second_weight',
        'second_date',
        'net_weight',
        'amount',
        'created_by',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
