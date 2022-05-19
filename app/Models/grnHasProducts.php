<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grnHasProducts extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'grn_id', 'product_id', 'unit_price', 'request_qty', 'in_qty', 'total', 'vat_id', 'net_total', 'status'];

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }

    public function getProduct()
    {
        return $this->hasOne(Products::class, 'id', 'product_id')->with('getMeasurement');
    }

    public function getMeasurement()
    {
        return $this->hasOne(Measurement::class, 'id', 'measurement_id');
    }

    public function getVat()
    {
        return $this->hasOne(vat::class, 'id', 'vat_id');
    }
}
