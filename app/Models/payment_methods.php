<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment_methods extends Model
{
    use HasFactory;

    public function getActivePaymentMethods()
    {
        return $this->where('status', 1)->get();
    }
}
