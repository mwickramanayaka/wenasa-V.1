@extends('back_end.layout.app')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin"
                            class="small_font font-weight-400  theme_font_color">Dashboard</a></li>
                    <li class="breadcrumb-item active small_font font-weight-400">Invoices</li>
                </ul>
            </div>
        </div>

        <div class="row">

            <div class="card rounded-0 shadow-none">

                <div class="card-header d-flex align-items-center rounded-0" style="margin-left:-8px ; margin-right:-8px">
                    <span class="flex-grow-1 font-weight-600"><i class="fa fa-star pe-2" aria-hidden="true"></i>
                        View Invoices
                    </span>
                    <a href="#" class="text-muted text-decoration-none fs-12px">
                        <i class="fa fa-fw fa-redo"></i>
                    </a>
                </div>

                <div class="px-3 py-3"
                    style="border-bottom: 1px solid #eee ; padding-right: 0px ; margin-left:-8px ; margin-right:-8px">
                    <div class="row">
                        <span>
                            Please use the table below to navigate or filter the results. You can download the table as
                            excel and pdf.
                        </span>
                    </div>
                </div>

                <div class="row">

                    <div class="mt-3 mb-3">

                        <div class="table-responsive">

                            <table id="administration_wise_invoices"
                                class="table table-borderless table-striped text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th></th>
                                        <th>Invoice Code</th>
                                        <th>Date</th>
                                        <th>Merchant</th>
                                        <th>Payment Type</th>
                                        <th>Gross Total</th>
                                        <th>Discount</th>
                                        <th>VAT %</th>
                                        <th>Service Charges</th>
                                        <th>Staff</th>
                                        <th>Payment Type Value</th>
                                        <th>Net Total</th>
                                    </tr>

                                    {{-- <tr>
                                        <th style="vertical-align: middle" class="text-center">Action</th>
                                        <th>
                                            <select id="invoice_list_status_option" class="form-select"
                                                style="width: 100px"
                                                onchange="(function(){administration_wise_invoice_search_func()})()">
                                                @foreach ($invoiceStatusList as $status)
                                                    <option value="{{ $status['id'] }}">{{ $status['text'] }}</option>
                                                @endforeach

                                            </select>
                                        </th>
                                        <th>
                                            <input id="invoice_list_code_option" type="text" class="form-control"
                                                placeholder="Invoice Code" style="width: 150px"
                                                onchange="(function(){administration_wise_invoice_search_func()})()">
                                        </th>
                                        <th>
                                            <div class="d-flex">
                                                <div class="me-1">
                                                    <input id="invoice_list_start_date_option" type="date"
                                                        class="form-control" style="width: 150px"
                                                        onchange="(function(){administration_wise_invoice_search_func()})()">
                                                </div>
                                                <span class="mt-2">to</span>
                                                <div class="ms-1">
                                                    <input id="invoice_list_end_date_option" type="date"
                                                        class="form-control" style="width: 150px"
                                                        onchange="(function(){administration_wise_invoice_search_func()})()">
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <select id="invoice_list_billing_to_option" class="form-select"
                                                style="width: 150px"
                                                onchange="(function(){administration_wise_invoice_search_func()})()">
                                                <option value="0">Booking Client</option>
                                                <option value="0">Atara Lagoon</option>
                                                <option value="0">Booking .lk</option>
                                            </select>
                                        </th>
                                        <th>
                                            <select id="invoice_list_payment_type_option" class="form-select"
                                                style="width: 150px"
                                                onchange="(function(){administration_wise_invoice_search_func()})()">
                                                <option value="0">Payment Type</option>
                                                @foreach ($paymentTypeList as $payment_type)
                                                    <option value="{{ $payment_type->id }}">{{ $payment_type->method }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <input id="invoice_list_referance_option" type="text" class="form-control"
                                                placeholder="Referance" style="width: 100px"
                                                onchange="(function(){administration_wise_invoice_search_func()})()">
                                        </th>
                                        <th style="vertical-align: middle">Gross Total</th>
                                        <th style="vertical-align: middle">Net Total</th>
                                        <th style="vertical-align: middle">Paid Amount</th>
                                        <th style="vertical-align: middle">Balance Amount</th>
                                        <th>
                                            <select id="invoice_list_admin_list_option" class="form-select"
                                                style="width: 150px"
                                                onchange="(function(){administration_wise_invoice_search_func()})()">
                                                <option value="0">Billing Admin</option>
                                                @foreach ($userList as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <input id="invoice_list_billing_address_option" type="text"
                                                class="form-control" placeholder="Customer Address"
                                                onchange="(function(){administration_wise_invoice_search_func()})()">
                                        </th>
                                    </tr> --}}

                                </thead>

                                <tbody></tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="view_invoice">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-0">
                <div class="card-header d-flex align-items-center">
                    <span class="flex-grow-1 font-weight-400">
                        <i class="fa fa-eye pe-2" aria-hidden="true"></i>Invoice Details
                    </span>
                    <button id="new_category_modal_close_btn" type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <div class="card rounded-0 border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="me-3">
                                            <i class="fa fa-3x fa-building padding010 theme_font_color"></i>
                                        </div>
                                        <div>
                                            <h5 id="view_invoice_invoice_code"></h5>
                                            <p id="view_billing_to" class="small_font">
                                            </p>
                                            <span id="view_invoice_billing_address" class="small_font"></span>
                                            <br>
                                            <span id="view_invoice_referance" class="small_font"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card rounded-0 border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="me-3">
                                            <i class="fa fa-3x fa-truck padding010 theme_font_color"></i>
                                        </div>
                                        <div>
                                            <h5 id="view_invoice_warehouse_name"></h5>
                                            <p id="view_invoice_warehouse_address" class="small_font"></p>
                                            <span id="view_invoice_warehouse_tel" class="small_font"></span>
                                            <br>
                                            <span id="view_invoice_warehouse_email" class="small_font"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card rounded-0 border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="me-3">
                                            <i class="fa fa-3x fa-file-text-o padding010 theme_font_color"></i>
                                        </div>
                                        <div>
                                            <h5 id="view_invoice_date"></h5>
                                            <span id="view_invoice_status" class="small_font"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="view_invoice_list" class="table table-bordered text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th class="w-50">Name</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>
                                        <th>VAT</th>
                                        <th>Net Total</th>
                                    </tr>
                                </thead>
                                <tbody id="view_invoice_table"></tbody>
                            </table>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <span>Created by: {{ Auth::user()->email }}</span>
                            <br>
                            <span>Date: 01/11/2021</span>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex d-flex-row">
                        <button id="view_inovice_print_btn" class="btn btn-default me-1">
                            <i class="fa fa-print me-1" aria-hidden="true"></i> Print
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_invoice">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-0">
                <div class="card-header d-flex align-items-center">
                    <span class="flex-grow-1 font-weight-400">
                        <i class="fa fa-eye pe-2" aria-hidden="true"></i>Invoice Productwise Return
                    </span>
                    <button id="new_category_modal_close_btn" type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <div class="card rounded-0 border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="me-3">
                                            <i class="fa fa-3x fa-building padding010 theme_font_color"></i>
                                        </div>
                                        <div>
                                            <h5 id="edit_invoice_invoice_code"></h5>
                                            <p id="edit_billing_to" class="small_font">
                                            </p>
                                            <span id="edit_invoice_billing_address" class="small_font"></span>
                                            <br>
                                            <span id="edit_invoice_referance" class="small_font"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card rounded-0 border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="me-3">
                                            <i class="fa fa-3x fa-truck padding010 theme_font_color"></i>
                                        </div>
                                        <div>
                                            <h5 id="edit_invoice_warehouse_name"></h5>
                                            <p id="edit_invoice_warehouse_address" class="small_font"></p>
                                            <span id="edit_invoice_warehouse_tel" class="small_font"></span>
                                            <br>
                                            <span id="edit_invoice_warehouse_email" class="small_font"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card rounded-0 border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="me-3">
                                            <i class="fa fa-3x fa-file-text-o padding010 theme_font_color"></i>
                                        </div>
                                        <div>
                                            <h5 id="edit_invoice_date"></h5>
                                            <span id="edit_invoice_status" class="small_font"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="edit_invoice_list" class="table table-bordered text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th class="w-50">Name</th>
                                        <th>Unit Price</th>
                                        <th>Recorded Qty</th>
                                        <th></th>
                                        <th>Sub Total</th>
                                        <th>VAT</th>
                                        <th>Net Total</th>
                                    </tr>
                                </thead>
                                <tbody id="edit_invoice_table"></tbody>
                            </table>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <span>Created by: {{ Auth::user()->email }}</span>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="d-flex d-flex-row" id="edit_inovice_save_btn"></div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="invoice_payment">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-0" style="height: 100%">
                <div class="card-header rounded-0 d-flex align-items-center">
                    <span class="flex-grow-1 font-weight-400">
                        <i class="fa fa-money pe-2" aria-hidden="true"></i>Payment Details
                    </span>
                    <button id="new_category_modal_close_btn" type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body p-0">

                    <div class="row">
                        <div class="table-responsive">
                            <table id="view_purchase_payment_list"
                                class="table table-bordered table-striped text-nowrap w-100"
                                style="margin-bottom: 0px ; padding-bottom: 0px">

                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Holder Name</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <th>1</th>
                                        <th>01/11/2021</th>
                                        <th>CAS057875</th>
                                        <th>Pasindu Priyashan</th>
                                        <th>Card</th>
                                        <th class="text-end">LKR 7,750,00</th>
                                    </tr>

                                    <tr>
                                        <th>2</th>
                                        <th>01/11/2021</th>
                                        <th>CAS057875</th>
                                        <th>Pasindu Priyashan</th>
                                        <th>Card</th>
                                        <th class="text-end">LKR 7,750,00</th>
                                    </tr>

                                    <tr>
                                        <th>3</th>
                                        <th>01/11/2021</th>
                                        <th>CAS057875</th>
                                        <th>Pasindu Priyashan</th>
                                        <th>Card</th>
                                        <th class="text-end">LKR 7,750,00</th>
                                    </tr>

                                    <tr>
                                        <th colspan="5" class="text-end font-weight-500">Total Paid Amount</th>
                                        <th class="text-end font-weight-500">LKR. 7,750.00</th>
                                    </tr>

                                    <tr>
                                        <th colspan="5" class="text-end font-weight-500">Balance Amount</th>
                                        <th class="text-end font-weight-500">LKR. 750.00</th>
                                    </tr>

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

                <div class="card-footer rounded-0 bs-gray-90">
                    <div class="d-flex d-flex-row">
                        <button class="btn btn-default me-1">
                            <i class="fa fa-print me-1" aria-hidden="true"></i> Print
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
