<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grnType extends Model
{
    use HasFactory;

    public function getActiveGrnTypeList()
    {
        return $this::where('status',1)->get();
    }

}
