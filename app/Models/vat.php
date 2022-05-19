<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vat extends Model
{
    use HasFactory;

    public function getActiveVAT()
    {
        return $this::where('status', 1)->get();
    }
}
