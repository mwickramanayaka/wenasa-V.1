<?php

namespace App\Http\Controllers;

use App\Models\CurrencyType;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceHasCustomer;
use App\Models\InvoiceHasProductReturns;
use App\Models\InvoiceHasProducts;
use App\Models\InvoiceType;
use App\Models\Merchant;
use App\Models\payment_methods;
use App\Models\Products;
use App\Models\ServiceCharge;
use App\Models\stock;
use App\Models\stockHasProducts;
use App\Models\User;
use App\Models\vat;
use App\Models\warehouses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

// INVOICE STATUS
// 1 = SUCCESS
// 2 = DISCONTINUE
// 3 = RETURNED 

// INVOICE PAYMENT STATUS
// 1 = PAYMENT DONE
// 2 = PAYMENT PENDING

class InvoiceController extends Controller
{
    public function index()
    {
        Session::forget('invoiceProducts');
        Session::forget('data');

        $invoiceCode = str_pad((new Invoice)->getInvoiceCount() + 1, 8, '0', STR_PAD_LEFT);

        $invoice_types = InvoiceType::where('status', 1)->get();
        $merchants = Merchant::where('status', 1)->get();
        $service_charges = ServiceCharge::where('status', 1)->get();
        $currency_type = CurrencyType::where('status', 1)->get();
        $empObj = Employee::where('status', 1)->get();

        return view('/back_end/invoice', compact(
            'invoiceCode',
            'invoice_types',
            'merchants',
            'service_charges',
            'currency_type',
            'empObj'
        ));
    }

    public function sessionRecords()
    {
        $sessionData = Session::get('data', []);
        return $sessionData;
    }

    public function addSessionProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'unit_price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:8',
            'qty' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:8',
            'vat' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:8',
            'discount' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if ((new Invoice)->getProductQty($request->product_id)['totqty'] == null) {
            return 'RESP1';
        }

        if ($request->qty > (new invoice)->getProductQty($request->product_id)['totqty']) {
            return 'RESP2';
        } else {

            $isNew = true;
            $data = $this->sessionRecords();

            foreach ($data as $index => $v) {
                if ($data[$index][0] == $request->product_id) {
                    $data[$index][1][0] = $request->product_id;
                    $data[$index][1][1] = $request->unit_price;
                    $data[$index][1][2] = $request->qty;
                    $data[$index][1][3] = $request->discount;
                    $data[$index][1][4] = $request->vat;

                    $product_stocks = (new stockHasProducts)->getProductStocksForInvoice($request->product_id)->get();
                    $invoiceQty = $request->qty;

                    while ($invoiceQty != 0) {
                        foreach ($product_stocks as $key => $value) {
                            if ($value['status'] == 1) {
                                if ($value['qty'] >= $invoiceQty) {
                                    $value['qty'] = $value['qty'] - $invoiceQty;

                                    if ($value['qty'] == 0) {
                                        $value['status'] = 2;
                                    }

                                    $datadummy[] = [
                                        'id' => $value['id'],
                                        'stock_id' => $value['stock_id'],
                                        'product_id' => $value['product_id'],
                                        'qty' => intval($invoiceQty),
                                        'unit_price' => $request->unit_price,
                                        'discount' => $request->discount,
                                        'vat' => vat::find($request->vat)->value,
                                        'status' => $value['status'],
                                    ];

                                    $data[$index][2] = $datadummy;
                                    $invoiceQty = 0;

                                    break;
                                } else {
                                    $value['status'] = 2;

                                    $datadummy[] = [
                                        'id' => $value['id'],
                                        'stock_id' => $value['stock_id'],
                                        'product_id' => $value['product_id'],
                                        'qty' => intval($value['qty']),
                                        'unit_price' => $request->unit_price,
                                        'discount' => $request->discount,
                                        'vat' => vat::find($request->vat)->value,
                                        'status' => 2,
                                    ];

                                    $invoiceQty = $invoiceQty - $value['qty'];
                                    $value['qty'] = 0;
                                }
                            }
                        }
                    }
                    $isNew = false;
                    break;
                }
            }

            if ($isNew) {

                $product_stocks = (new stockHasProducts)->getProductStocksForInvoice($request->product_id)->get();
                $invoiceQty = $request->qty;

                while ($invoiceQty != 0) {
                    foreach ($product_stocks as $key => $value) {

                        if ($value['status'] == 1) {
                            if ($value['qty'] >= $invoiceQty) {

                                $value['qty'] = $value['qty'] - $invoiceQty;

                                if ($value['qty'] == 0) {
                                    $value['status'] = 2;
                                }

                                $datadummy[] = [
                                    'id' => $value['id'],
                                    'stock_id' => $value['stock_id'],
                                    'product_id' => $value['product_id'],
                                    'qty' => intval($invoiceQty),
                                    'unit_price' => $request->unit_price,
                                    'discount' => $request->discount,
                                    'vat' => vat::find($request->vat)->value,
                                    'status' => $value['status'],
                                ];

                                $data[] = [$request->product_id, [$request->product_id, $request->unit_price, $request->qty, $request->discount, $request->vat], $datadummy];

                                $invoiceQty = 0;

                                break;
                            } else {

                                $value['status'] = 2;

                                $datadummy[] = [
                                    'id' => $value['id'],
                                    'stock_id' => $value['stock_id'],
                                    'product_id' => $value['product_id'],
                                    'qty' => intval($value['qty']),
                                    'unit_price' => $request->unit_price,
                                    'discount' => $request->discount,
                                    'vat' => vat::find($request->vat)->value,
                                    'status' => $value['status'],
                                ];

                                $invoiceQty = $invoiceQty - $value['qty'];
                                $value['qty'] = 0;
                            }
                        }
                    }
                }
            }

            Session::put('data', $data);
            return 'DONE';
        }
    }

    public function getInvoiceTotal(Request $request)
    {
        $data_for_tot = Session::get('data');
        $nettot = 0;

        $discount = $request->discount;
        $invoiceVatValue = vat::find($request->vat_id)->value;
        $invoice_type_value = InvoiceType::find($request->invoice_type)->type_value;
        $sc = ServiceCharge::find($request->sc)->value;

        if ($data_for_tot == null) {
            return env('CURRANCY') . ' ' . number_format(0, 2);
        } else {
            foreach ($data_for_tot as $key => $val) {
                $nettot += ($val[2][0]['unit_price'] * $val[1][2]);
            }

            $nettot = $nettot - $discount;
            $nettot = $nettot * (100 + $sc) / 100;
            $nettot = $nettot * (100 + $invoiceVatValue) / 100;
            $nettot = $nettot * (100 + $invoice_type_value) / 100;

            return env('CURRANCY') . ' ' . number_format($nettot, 2);
        }
    }

    public function getInvoiceTotalForSave(
        $vat_id,
        $discount,
        $sc,
        $invoice_type
    ) {
        $data_for_tot = Session::get('data');
        $nettot = 0;

        $invoiceVatValue = vat::find($vat_id)->value;
        $invoice_type_value = InvoiceType::find($invoice_type)->type_value;
        $sc = ServiceCharge::find($sc)->value;
        $discount = $discount;

        if ($data_for_tot == null) {
            return 0;
        } else {
            foreach ($data_for_tot as $key => $val) {
                $nettot += (($val[2][0]['unit_price'] * $val[1][2]) * (100 + $val[2][0]['vat']) / 100) * (100 - $val[2][0]['discount']) / 100;
            }

            $nettot = $nettot - $discount;
            $nettot = $nettot * (100 + $sc) / 100;
            $nettot = $nettot * (100 + $invoiceVatValue) / 100;
            $nettot = $nettot * (100 + $invoice_type_value) / 100;

            return $nettot;
        }
    }

    public function getInvoiceNetTotal(
        $vat_id,
        $discount,
        $sc,
    ) {
        $data_for_tot = Session::get('data');
        $nettot = 0;

        $invoiceVatValue = vat::find($vat_id)->value;
        $sc = ServiceCharge::find($sc)->value;
        $discount = $discount;

        if ($data_for_tot == null) {
            return 0;
        } else {
            foreach ($data_for_tot as $key => $val) {
                $nettot += (($val[2][0]['unit_price'] * $val[1][2]) * (100 + $val[2][0]['vat']) / 100) * (100 - $val[2][0]['discount']) / 100;
            }

            $nettot = $nettot - $discount;
            $nettot = $nettot * (100 + $sc) / 100;
            $nettot = $nettot * (100 + $invoiceVatValue) / 100;

            return $nettot;
        }
    }

    public function getInvoiceNetTotalForSave()
    {
        $data_for_tot = Session::get('data');
        $nettot = 0;

        if ($data_for_tot == null) {
            return 0;
        } else {
            foreach ($data_for_tot as $key => $val) {
                $nettot += (($val[2][0]['unit_price'] * $val[1][2]) * (100 + $val[2][0]['vat']) / 100) * (100 - $val[2][0]['discount']) / 100;
            }
            return $nettot;
        }
    }

    public function invoiceTableView()
    {
        $tableData = [];
        $records = $this->sessionRecords();
        if (count($records) == 0) {
            return $tableData = [];
        }

        foreach ($records as $index => $value) {

            foreach ($records[$index][2] as $key => $product) {

                $actions = '<div class="mt-md-0 mt-2">' .
                    '<a href="#" data-bs-toggle="dropdown" class="btn btn-sm btn-default text-decoration-none">' .
                    '<i class="fa fa-bars" aria-hidden="true"></i></a><div class="dropdown-menu bg-white rounded-0">' .
                    '<a class="dropdown-item" onclick="invoice_product_remove_func(' . $index . ')">' .
                    '<i class="fa fa-trash-o px-2" aria-hidden="true"></i>Remove' .
                    '</a>' .
                    '</div>' .
                    '</div>';

                $tableData[] = [
                    ++$key,
                    $actions,
                    Products::find($product['product_id'])->code,
                    Str::limit((Products::find($product['product_id'])->lang1_name), 20, '...'),
                    env('CURRANCY') . ' ' . number_format($product['unit_price'], 2),
                    number_format($product['qty'], 2),
                    env('CURRANCY') . ' ' . number_format($product['unit_price'] * $product['qty'], 2),
                ];
            }
        }

        return $tableData;
    }

    public function removeProductFromSession(Request $request)
    {

        $index = $request->index;
        $records = $this->sessionRecords();

        if (count($records) > 0) {
            array_splice($records, $index, 1);
            Session::put('data', $records);
            return 'Successfully Removed';
        } else {
            return 2;
        }
    }

    public function saveInvoice(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'invoice_vat' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:8',
            'type_id' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $records = $this->sessionRecords();
        if ($records == null) {
            return 1;
        } else {

            $data = [
                'invoice_code' => str_pad((new Invoice)->getInvoiceCount() + 1, 8, '0', STR_PAD_LEFT),
                'referance' => null,
                'order_type_id' => 1,
                'invoice_type_id' => $request->type_id,
                'merchant_id' => $request->customer_id,
                'service_charge_id' => $request->sc,
                'emp_id' => $request->emp_id,
                'administration_id' => Auth::user()->id,
                'warehouse_id' => 1,
                'vat_id' => $request->invoice_vat,
                'total' => $this->getInvoiceNetTotalForSave(),
                'discount' => ($request->discount == null ? 0 : $request->discount),
                'sc_value' => ServiceCharge::find($request->sc)->value,
                'vat_value' => vat::find($request->invoice_vat)->value,
                'invoice_type_value' => InvoiceType::find($request->type_id)->type_value,
                'net_total' => $this->getInvoiceNetTotal(
                    $request->invoice_vat,
                    $request->discount,
                    $request->sc,
                ) * (100 + InvoiceType::find($request->type_id)->type_value) / 100,
                'paid_amount' => $this->getInvoiceNetTotal(
                    $request->invoice_vat,
                    $request->discount,
                    $request->sc,
                ) * (100 + InvoiceType::find($request->type_id)->type_value) / 100,
                'balance_amount' => 0,
                'remark' => $request->remark,
                'billing_to' => $request->invoice_billing_to,
                'billing_address' => $request->invoice_billing_address,
                'status' => 1,
            ];

            $invoice = (new Invoice)->add($data);

            foreach ($records as $key => $val) {

                foreach ($val[2] as $pkey => $productval) {

                    $phs = stockHasProducts::find($productval['id']);
                    $phs->qty = $phs->qty - $productval['qty'];
                    $phs->status = $productval['status'];
                    $phs->save();

                    $product_stocks_data = [
                        'invoice_id' => $invoice->id,
                        'unit_price' => $productval['unit_price'],
                        'qty' => $productval['qty'],
                        'total' => $productval['qty'] * $productval['unit_price'],
                        'discount' => $productval['discount'],
                        'vat_id' => 1,
                        'vat_value' => $productval['vat'],
                        'net_total' => ($productval['qty'] * $productval['unit_price']) * (100 + $productval['vat']) / 100,
                        'shp_id' => $productval['id'],
                        'status' => 1,
                    ];

                    (new InvoiceHasProducts)->add($product_stocks_data);
                }
            }

            if ($request->customer_id != '') {

                $invoiceHasCustomerData = [
                    'invoice_id' => $invoice->id,
                    'customer_id' => $request->customer_id,
                    'invoice_type_id' => $request->type_id,
                    'pay_done_date' => Carbon::now(),
                    'status' => 1,
                ];

                (new InvoiceHasCustomer)->add($invoiceHasCustomerData);
            }

            Session::forget('data');
            return [
                'invoice_code' => 'INV/' . date('smy') . '/' . str_pad((new invoice)->getInvoiceCount() + 1, 3, '0', STR_PAD_LEFT),
                'invoice_id' => $invoice->id
            ];
        }
    }

    public function loadInvoiceList()
    {
        $invoiceStatusList = [
            [
                'id' => 0,
                'text' => 'Status'
            ],
            [
                'id' => 1,
                'text' => 'Active'
            ],
            [
                'id' => 2,
                'text' => 'Discontinued'
            ]
        ];

        $paymentTypeList = (new InvoiceType)->getActivePaymentMethods();
        $customerList = (new Customer)->getCustomerListByASC();
        $userList = (new User)->getUserList();

        return view('/back_end/invoice_list', compact('invoiceStatusList', 'paymentTypeList', 'customerList', 'userList'));
    }

    public function getInvoiceList(Request $request)
    {

        $tableData = [];

        $status = $request->status;
        $code = $request->code;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $customer = $request->customer;
        $payment_type = $request->payment_type;
        $ref = $request->ref;
        $admin = $request->admin;
        $customer_address = $request->customer_address;

        $invoices = (new Invoice)->getAllInvoices(
            $status,
            $code,
            $start_date,
            $end_date,
            $customer,
            $payment_type,
            $ref,
            $admin,
            $customer_address
        );

        foreach ($invoices as $key => $value) {

            switch ($value->status) {
                case 1:
                    $invoiceTypeText = 'Successfull';
                    $invoiceTypeColor1 = 'success';
                    $invoiceTypeColor2 = 'success';
                    break;
                case 2:
                    $invoiceTypeText = 'Error';
                    $invoiceTypeColor1 = 'danger';
                    $invoiceTypeColor2 = 'danger';
                    break;
            }

            $invoice_type = '<i class="fa fa-circle text-' . $invoiceTypeColor2 . '-transparent-8 fs-9px fa-fw me-5px"></i>';

            $discontinueContent = '<a onclick="invoice_discontinue_func(' . $value->id . ')" class="dropdown-item font-weight-400 small_font mb-0 ">' .
                '<i class="fa fa-trash-o pe-3" aria-hidden="true"></i>Discontinue Invoice' .
                '</a>';

            $returnInvoiceContent = '<a onclick="invoice_return_func(' . $value->id . ')" class="dropdown-item font-weight-400 small_font mb-0 ">' .
                '<i class="fa fa-repeat pe-3" aria-hidden="true"></i>Return Invoice' .
                '</a>';

            $addInvoicePaymentContent = '<a onclick="invoice_add_payment_func(' . $value->id . ')" class="dropdown-item font-weight-400 small_font mb-0 ">' .
                '<i class="fa fa-money pe-3" aria-hidden="true"></i>Add Invoice Payment' .
                '</a>';

            $value->status == 1 ? $actionContent =  $addInvoicePaymentContent . $returnInvoiceContent . $discontinueContent  : $actionContent = '';

            $actions = '<div class="mt-md-0 mt-2">' .
                '<a href="#" data-bs-toggle="dropdown"class="btn btn-sm btn-default text-decoration-none">' .
                'Action&nbsp<i class="fa fa-caret-down" aria-hidden="true"></i></a><div class="dropdown-menu bg-white rounded-0">' .
                '<a class="dropdown-item font-weight-400 small_font" onclick="print_invoice_func(' . $value->id . ')">' .
                '<i class="fa fa-print pe-3" aria-hidden="true"></i>' .
                'Print Invoice' .
                '</a>' .
                '</div>';

            $tableData[] = [
                ++$key,
                $actions,
                $invoice_type,
                $value->invoice_code,
                date('d-m-Y', strtotime($value->created_at)),
                Merchant::find($value->merchant_id)->merchant_name,
                InvoiceType::find($value->invoice_type_id)->type_name,
                env('CURRANCY') . ' ' . number_format($value->total, 2),
                env('CURRANCY') . ' ' . number_format($value->discount, 2),
                env('CURRANCY') . ' ' . number_format($value->vat_value, 2),
                env('CURRANCY') . ' ' . number_format($value->sc_val, 1) . '%',
                Employee::find($value->emp_id)->emp_name,
                env('CURRANCY') . ' ' . number_format($value->invoice_type_value, 1) . '%',
                env('CURRANCY') . ' ' . number_format($value->net_total, 2),
            ];
        }

        return $tableData;
    }

    public function viewInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        return (new Invoice)->viewInvoice($request->id);
    }

    public function printInvoice(Request $request)
    {
        $data = (new Invoice)->viewInvoice($request->id);

        $date = Carbon::now();
        $sub_total = $data[0]['total'] - $data[0]['discount'] - $data[0]['sc_value'];

        $invoicedata = [
            'invoice_code' => $data[0]['invoice_code'],
            'invoice_date' => $data[0]['created_at'],
            'invoice_total' => $data[0]['total'],
            'invoice_discount' => $data[0]['discount'],
            'invoice_sc_value' => ($data[0]['total'] -  $data[0]['discount']) * $data[0]['sc_value'] / 100,
            'staff' => Employee::find($data[0]['emp_id'])->emp_name,
            'invoice_vat' => ($data[0]['total'] -  $data[0]['discount']) * $data[0]['vat_value'] / 100,
            'invoice_type' => InvoiceType::find($data[0]['invoice_type_id'])->type_name,
            'invoice_type_value' => $data[0]['invoice_type_value'],
            'invoice_sub_total' => ($data[0]['total'] - $data[0]['discount']) * (100 + $data[0]['sc_value']) / 100,
            'invoice_other' => $data[0]['net_total'] - (($data[0]['total'] - $data[0]['discount']) * (100 + $data[0]['sc_value']) / 100),
            'nettotal' => $data[0]['net_total'],
            'invoice_remark' => $data[0]['remark'],
            'sc_precentage' => $data[0]['sc_value'],
            'vat_precentage' => $data[0]['vat_value'],
        ];

        $product = array();
        foreach ($data[0]['getproducts'] as $key => $value) {

            if ($key == 0) {
                $product[] = [
                    $value['getshp']['getproduct']['id'],
                    $value['getshp']['getproduct']['code'],
                    $value['getshp']['getproduct']['lang1_name'],
                    $value['unit_price'],
                    $value['qty'],
                ];
            } else {
                $isSame = false;

                foreach ($product as $product_key => $product_value) {
                    if ($product_value[0] == $value['getshp']['getproduct']['id']) {
                        $product[$product_key][4] += $value['qty'];
                        $isSame = true;
                        break;
                    }
                }
                if ($isSame == false) {
                    $product[] = [
                        $value['getshp']['getproduct']['id'],
                        $value['getshp']['getproduct']['code'],
                        $value['getshp']['getproduct']['lang1_name'],
                        $value['unit_price'],
                        $value['qty'],
                    ];
                }
            }
        }

        $printabledata = ['invoicedetails' => $invoicedata, 'products' => $product];

        if ($printabledata) {
            return view('/back_end/reports/sample18mm')->with('data', $printabledata);
        } else {
            return 2;
        }
    }

    public function discontinueInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $invoiceObj = (new Invoice)->edit('id', $request->id, ['status' => 2]);
        $invoiceHasProductRecords = (new InvoiceHasProducts)->getInvoiceHasProducts($request->id);

        foreach ($invoiceHasProductRecords as $key => $invoiceProducts) {

            (new InvoiceHasProducts)->edit('id', $invoiceProducts->id, ['status' => 2]);

            $shpObj = stockHasProducts::find($invoiceProducts->shp_id);

            if ($shpObj->status == 2) {
                $shp_data = [
                    'qty' => $shpObj->qty + $invoiceProducts->qty,
                    'status' => 1,
                ];
            } else {
                $shp_data = [
                    'qty' => $shpObj->qty + $invoiceProducts->qty,
                ];
            }

            (new stockHasProducts)->edit('id', $shpObj->id, $shp_data);
        }

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => 'Successfully Discontinued Invoice',
            'refresh_status' => 1,
            'field_reset_status' => 1,
        ]);
    }

    public function editStockOfInvoice(Request $request)
    {
        // 1. Check Current Qty and New Qty
        // 2. If New Qty <= Current then; 
        // 3. Edit InvoiceHasProducts
        // 4. Edit StockHasProducts
        // 5. Edit Invoice
        // 5. Create Table Invoice Returns
        // 6. Attributes: {'invoice_id', 'product_id', 'ihp_id, shp_id', 'previous_qty', 'new_qty' ,'qty_def' }

        $validator = Validator::make($request->all(), [
            'product_array' => 'required|array|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $productArray = $request->product_array;
        $invoiceObj = null;
        $invoiceTotAmount = null;
        $invoiceBalanceAmount = null;
        $invoicePaidAmount = null;
        $deduction_total = 0;
        $add_total = 0;

        foreach ($productArray as $key => $value) {

            // REQUEST_ARRAY_FORMATE = [['ihp_id','shp_id'],'qty'];

            $newQty = floatval($value[1]);
            $currentInvoicHasProducteObj = InvoiceHasProducts::find($value[0][0]);
            $shpObj = stockHasProducts::find($value[0][1]);
            $invoiceObj = Invoice::find($currentInvoicHasProducteObj->invoice_id);
            $invoiceTotAmount = $invoiceObj->total;
            // $invoiceBalanceAmount = $invoiceObj->balance_amount;
            $invoicePaidAmount = $invoiceObj->paid_amount;

            if ($currentInvoicHasProducteObj->qty < $newQty) {
                return response()->json([
                    'code' => 1,
                    'type' => 'error',
                    'des' => 'Returned qty must be less than exist qty',
                    'refresh_status' => 1,
                    'field_reset_status' => 1,
                ]);
            }

            if ($currentInvoicHasProducteObj->qty != $newQty) {

                $shp_current_qty = $shpObj->qty;
                $stock_qty = $shp_current_qty + $currentInvoicHasProducteObj->qty - $newQty;
                $qty_def = $currentInvoicHasProducteObj->qty - $newQty;

                if ($value[1] != 0) {
                    $ihp_data = [
                        'qty' => $newQty,
                        'total' => $currentInvoicHasProducteObj->unit_price * $newQty,
                        'net_total' => ($currentInvoicHasProducteObj->unit_price * $newQty) * ((100 + $currentInvoicHasProducteObj->vat_value) / 100) * (((100 - $currentInvoicHasProducteObj->discount) / 100)),
                    ];
                    $stock_data = ['qty' => $stock_qty];
                } else {
                    $ihp_data = [
                        'qty' => $newQty,
                        'total' => $currentInvoicHasProducteObj->unit_price * $newQty,
                        'net_total' => ($currentInvoicHasProducteObj->unit_price * $newQty) * ((100 + $currentInvoicHasProducteObj->vat_value) / 100) * (((100 - $currentInvoicHasProducteObj->discount) / 100)),
                        'status' => 2,
                    ];
                    $stock_data = ['qty' => $stock_qty, 'status' => 2];
                }

                $ihp_returns = [
                    'invoice_id' => $currentInvoicHasProducteObj->invoice_id,
                    'product_id' => $shpObj->product_id,
                    'ihp_id' => $currentInvoicHasProducteObj->id,
                    'shp_id' => $shpObj->id,
                    'previous_qty' => $currentInvoicHasProducteObj->qty,
                    'new_qty' => $newQty,
                    'qty_def' => $qty_def,
                ];

                (new InvoiceHasProductReturns)->add($ihp_returns);
                (new InvoiceHasProducts)->edit('id', $value[0][0], $ihp_data);
                (new stockHasProducts)->edit('id', $value[0][1], $stock_data);

                $deduction_total += $currentInvoicHasProducteObj->net_total;
                $add_total += $currentInvoicHasProducteObj->unit_price * $newQty;
            }
        }

        $invoiceTotAmount = $invoiceTotAmount - $deduction_total + $add_total;
        $invoiceBalanceAmount = $invoiceTotAmount - $invoicePaidAmount;

        $invoice_data = [
            'total' => $invoiceTotAmount,
            'net_total' => $invoiceTotAmount * ((100 + vat::find($invoiceObj->vat_id)->value) / 100) * (((100 - $invoiceObj->discount) / 100)),
            'balance_amount' => $invoiceBalanceAmount,
        ];

        if ($invoicePaidAmount > $invoiceBalanceAmount) {
            Log::ERROR(
                $invoiceObj->id .
                    '-' .
                    $invoiceObj->code .
                    'THIS INVOICE HAS PAYMENT ERROR' .
                    ' | PAID : ' . $invoicePaidAmount .
                    ' | PREVIOUS BALANCE : ' . $invoiceBalanceAmount
            );
        }

        (new Invoice)->edit('id', $invoiceObj->id, $invoice_data);

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => 'Successfully Return Products to Stock',
            'refresh_status' => 1,
            'field_reset_status' => 1,
        ]);
    }
}
