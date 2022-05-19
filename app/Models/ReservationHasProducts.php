<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationHasProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'product_id',
        'qty',
        'total_amount',
        'status',
    ];

}
