@extends('back_end.layout.app')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin" class="small_font font-weight-400 theme_font_color">Dashboard
                        </a></li>
                    <li class="breadcrumb-item active small_font font-weight-400">Add POS Invoice</li>
                </ul>
            </div>
        </div>

        <div class="row">

            <div class="card rounded-0 shadow-none">

                <div class="card-header d-flex align-items-center rounded-0" style="margin-left:-8px ; margin-right:-8px">
                    <span class="flex-grow-1 font-weight-600"><i class="fa fa-barcode pe-2" aria-hidden="true"></i>Add
                        New Invoice</span>
                    <a href="#" class="text-muted text-decoration-none fs-12px">
                        <i class="fa fa-fw fa-redo"></i>
                    </a>
                </div>

                <div class="px-3 py-3"
                    style="border-bottom: 1px solid #eee ; padding-right: 0px ; margin-left:-8px ; margin-right:-8px">
                    <div class="row">
                        <span>
                            Please fill in the information below. The field labels marked with * are required input
                            fields.
                        </span>
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-3 col-md-4 col-12 px-0">

                        <div class="card rounded-0 shadow-sm border-0">

                            <div class="card-header d-flex align-items-center rounded-0">
                                <span class="flex-grow-1 font-weight-600"><i class="fa fa-barcode pe-2"
                                        aria-hidden="true"></i>Add Invoice Primary Details</span>
                                <a href="#" class="text-muted text-decoration-none fs-12px">
                                    <i class="fa fa-fw fa-redo"></i>
                                </a>
                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_code">Invoice Code</label>
                                            <input id="new_invoice_code" name="new_invoice_code" type="text"
                                                class="form-control" value="{{ $invoiceCode }}" readonly />
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-none">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_ref">Invoice Ref#</label>
                                            <input id="new_invoice_ref" name="new_invoice_ref" type="text"
                                                class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_currency_type">
                                                Currency</label>
                                            <select id="new_invoice_currency_type" name="new_invoice_currency_type"
                                                class="form-select">

                                                @foreach ($currency_type as $ct)
                                                    <option value="{{ $ct->id }}">{{ $ct->currency_type }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_types">
                                                Invoice Type
                                            </label>
                                            <select id="new_invoice_type" name="new_invoice_type" class="form-select">

                                                @foreach ($invoice_types as $invType)
                                                    <option value="{{ $invType['id'] }}">{{ $invType['type_name'] }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_billing_to_id">
                                                Select Client</label>
                                            <select id="new_invoice_billing_to_id" name="new_invoice_billing_to_id"
                                                class="form-select">

                                                @foreach ($merchants as $value)
                                                    <option value="{{ $value->id }}">{{ $value->merchant_name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-none">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_ref">Invoice Date</label>
                                            <input id="new_invoice_pay_done_date" name="new_invoice_pay_done_date"
                                                type="date" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_vat">Select Invoice
                                                VAT</label>
                                            <select id="new_invoice_vat" name="new_invoice_vat" class="form-select">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_service_charge">Service
                                                Charge</label>

                                            <select id="new_invoice_service_charge" name="new_invoice_service_charge"
                                                class="form-select">
                                                @foreach ($service_charges as $sc)
                                                    <option value="{{ $sc->id }}">
                                                        {{ $sc->service_charge_name }} ( {{ $sc->value }}% )
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_staff">
                                                Staff    
                                            </label>

                                            <select id="new_invoice_staff" name="new_invoice_staff"
                                                class="form-select">
                                                @foreach ($empObj as $emp)
                                                    <option value="{{ $emp->id }}">
                                                        {{ $emp->emp_name }} ( {{ $emp->emp_code }} )
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_discount">
                                                Discount (
                                                <small> <i>Please add Value NOT %</i></small> )
                                            </label>
                                            <input id="new_invoice_discount" name="new_invoice_discount" type="number"
                                                class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-none">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_billing_address">Billing
                                                Address</label>
                                            <textarea id="new_invoice_billing_address" name="new_invoice_billing_address"
                                                class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoice_remark">Remark</label>
                                            <textarea id="new_invoice_remark" name="new_invoice_remark"
                                                class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="card-footer bg-white px-0 py-0">
                            <div class="card-header d-flex align-items-center rounded-0" style="background-color: #a2cf6e">
                                <span class="flex-grow-1 font-weight-600">Total Payable Amount</span>
                            </div>

                            <div class="card-body" style="border-top: 3px solid #357a38; background-color: #c5e1a5">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h3 id="new_invoice_total_view" class="mb-1 font-weight-700">LKR. 0.00</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-9 col-md-8 col-12 px-0">
                        <div class="card rounded-0 shadow-sm border-0 h-100">

                            <div class="card-header d-flex align-items-center rounded-0">
                                <span class="flex-grow-1 font-weight-600"><i class="fa fa-folder-open pe-2"
                                        aria-hidden="true"></i>Add Invoicing Products</span>
                                <a href="#" class="text-muted text-decoration-none fs-12px">
                                    <i class="fa fa-fw fa-redo"></i>
                                </a>
                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoiceproduct_product">Select
                                                Product</label>

                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                                <input id="new_invoiceproduct_product" name="new_invoiceproduct_product"
                                                    type="text" class="form-control"
                                                    placeholder="Type 'Product Name / Code' ">
                                            </div>
                                            <input type="hidden" id="new_invoiceproduct_product_id"
                                                name="new_invoiceproduct_product_id" />

                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoiceproduct_unit_price">Unit
                                                Price</label>
                                            <input id="new_invoiceproduct_unit_price" name="new_invoiceproduct_unit_price"
                                                type="number" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoiceproduct_qty">Qty</label>
                                            <input id="new_invoiceproduct_qty" name="new_invoiceproduct_qty" type="number"
                                                class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-lg-1 col-md-6 d-none">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font"
                                                for="new_invoiceproduct_discount">Discount</label>
                                            <input id="new_invoiceproduct_discount" name="new_invoiceproduct_discount"
                                                type="number" class="form-control" value="0" />
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6 d-none">
                                        <div class="form-group mb-3">
                                            <label class="form-label small_font" for="new_invoiceproduct_vat">Product
                                                VAT</label>
                                            <select id="new_invoiceproduct_vat" name="new_invoiceproduct_vat"
                                                class="form-select">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label"
                                                for="new_invoiceproduct_save_btn">&nbsp;</label><br>
                                            <button id="new_invoiceproduct_save_btn" class="btn btn-primary w-100">Add to
                                                Invoice</button>
                                        </div>
                                    </div>

                                </div>

                                <div class="mb-3">
                                    <div class="table-responsive" style="margin-top: -5px;">

                                        <table id="invoice_save_product_list"
                                            class="table table-borderless table-striped text-nowrap w-100 dataTable no-footer">
                                            <thead class="display-heading">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Actions</th>
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Unit Price</th>
                                                    <th>Qty</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>

                            <div class="card-footer bg-white border-0">
                                <div class="d-flex flex-row-reverse">
                                    <button id="new_invoice_save_btn" class="btn btn-primary mb-2">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;
                                        Save Invoice
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="modal fade" id="grn_product_view_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="grn_product_view_code_name" class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <div class="row">
                                <div class="col-12">
                                    <img id="grn_product_view_img1" class="w-100 p-4"
                                        src="{{ asset('assets_front_end/image/products/left_pulses_bg_2.jpeg') }}"
                                        alt="">
                                </div>
                                <div class="col-12">
                                    <img id="grn_product_view_img2" class="w-100 p-4"
                                        src="{{ asset('assets_front_end/image/products/829166_2.jpeg') }}" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div id="grn_product_view_content">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
