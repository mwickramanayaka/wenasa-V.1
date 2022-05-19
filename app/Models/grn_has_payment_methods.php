<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grn_has_payment_methods extends Model
{
    use HasFactory;

    public function getPaymentMethod()
    {
        return $this->hasOne(payment_methods::class, 'id' , 'payment_methods_id');
    }

}
