<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_name', 'company_regis', 'street_address', 'city', 'tel1', 'tel2', 'email', 'bank_details', 'status'];

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }

    public function getSupplierList()
    {
        return $this::orderBy('id', 'DESC')->get();
    }

    public function suggetions($input)
    {   
        return $this::where([
            ['status', '=', 1],
            ["name", "LIKE", "%{$input['query']}%"],
        ])->orWhere([
            ['status', '=', 1],
            ["company_name", "LIKE", "%{$input['query']}%"],
        ])->orWhere([
            ['status', '=', 1],
            ["email", "LIKE", "%{$input['query']}%"],
        ])->get();
    }
}
