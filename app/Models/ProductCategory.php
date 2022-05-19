<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'category_level', 'priority_level', 'media_id', 'category_id', 'status'];

    public function getActiveAll()
    {
        return $this::where('status', 1)->get();
    }

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }

    public function getProductCategoryCount()
    {
        return $this::count();
    }

    public function suggetions($input)
    {
        return $this::where([
            ['status', '=', 1],
            ["name", "LIKE", "%{$input['query']}%"],
        ])->orWhere([
            ['status', '=', 1],
            ["code", "LIKE", "%{$input['query']}%"],
        ])->get();
    }

    public function getCategoryList()
    {
        return $this::where('status', 1)->orderBy('id', 'ASC')->get();
    }

    public function getCategoryImage()
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }

    public function getSubCategoryById()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }

    public function getCategoryById($category_id)
    {
        return (new ProductCategory)->where('id', $category_id)->with('getSubCategoryById')->with('getCategoryImage');
    }
}
