<?php

namespace App\Http\Controllers;

use App\Models\vat;
use Illuminate\Http\Request;

class VatController extends Controller
{
    
    public function getAllVAT()
    {
        return (new vat)->getActiveVAT();
    }

}
