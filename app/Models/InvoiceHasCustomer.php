<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceHasCustomer extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'customer_id', 'invoice_type_id', 'pay_done_date', 'status'];

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }
}
