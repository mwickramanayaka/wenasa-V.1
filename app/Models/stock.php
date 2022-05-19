<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'code', 'warehouses_id', 'status'];

    public function getGrnCount()
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

    public function getWarehouse()
    {
        return $this->hasOne(warehouses::class, 'id', 'warehouses_id');
    }
}
