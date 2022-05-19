@extends('back_end.layout.app')

@section('content')

    <div id="content" class="app-content">

        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin"
                            class="small_font font-weight-400  theme_font_color">Dashboard </a></li>
                    <li class="breadcrumb-item active small_font font-weight-400">Product List</li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-3 rounded-0">

                    <div class="card-header d-flex align-items-center rounded-0 ">
                        <span class="flex-grow-1 font-weight-600"><i class="fa fa-folder-open pe-2"
                                aria-hidden="true"></i>Registered Product Directory</span>
                        <a href="#" class="text-muted text-decoration-none fs-12px">
                            <i class="fa fa-fw fa-redo"></i>
                        </a>
                    </div>

                    <div class="px-3 py-3 d-flex align-items-center">
                        <span class="flex-grow-1">
                            Please use the table below to navigate or filter the results. You can download the table as
                            excel and pdf.
                        </span>
                        <a class="btn btn-default btn-sm" href="/admin/product">
                            <i class="fa fa-plus fa-fw"></i>
                            New
                        </a>
                    </div>

                    <div class="list-group list-group-flush p-3">

                        <div class="table-responsive">
                            <table id="product_list" class="table text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th>Actions</th>
                                        <th>Image</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Low Stock Value</th>
                                        <th>Sell Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($allproducts as $key => $product)

                                        <tr>
                                            <td class="align-middle">{{ $key + 1 }}</td>
                                            <td class="align-middle">
                                                <div class="mt-md-0 mt-2">
                                                    <a href="#" data-bs-toggle="dropdown"
                                                        class="btn btn-sm btn-default text-decoration-none">
                                                        <i class="fa fa-bars" aria-hidden="true"></i></a>
                                                    <div class="dropdown-menu bg-white rounded-0 pb-0">

                                                        <a onclick="product_quick_view({{ $product['id'] }})"
                                                            class="dropdown-item font-weight-400 small_font border-bottom mb-1">
                                                            <i class="fa fa-eye pe-3" aria-hidden="true"></i>Quick View
                                                        </a>
                                                        <a onclick="product_default_price_edit({{ $product['id'] }})"
                                                            class="dropdown-item font-weight-400 small_font border-bottom mb-1">
                                                            <i class="fa fa-money pe-3" aria-hidden="true"></i>Change
                                                            Default Price
                                                        </a>
                                                        <a onclick="product_category_edit({{ $product['id'] }})"
                                                            class="dropdown-item font-weight-400 small_font border-bottom mb-1">
                                                            <i class="fa fa-clone pe-3" aria-hidden="true"></i>Change
                                                            Category
                                                        </a>
                                                        <a onclick="product_quick_edit({{ $product['id'] }})"
                                                            class="dropdown-item font-weight-400 small_font border-bottom mb-1">
                                                            <i class="fa fa-pencil-square-o pe-3"
                                                                aria-hidden="true"></i>Edit
                                                            Description
                                                        </a>
                                                        @if ($product['status'] == 1)
                                                            <a onclick="change_product_status_func({{ $product['id'] }} , 2)"
                                                                class="dropdown-item font-weight-400 small_font">
                                                                <i class="fa fa-ban pe-3" aria-hidden="true"></i>Disable
                                                            </a>
                                                        @else
                                                            <a onclick="change_product_status_func({{ $product['id'] }} , 1 )"
                                                                class="dropdown-item font-weight-400 small_font">
                                                                <i class="fa fa-check pe-3" aria-hidden="true"></i>Enable
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <img src="{{ asset('assets_front_end/image/products/' . $product['getProductImage'][0]['getMedia']['name'] . '') }}"
                                                    style="width:50px ; height:50px">
                                            </td>
                                            <td class="align-middle">{{ $product['code'] }}</td>
                                            <td class="align-middle">{{ $product['lang1_name'] }}</td>
                                            <td class="align-middle">{{ $product['getProductCategory']['name'] }}</td>
                                            <td class="align-middle">{{ $product['getBrand']['name'] }}</td>
                                            <td class="align-middle">{{ $product['low_stock_alert_qty'] }}</td>
                                            <td class="align-middle">
                                                {{ env('CURRANCY') . ' ' . number_format($product['default_price'], 2) }}
                                            </td>
                                            <td class="align-middle">

                                                @if ($product['status'] == 1)
                                                    <span
                                                        class="badge rounded-1 my-1 font-weight-500 bg-success-transparent-2 text-success px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">
                                                        <i
                                                            class="fa fa-circle text-success-transparent-8 fs-9px fa-fw me-5px"></i>
                                                        Active
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge rounded-1 my-1 font-weight-500 bg-danger-transparent-2 text-danger px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">
                                                        <i
                                                            class="fa fa-circle text-danger-transparent-8 fs-9px fa-fw me-5px"></i>
                                                        In-active
                                                    </span>
                                                @endif


                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="product_view_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="product_view_code_name" class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <div class="row">
                                <div class="col-12">
                                    <img id="product_view_img1" class="w-100 p-4"
                                        src="{{ asset('assets_front_end/image/products/left_pulses_bg_2.jpeg') }}"
                                        alt="">
                                </div>
                                <div class="col-12">
                                    <img id="product_view_img2" class="w-100 p-4"
                                        src="{{ asset('assets_front_end/image/products/829166_2.jpeg') }}" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div id="product_view_content">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="product_edit_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="product_update_code_name" class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="update_product_form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="update_product_des">Product Description</label>
                                    <textarea id="update_product_des" name="update_product_des" class="summernote"
                                        title="Contents">
                                                                                                </textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <button id="quickEdit_update" class="btn btn-primary">Save & Update</button>
                    <button class="btn btn-default" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="product_category_edit_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="edit_product_category_select_name" class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="edit_product_select_category">Select Product Category</label>
                                <select id="edit_product_select_category" name="edit_product_select_category"
                                    class="form-select">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button id="edit_category_update_btn" class="btn btn-primary">Update</button>
                    <button class="btn btn-default" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection
