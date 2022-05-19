<?php

namespace App\Http\Controllers;

use App\Models\grn;
use App\Models\grnHasProducts;
use App\Models\grnType;
use App\Models\Products;
use App\Models\stock;
use App\Models\stockHasProducts;
use App\Models\vat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GrnController extends Controller
{
    public function index()
    {
        Session::forget('grnProducts');
        $grnCode = 'GRN/' . str_pad((new grn)->getGrnCount() + 1, 3, '0', STR_PAD_LEFT);
        return view('/back_end/grn', compact('grnCode'));
    }

    public function sessionRecords()
    {
        return Session::get('grnProducts', array());
    }

    public function calculateSubTotals($records)
    {
        $total = 0;
        foreach ($records as $key => $value) {
            $total += $value[1];
        }
        return round($total, 2);
    }

    public function addSessionProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'unit_price' => 'required|numeric|min:1',
            'qty' => 'required|numeric|min:1',
            'vat' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $records = $this->sessionRecords();
        $isNew = true;

        foreach ($records as $key => $product) {
            if ($product[0] == $request->product_id) {
                $records[$key][1] = $request->unit_price;
                $records[$key][2] = $request->qty;
                $records[$key][3] = $request->vat;
                $isNew = false;
                break;
            }
        }

        if ($isNew) {
            $records[] = [
                $request->product_id,
                $request->unit_price,
                $request->qty,
                $request->vat
            ];
        }

        Session::put('grnProducts', $records);

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => 'Successfully Added Product to GRN',
            'refresh_status' => 2,
            'fieild_reset_status' => 2,
        ]);
    }

    public function grnAddedProductList()
    {

        $tableData = [];
        $records = $this->sessionRecords();
        if (count($records) == 0) {
            return $tableData = [];
        }

        foreach ($records as $key => $product) {

            $actions = '<div class="mt-md-0 mt-2">' .
                '<a href="#" data-bs-toggle="dropdown" class="btn btn-sm btn-default text-decoration-none">' .
                '<i class="fa fa-bars" aria-hidden="true"></i></a><div class="dropdown-menu bg-white rounded-0">' .
                '<a class="dropdown-item" onclick="grn_product_remove_func(' . $key . ')">' .
                '<i class="fa fa-trash-o px-2" aria-hidden="true"></i>Remove' .
                '</a>' .
                '<a onclick="grn_product_view_func(' . $product[0] . ')" class="dropdown-item">' .
                '<i class="fa fa-eye px-2" aria-hidden="true"></i>View Product' .
                '</a>' .
                '</div>' .
                '</div>';

            $tableData[] = [
                ++$key,
                $actions,
                Str::limit((Products::find($product[0])->lang1_name), 20, '...'),
                Products::find($product[0])->code,
                env('CURRANCY') . ' ' . number_format($product[1], 2),
                number_format($product[2], 2),
                number_format($product[1] * $product[2], 2),
                vat::find($product[3])->value,
                env('CURRANCY') . ' ' . number_format(($product[1] * $product[2]) * (100 + vat::find($product[3])->value) / 100, 2)
            ];
        }

        return $tableData;
    }

    public function getAddedSessionProductTotal(Request $request)
    {

        $grn_total = 0;
        $records = $this->sessionRecords();
        if (count($records) == 0) {
            return env('CURRANCY') . ' ' . number_format($grn_total, 2);
        }

        foreach ($records as $key => $product) {
            $grn_total += ($product[1] * $product[2]) * (100 + vat::find($product[3])->value) / 100;
        }

        $grn_total = $grn_total;
        return env('CURRANCY') . ' ' . number_format($grn_total, 2);
    }

    public function grnRemoveSessionProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'grn_product_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $index = $request->grn_product_id;

        $records = $this->sessionRecords();
        if (count($records) > 0) {
            array_splice($records, $index, 1);
            Session::put('grnProducts', $records);

            $return_val = ['success', 'Product removed successfully'];
        } else {
            $return_val = ['error', 'Something went wrong !'];
        }

        return response()->json([
            'code' => 1,
            'type' => $return_val[0],
            'des' => $return_val[1],
            'refresh_status' => 1,
            'field_reset_status' => 1,
        ]);
    }

    public function saveGRN(Request $request)
    {

        $session = Session::has('grnProducts');

        if ($session) {

            $grn_total = 0;
            $records = $this->sessionRecords();

            foreach ($records as $key => $product) {
                $grn_total += ($product[1] * $product[2]) * (100 + vat::find($product[3])->value) / 100;
            }

            $grn_data = [
                'grn_code' =>  'GRN/' . str_pad((new grn)->getGrnCount() + 1, 3, '0', STR_PAD_LEFT),
                'po_ref' => $request->po_ref,
                'total' => $grn_total,
                'supplier_id' => 1,
                'grn_type_id' => 1,
                'vat_id' => 1,
                'warehouse_id' => 1,
                'remark' => $request->remark,
                'status' => 1,
                'user_id' => 1,
            ];

            $added_grn = (new grn)->add($grn_data);

            if ($request->grn_type == 1) {
                $stock_data = [
                    'code' => 'STK/' . date('smy') . '/' . str_pad((new stock)->getGrnCount() + 1, 3, '0', STR_PAD_LEFT),
                    'warehouses_id' => $request->warehouse_id,
                    'status' => 1
                ];
                $added_stock = (new stock)->add($stock_data);
            }

            $records = $this->sessionRecords();
            foreach ($records as $key => $grn_product) {

                $grn_product_data = [
                    'grn_id' => $added_grn->id,
                    'product_id' => $grn_product[0],
                    'unit_price' => $grn_product[1],
                    'request_qty' => $grn_product[2],
                    'in_qty' => $grn_product[2],
                    'total' => $grn_product[1] * $grn_product[2],
                    'vat_id' => $grn_product[3],
                    'net_total' => ($grn_product[1] * $grn_product[2]) * (100 + vat::find($grn_product[3])->value) / 100,
                    'status' => 1
                ];

                (new grnHasProducts)->add($grn_product_data);

                if ($request->grn_type == 1) {
                    $stock_product_data = [
                        'stock_id' => $added_stock->id,
                        'product_id' => $grn_product[0],
                        'in_price' => $grn_product[1],
                        'out_price' => Products::find($grn_product[0])->default_price,
                        'qty' => $grn_product[2],
                        'vat_id' => $grn_product[3],
                        'vat_value' => vat::find($grn_product[3])->value,
                        'status' => 1,
                    ];
                    (new stockHasProducts)->add($stock_product_data);
                }
            }
            $return_value = ['success', 'GRN/' . date('smy') . '/' . str_pad((new grn)->getGrnCount() + 1, 3, '0', STR_PAD_LEFT)];
        } else {
            $return_value = ['error', 'Please add products before save GRN'];
        }

        Session::forget('grnProducts');

        return response()->json([
            'code' => 1,
            'type' => $return_value[0],
            'des' => $return_value[1],
            'refresh_status' => 1,
            'field_reset_status' => 1,
        ]);
    }

    public function purchaseList(Request $request)
    {
        $tableData = [];
        $records = (new grn)->getAllGRN();

        $array = json_decode(json_encode($records), true);

        foreach ($records as $key => $value) {

            $payment_array = array();
            $payed_total = 0;
            $payment_balance = $value->total;

            foreach ($value['getPurchases'] as $purchaseKey => $purchaseValue) {
                $payment_array[] = [$purchaseValue];
                $payed_total += $purchaseValue->amount;
            }

            if (empty($payment_array)) {
                $statusText = 'Pending';
                $statusColor1 = 'warning';
                $statusColor2 = 'warning';
            } else {

                $payment_balance = $value->total - $payed_total;

                if ($payment_balance != 0) {
                    $statusText = 'Pending';
                    $statusColor1 = 'warning';
                    $statusColor2 = 'warning';
                } else {
                    $statusText = 'Done';
                    $statusColor1 = 'success';
                    $statusColor2 = 'success';
                }
            }

            switch ($value->grn_type_id) {
                case 1:
                    $purchaseTypeText = grnType::find($value->grn_type_id)->grn_type;
                    $purchaseTypeColor1 = 'success';
                    $purchaseTypeColor2 = 'success';
                    break;
                case 2:
                    $purchaseTypeText = grnType::find($value->grn_type_id)->grn_type;
                    $purchaseTypeColor1 = 'warning';
                    $purchaseTypeColor2 = 'warning';
                    break;
                case 3:
                    $purchaseTypeText = grnType::find($value->grn_type_id)->grn_type;
                    $purchaseTypeColor1 = 'info';
                    $purchaseTypeColor2 = 'primary';
                    break;
                case 4:
                    $purchaseTypeText = grnType::find($value->grn_type_id)->grn_type;
                    $purchaseTypeColor1 = 'dark';
                    $purchaseTypeColor2 = 'secondary';
                    break;
                case 5:
                    $purchaseTypeText = grnType::find($value->grn_type_id)->grn_type;
                    $purchaseTypeColor1 = 'danger';
                    $purchaseTypeColor2 = 'danger';
                    break;
            }

            $purchase_type = '<span class="badge rounded-1 my-1 font-weight-400 bg-' . $purchaseTypeColor1 . '-transparent-2 text-' . $purchaseTypeColor2 . ' px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">' .
                '<i class="fa fa-circle text-' . $purchaseTypeColor2 . '-transparent-8 fs-9px fa-fw me-5px"></i>'
                . $purchaseTypeText .
                '</span>';

            $payment_status = '<span class="badge rounded-1 my-1 font-weight-400 bg-' . $statusColor1 . '-transparent-2 text-' . $statusColor2 . ' px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">' .
                '<i class="fa fa-circle text-' . $statusColor2 . '-transparent-8 fs-9px fa-fw me-5px"></i>'
                . $statusText .
                '</span>';

            $actions = '<div class="mt-md-0 mt-2">' .
                '<a href="#" data-bs-toggle="dropdown"class="btn btn-sm btn-default text-decoration-none">' .
                'Action&nbsp<i class="fa fa-caret-down" aria-hidden="true"></i></a><div class="dropdown-menu bg-white rounded-0">' .
                '<a class="dropdown-item font-weight-400 small_font border-bottom" onclick="view_purchase_func(' . $value->id . ')">' .
                '<i class="fa fa-file-text-o pe-3" aria-hidden="true"></i>' .
                'Purchase Details' .
                '</a>' .
                '<a onclick="delete_purchase_func(' . $value->id . ')" class="dropdown-item font-weight-400 small_font mb-0 ">' .
                '<i class="fa fa-trash-o pe-3" aria-hidden="true"></i>Discontinue Purchase</a>' .
                '</div>' .
                '</div>';

            $total_paid = $value->total - $payment_balance;

            $tableData[] = [
                ++$key,
                date('d-m-Y', strtotime($value->created_at)),
                empty($value->po_ref) ? '-' : $value->po_ref,
                $purchase_type,
                env('CURRANCY') . ' ' . number_format($value->total, 2),
                $actions

            ];
        }

        return $tableData;
    }

    public function viewPurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        return (new grn)->viewPurchase($request->id);
    }
}
