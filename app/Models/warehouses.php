<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class warehouses extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'code', 'name', 'address', 'telephone', 'email', 'map_code', 'latitude', 'longitude', 'status'];

    public function getWarehouseCount()
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

    public function getAllWarehouses()
    {
        return $this::where('status', 1)->get();
    }
    
}
