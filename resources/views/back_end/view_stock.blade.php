@extends('back_end.layout.app')

@section('content')

    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin"
                            class="small_font font-weight-400  theme_font_color">Dashboard</a></li>
                    <li class="breadcrumb-item active small_font font-weight-400">View Stock</li>
                </ul>
            </div>
        </div>

        <div class="row">

            <div class="card rounded-0 shadow-none">

                <div class="card-header d-flex align-items-center rounded-0" style="margin-left:-8px ; margin-right:-8px">
                    <span class="flex-grow-1 font-weight-600"><i class="fa fa-star pe-2" aria-hidden="true"></i>
                        View Stock
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

                        <ul class="nav nav-tabs">
                            <li class="nav-item me-1">
                                <a href="#all_stock" class="nav-link active" data-bs-toggle="tab">All Stock</a>
                            </li>
                            <li class="nav-item me-1"><a href="#productwise_stock" class="nav-link"
                                    data-bs-toggle="tab">Product Wise Stock</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3">
                            <div class="tab-pane fade show active" id="all_stock">

                                <div class="table-responsive">
                                    <br>
                                    <table id="all_stock_list"
                                        class="table table-borderless table-striped text-nowrap w-100">
                                        <thead class="display-heading">
                                            <tr>
                                                <th>#</th>
                                                <th>Stock Code</th>
                                                <th>Warehouse</th>
                                                <th>Product Name</th>
                                                <th>In-Price</th>
                                                <th>Available Qty</th>
                                                <th>VAT</th>
                                                <th>Net In-Price</th>
                                                <th>Out-Price</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody></tbody>

                                    </table>

                                </div>

                            </div>

                            <div class="tab-pane fade" id="productwise_stock">

                                <div class="table-responsive">
                                    <br>
                                    <table id="product-wise_stock"
                                        class="table table-borderless table-striped text-nowrap w-100">
                                        <thead class="display-heading">
                                            <tr>
                                                <th>#</th>
                                                <th>Product Code</th>
                                                <th>Product Name</th>
                                                <th>Low Qty Alert Qty</th>
                                                <th>Available Qty</th>
                                                <th>Status</th>
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
        </div>
    </div>

@endsection
