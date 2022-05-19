<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function index()
    {
        return view('/back_end/customer');
    }

    public function customerList()
    {

        $records = (new Customer)->getCustomerList(Auth::user()->id);

        $tableData = [];

        foreach ($records as $key => $customer) {

            switch ($customer->status) {
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
                '<a class="dropdown-item font-weight-400 small_font" onclick="update_customer_func_view(' . $customer->id . ')">' .
                '<i class="fa fa-pencil-square-o pe-3" aria-hidden="true"></i>' .
                'Update' .
                '</a>' .
                (($customer->status == 1) ?
                    '<a onclick="change_customer_status_func(' . $customer->id . ',2)" class="dropdown-item font-weight-400 small_font">' .
                    '<i class="fa fa-ban pe-3" aria-hidden="true"></i>' .
                    'Disable' .
                    '</a>
                    ' : '
                    <a onclick="change_customer_status_func(' . $customer->id . ',1)" class="dropdown-item font-weight-400 small_font">' .
                    '<i class="fa fa-check pe-3" aria-hidden="true"></i>' .
                    'Enable' .
                    '</a>
                    ') .
                '</div></div>';

            $tableData[] = [
                ++$key,
                $actions,
                $customer->name,
                ((empty($customer->company_name)) ? '-' : $customer->company_name),
                $customer->street_address . ' ' . $customer->city,
                $customer->tel1,
                ((empty($customer->bank_details)) ? '-' : $customer->bank_details),
                $status,
            ];
        }

        return $tableData;
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_customer_name' => 'required',
            'new_customer_city' => 'required',
            'new_customer_street_address' => 'required',
            'new_customer_tel1' => 'required|numeric|digits:10|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $data = [
            'name' => $request->new_customer_name,
            'company_name' => $request->new_customer_company_name,
            'company_regis' => $request->new_customer_registration_number,
            'street_address' => $request->new_customer_street_address,
            'city' => $request->new_customer_city,
            'tel1' => $request->new_customer_tel1,
            'tel2' => $request->new_customer_tel2,
            'email' => $request->new_customer_email,
            'bank_details' => $request->new_customer_bank_details,
            'admin_id' => Auth::user()->id,
            'status' => 1,
        ];

        (new Customer)->add($data);

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => 'Successfully saved customer',
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }

    public function customerViewForUpdate(Request $request)
    {
        $customer  = Customer::find($request->id);
        Session::put('customerForUpdate', $customer);
        return $customer;
    }

    public function updateCustomer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'new_customer_name' => 'required',
            'new_customer_city' => 'required',
            'new_customer_street_address' => 'required',
            'new_customer_tel1' => 'required|numeric|digits:10|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $data = [
            'company_name' => $request->new_customer_company_name,
            'company_regis' => $request->new_customer_registration_number,
            'street_address' => $request->new_customer_street_address,
            'city' => $request->new_customer_city,
            'tel1' => $request->new_customer_tel1,
            'tel2' => $request->new_customer_tel2,
            'email' => $request->new_customer_email,
            'bank_details' => $request->new_customer_bank_details,
            'status' => 1,
        ];

        (new Customer)->edit('id', Session::get('customerForUpdate')->id, $data);
        Session::forget('customerForUpdate');

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

        (new Customer)->edit('id', $request->id, ['status' => $request->status]);

        (($request->status == 1) ? $response_description = 'Customer Enabled Successfully' : $response_description = 'Customer Disabled Successfully');

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => $response_description,
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }

    public function customerSuggestions(Request $request)
    {
        $data = array();

        foreach ((new Customer)->suggetions($request->all()) as $customer) {
            $data[] = [
                'id' => $customer->id,
                'name' => $customer->name,
            ];
        }
        return response()->json($data, 200);
    }

    public function getCustomerAddressById(Request $request)
    {
        return (new Customer)->where('id', $request->id)->first()->street_address;
    }
}
