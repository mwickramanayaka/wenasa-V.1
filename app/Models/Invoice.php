<?php

namespace App\Models;

use App\Http\Controllers\StockHasProductsController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'invoice_code',
        'referance',
        'order_type_id',
        'invoice_type_id',
        'merchant_id',
        'service_charge_id',
        'emp_id',
        'administration_id',
        'warehouse_id',
        'vat_id',
        'total',
        'discount',
        'sc_value',
        'vat_value',
        'invoice_type_value',
        'net_total',
        'paid_amount',
        'balance_amount',
        'remark',
        'billing_to',
        'billing_address',
        'status'
    ];

    public function getInvoiceCount()
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

    public function getProductQty($id)
    {
        return (new stockHasProducts)->getProductStocksForInvoice($id)->selectRaw("SUM(qty) as totqty")->first();
    }

    public function getAllInvoices(
        $status,
        $code,
        $start_date,
        $end_date,
        $customer,
        $payment_type,
        $ref,
        $admin,
        $customer_address
    ) {
        $invoiceObj =  $this;
        if ($status != 0) {
            $invoiceObj->where('status', $status);
        }
        if ($admin != 0) {
            $invoiceObj->where('administration_id', $admin);
        }
        if ($payment_type != 0) {
            $invoiceObj->where('order_type_id', $payment_type);
        }
        $invoiceObj->orderBy('created_at', 'DESC');

        return $invoiceObj->orderBy('id', 'DESC')->get();
    }

    public function getAdministrationWiseInvoices($user_id)
    {
        return $this->where('administration_id', $user_id)->get();
    }

    public function getVat()
    {
        return $this->hasOne(vat::class, 'id', 'vat_id');
    }

    public function getwarehouse()
    {
        return $this->hasOne(warehouses::class, 'id', 'warehouse_id');
    }

    public function getproducts()
    {
        return $this->hasMany(InvoiceHasProducts::class, 'invoice_id', 'id')->with('getshp')->with('getvat');
    }

    public function viewInvoice($id)
    {
        return $this::where('id', $id)
            ->with('getVat')
            ->with('getwarehouse')
            ->with('getproducts')
            ->get();
    }
}
