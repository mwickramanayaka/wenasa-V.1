<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stockHasProducts extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'stock_id', 'product_id', 'in_price', 'out_price', 'qty', 'vat_id', 'vat_value', 'exp_date', 'status'];

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }

    public function getStock()
    {
        return $this->hasOne(stock::class, 'id', 'stock_id')
            ->with('getWarehouse');
    }

    public function getProduct()
    {
        return $this->hasOne(Products::class, 'id', 'product_id')->with('getMeasurement');
    }

    public function getvat()
    {
        return $this->hasOne(vat::class,'id','vat_id');
    }

    public function getAllStock()
    {
        return $this->with('getStock')
            ->with('getProduct')
            ->get();
    }

    public function getProductStocksForInvoice($product_id)
    {
        $binIds = [];

        return $this->where('product_id', $product_id)->where('status', 1);

    }
}
