<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'lang1_name', 'lang2_name', 'lang3_name', 'default_price', 'description', 'low_stock_alert_qty', 'product_category_id', 'measurement_id', 'brand_id', 'product_type_id', 'status'];

    public function getProductCount()
    {
        return $this::count();
    }

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }

    public function getProductById($id)
    {
        return $this->where('id', $id)
            ->with('getProductImage')
            ->with('getMeasurement')
            ->first();
    }

    public function suggetions($input)
    {
        return $this::where([
            ['status', '=', 1],
            ["lang1_name", "LIKE", "%{$input['query']}%"],
        ])->orWhere([
            ['status', '=', 1],
            ["lang2_name", "LIKE", "%{$input['query']}%"],
        ])->orWhere([
            ['status', '=', 1],
            ["code", "LIKE", "%{$input['query']}%"],
        ])->get();
    }

    public function getProductCategory()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'product_category_id');
    }

    public function getProductMedia()
    {
        return $this->hasMany(ProductHasMedia::class, 'product_id')->with('getMedia');
    }

    public function getFeaturedProductsByCategory($category_id)
    {
        return $this->where('status', 1)
            ->where('product_category_id', $category_id)->orderBy('id', 'DESC')
            ->with('getMeasurement')
            ->with('getProductCategory')
            ->with('getProductMedia')
            ->offset(0)
            ->limit(10)
            ->get();
    }

    public function getProductsByCategory($category_id)
    {
        return $this->where('status', 1)
            ->where('product_category_id', $category_id)->orderBy('id', 'DESC')
            ->with('getMeasurement')
            ->with('getProductCategory')
            ->with('getProductMedia')
            ->get();
    }

    public function getMeasurement()
    {
        return $this->hasOne(Measurement::class, 'id', 'measurement_id');
    }

    public function getBrand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function getProductType()
    {
        return $this->hasOne(ProductType::class, 'id', 'product_type_id');
    }

    public function getProductImage()
    {
        return $this->hasMany(ProductHasMedia::class, 'product_id', 'id')->with('getMedia');
    }

    public function getAllProducts()
    {
        return $this
            ->orderBy('status', 'ASC')
            ->orderBy('lang1_name', 'ASC')
            ->with('getProductCategory')
            ->with('getMeasurement')
            ->with('getBrand')
            ->with('getProductType')
            ->with('getProductImage')
            ->get();
    }

}
