<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        return view('/back_end/supplier');
    }

    public function supplierSuggestions(Request $request)
    {
        $data = array();

        foreach ((new Supplier)->suggetions($request->all()) as $supplier) {
            $data[] = [
                'id' => $supplier->id,
                'name' => $supplier->name,
            ];
        }
        return response()->json($data, 200);
    }

    public function save(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'new_supplier_name' => 'required',
            'new_supplier_city' => 'required',
            'new_supplier_street_address' => 'required',
            'new_supplier_tel1' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $data = [
            'name' => $request->new_supplier_name,
            'company_name' => $request->new_supplier_company_name,
            'company_regis' => $request->new_supplier_registration_number,
            'street_address' => $request->new_supplier_street_address,
            'city' => $request->new_supplier_city,
            'tel1' => $request->new_supplier_tel1,
            'tel2' => $request->new_supplier_tel2,
            'email' => $request->new_supplier_email,
            'bank_details' => $request->new_supplier_bank_details,
            'status' => 1,
        ];

        (new Supplier)->add($data);

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => 'Successfully saved supplier',
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }

    public function supplierList()
    {

        $records = (new Supplier)->getSupplierList();

        $tableData = [];

        foreach ($records as $key => $supplier) {

            switch ($supplier->status) {
                case 1:
                    $statusText = 'Active';
                    $statusColor1 = 'success';
                    $statusColor2 = 'green';
                    break;

                case 2:
                    $statusText = 'In-active';
                    $statusColor1 = 'danger';
                    $statusColor2 = 'red';
                    break;

                default:
                    $statusText = '-';
                    $statusColor1 = 'default';
                    $statusColor2 = 'white';
                    break;
            }

            $status = '<span class="badge rounded-1 my-1 font-weight-500 bg-'
                . $statusColor2 .
                '-100 text-'
                . $statusColor1 .
                ' px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">
                <i class="fa fa-circle text-' . $statusColor1 . ' fs-9px fa-fw me-5px"></i>'
                . $statusText .
                '</span>';

            $actions = '<div class="mt-md-0 mt-2">' .
                '<a href="#" data-bs-toggle="dropdown"class="btn btn-sm btn-default text-decoration-none">' .
                '<i class="fa fa-bars" aria-hidden="true"></i></a><div class="dropdown-menu bg-white rounded-0">' .
                '<a class="dropdown-item font-weight-400 small_font" onclick="update_supplier_func_view(' . $supplier->id . ')">' .
                '<i class="fa fa-pencil-square-o pe-3" aria-hidden="true"></i>' .
                'Update' .
                '</a>'.
                (($supplier->status == 1) ?
                    '<a onclick="change_supplier_status_func(' . $supplier->id . ',2)" class="dropdown-item font-weight-400 small_font">' .
                    '<i class="fa fa-ban pe-3" aria-hidden="true"></i>' .
                    'Disable' .
                    '</a>
                    ' : '
                    <a onclick="change_supplier_status_func(' . $supplier->id . ',1)" class="dropdown-item font-weight-400 small_font">' .
                    '<i class="fa fa-check pe-3" aria-hidden="true"></i>' .
                    'Enable' .
                    '</a>
                    ') .
                '</div></div>';

            $tableData[] = [
                ++$key,
                $actions,
                $supplier->name,
                ((empty($supplier->company_name)) ? '-' : $supplier->company_name),
                $supplier->street_address . ' ' . $supplier->city,
                $supplier->tel1,
                ((empty($supplier->bank_details)) ? '-' : $supplier->bank_details),
                $status,
            ];
        }

        return $tableData;
    }

    public function supplierViewForUpdate(Request $request)
    {
        $supplier  = Supplier::find($request->id);
        Session::put('supplierForUpdate', $supplier);
        return $supplier;
    }

    public function updateSupplier(Request $request)
    {

        $data = [
            'company_name' => $request->new_supplier_company_name,
            'company_regis' => $request->new_supplier_registration_number,
            'street_address' => $request->new_supplier_street_address,
            'city' => $request->new_supplier_city,
            'tel1' => $request->new_supplier_tel1,
            'tel2' => $request->new_supplier_tel2,
            'email' => $request->new_supplier_email,
            'bank_details' => $request->new_supplier_bank_details,
            'status' => 1,
        ];

        (new Supplier)->edit('id', Session::get('supplierForUpdate')->id, $data);

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => 'Supplier Updated Successfully',
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }

    public function changeStatus(Request $request)
    {

        (new Supplier)->edit('id', $request->id, ['status' => $request->status]);

        (($request->status == 1) ? $response_description = 'Supplier Enabled Successfully' : $response_description = 'Supplier Disabled Successfully');

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => $response_description,
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }
}
