<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceHasProductReturns extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'ihp_id',
        'shp_id',
        'previous_qty',
        'new_qty',
        'qty_def',
        'remark',
    ];

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }
}
