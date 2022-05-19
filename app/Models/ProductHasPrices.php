<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHasPrices extends Model
{
    use HasFactory;

    protected $fillable = ['id','product_id','price', 'status'];

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }

    public function getProductHasPriceById($product_id)
    {
        return $this::where('product_id', $product_id)->get();
    }
}
