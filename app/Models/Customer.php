<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_name', 'company_regis', 'street_address', 'city', 'tel1', 'tel2', 'email', 'bank_details', 'admin_id', 'status'];

    public function add($data)
    {
        return $this->create($data);
    }

    public function edit($key, $term, $data)
    {
        return $this->where($key, $term)->update($data);
    }

    public function getCustomerList($admin_id)
    {
        return $this::where('admin_id', $admin_id)->orderBy('id', 'DESC')->get();
    }

    public function getCustomerListByASC()
    {
        return $this::orderBy('name', 'ASC')->get();
    }

    public function suggetions($input)
    {
        return $this::where([
            ['status', '=', 1],
            ['admin_id', '=', Auth::user()->id],
            ["name", "LIKE", "%{$input['query']}%"],
        ])->orWhere([
            ['status', '=', 1],
            ['admin_id', '=', Auth::user()->id],
            ["company_name", "LIKE", "%{$input['query']}%"],
        ])->orWhere([
            ['status', '=', 1],
            ['admin_id', '=', Auth::user()->id],
            ["email", "LIKE", "%{$input['query']}%"],
        ])->get();
    }
}
