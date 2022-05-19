<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceHasProducts extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'unit_price', 'qty', 'total', 'discount', 'vat_id', 'vat_value', 'net_total', 'shp_id', 'chp_id', 'status'];

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }

    public function getvat()
    {
        return $this->hasOne(vat::class,'id','vat_id');
    }

    public function getshp()
    {
        return $this->hasOne(stockHasProducts::class,'id','shp_id')->with('getproduct')->with('getvat');
    }

    public function getInvoiceHasProducts($invoice_id)
    {
        return $this->where('invoice_id', $invoice_id)->get();
    }

}
