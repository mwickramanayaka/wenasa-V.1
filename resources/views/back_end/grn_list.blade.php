@extends('back_end.layout.app')

@section('content')

    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin"
                            class="small_font font-weight-400  theme_font_color">Dashboard</a></li>
                    <li class="breadcrumb-item active small_font font-weight-400">Purchases List</li>
                </ul>
            </div>
        </div>

        <div class="row">

            <div class="card rounded-0 shadow-none">

                <div class="card-header d-flex align-items-center rounded-0" style="margin-left:-8px ; margin-right:-8px">
                    <span class="flex-grow-1 font-weight-600"><i class="fa fa-star pe-2" aria-hidden="true"></i>
                        Purchasing List
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

                    <div class="mb-3">
                        <div class="table-responsive">
                            <br>
                            <table id="purchase_list" class="table table-borderless table-striped text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>PO Ref</th>
                                        <th>Purchase Status</th>
                                        <th>Grant Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>

                            </table>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="view_purchase">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-0">
                <div class="card-header d-flex align-items-center">
                    <span class="flex-grow-1 font-weight-400">
                        <i class="fa fa-eye pe-2" aria-hidden="true"></i>Purchase Details
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
                                            <h5 id="view_purchase_supplier_name"></h5>
                                            <p id="view_purchase_supplier_address" class="small_font">
                                            </p>
                                            <span id="view_purchase_supplier_tel" class="small_font"></span>
                                            <br>
                                            <span id="view_purchase_supplier_email" class="small_font"></span>
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
                                            <h5 id="view_purchase_warehouse_name"></h5>
                                            <p id="view_purchase_warehouse_address" class="small_font"></p>
                                            <span id="view_purchase_warehouse_tel" class="small_font"></span>
                                            <br>
                                            <span id="view_purchase_warehouse_email" class="small_font"></span>
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
                                            <h5 id="view_purchase_reference"></h5>
                                            <span id="view_purchase_date" class="small_font"></span>
                                            <br>
                                            <span id="view_purchase_status" class="small_font"></span>
                                            <br>
                                            <span id="view_purchase_payment_status" class="small_font"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="view_purchase_list" class="table table-bordered text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th class="w-50">Description</th>
                                        <th>Quantity</th>
                                        <th>Unit Cost</th>
                                        <th>Sub Total</th>
                                        <th>VAT</th>
                                        <th>Net Total</th>
                                    </tr>
                                </thead>
                                <tbody id="view_purchase_table"></tbody>
                            </table>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <span>Date: 01/11/2021</span>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex d-flex-row">
                        <button class="btn btn-default me-1">
                            <i class="fa fa-print me-1" aria-hidden="true"></i> Print
                        </button>
                        {{-- <button class="btn btn-default mx-1" onclick="view_purchase_payment_func()">
                            <i class="fa fa-money me-1" aria-hidden="true"></i> View Payment
                        </button> --}}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="view_purchase_payment" style="height: 100%">
        <div class="modal-dialog modal-xl" style="height: 100%">
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
                                        <td>1</td>
                                        <td>01/11/2021</td>
                                        <td>CAS057875</td>
                                        <td>Pasindu Priyashan</td>
                                        <td>Card</td>
                                        <td class="text-end">LKR 7,750,00</td>
                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>01/11/2021</td>
                                        <td>CAS057875</td>
                                        <td>Pasindu Priyashan</td>
                                        <td>Card</td>
                                        <td class="text-end">LKR 7,750,00</td>
                                    </tr>

                                    <tr>
                                        <td>3</td>
                                        <td>01/11/2021</td>
                                        <td>CAS057875</td>
                                        <td>Pasindu Priyashan</td>
                                        <td>Card</td>
                                        <td class="text-end">LKR 7,750,00</td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="text-end font-weight-500">Total Paid Amount</td>
                                        <td class="text-end font-weight-500">LKR. 7,750.00</td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="text-end font-weight-500">Balance Amount</td>
                                        <td class="text-end font-weight-500">LKR. 750.00</td>
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
                        {{-- <button class="btn btn-teal btn-sm mx-1" onclick="view_purchase_payment_func()">
                            <i class="fa fa-money me-1" aria-hidden="true"></i> View Payment
                        </button> --}}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_purchase">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-0">
                <div class="card-header d-flex align-items-center">
                    <span class="flex-grow-1 font-weight-400">
                        <i class="fa fa-eye pe-2" aria-hidden="true"></i>Edit Purchase
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
                                            <h5>Shenzen Magelei Electronic and Technology Co Ltd</h5>
                                            <p class="small_font">CN, Guandong, Floor B4, No : 7, Shichang Road Shenzen
                                            </p>
                                            <span class="small_font">Tel: 008618126122372</span>
                                            <br>
                                            <span class="small_font">Email: justin@magelei.com</span>
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
                                            <h5>Next Technologies</h5>
                                            <p class="small_font">
                                                Next Technologies
                                                29 C Athurugiriya Road Kottawa.
                                            </p>
                                            <span class="small_font">Tel: 008618126122372</span>
                                            <br>
                                            <span class="small_font">Email: justin@magelei.com</span>
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
                                            <h5>Reference: PO0013</h5>
                                            <span class="small_font">Date: 28/10/2021 14:29</span>
                                            <br>
                                            <span class="small_font">Status: Received</span>
                                            <br>
                                            <span class="small_font">Payment Status: Pending</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="view_purchase_list" class="table table-bordered text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th class="w-25">Description</th>
                                        <th>Order Qty</th>
                                        <th>Received Qty</th>
                                        <th>Unit Cost</th>
                                        <th>Sub Total</th>
                                        <th>VAT</th>
                                        <th>Net Total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td class="align-middle">1</td>
                                        <td class="align-middle">16320493 - Riser Card V009C-Plus
                                            PCIe Riser Card 009C-Plus
                                        </td>
                                        <td class="text-end align-middle">10 PCS</td>
                                        <td class="text-end align-middle">
                                            <input id="edit_purchase" name="new_grn_po_ref" type="text"
                                                class="form-control" />
                                        </td>
                                        <td class="text-end align-middle">LKR 750,00</td>
                                        <td class="text-end align-middle">LKR 1,750,00</td>
                                        <td class="text-end align-middle">
                                            <select id="edit_purchase" name="new_grn_warehouse" class="form-select">
                                                <option value="1">15%</option>
                                            </select>
                                        </td>
                                        <td class="text-end align-middle">LKR 7,900.00</td>
                                    </tr>

                                    <tr>
                                        <td colspan="7" class="text-end font-weight-500">Total Sub Amount</td>
                                        <td class="text-end font-weight-500">LKR 15,000.00</td>
                                    </tr>

                                    <tr>
                                        <td colspan="7" class="text-end font-weight-500 align-middle">VAT</td>
                                        <td class="text-end font-weight-500">
                                            <select id="edit_purchase" name="new_grn_warehouse" class="form-select">
                                                <option value="1">15%</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="7" class="text-end font-weight-500">Total Net Amount</td>
                                        <td class="text-end font-weight-500">LKR 16,500.00</td>
                                    </tr>

                                    <tr>
                                        <td colspan="7" class="text-end font-weight-500">Paid</td>
                                        <td class="text-end font-weight-500">LKR 15,000.00</td>
                                    </tr>

                                    <tr>
                                        <td colspan="7" class="text-end font-weight-500">Balance</td>
                                        <td class="text-end font-weight-500">LKR 1,500.00</td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <span>Created by: Thilanga Attanayake</span>
                            <br>
                            <span>Date: 01/11/2021</span>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex d-flex-row">
                        <button class="btn btn-default me-1">
                            <i class="fa fa-print me-1" aria-hidden="true"></i> Print
                        </button>
                        <button class="btn btn-default mx-1" onclick="view_purchase_payment_func()">
                            <i class="fa fa-money me-1" aria-hidden="true"></i> View Payment
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
