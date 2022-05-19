<?php

namespace App\Http\Controllers;

use App\Models\grnType;
use Illuminate\Http\Request;

class GrnTypeController extends Controller
{
    public function getAllGrnType()
    {
        return (new grnType)->getActiveGrnTypeList();
    }
}
