@extends('back_end.layout.app')

@section('content')

    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin"
                            class="small_font font-weight-400  theme_font_color">Dashboard </a></li>
                    <li class="breadcrumb-item active small_font font-weight-400">Product Registration</li>
                </ul>
            </div>
        </div>

        <style>
            .product_card_div {
                border-right: 1px solid #eee;
            }

        </style>
        <form method="POST" id="new_product_form" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="col-lg-12 px-0">
                    <div class="card shadow-none border-0 rounded-0">
                        <div class="card-header d-flex align-items-center rounded-0">
                            <span class="flex-grow-1 font-weight-600"><i class="fa fa-folder-open pe-2"
                                    aria-hidden="true"></i>Product Registration</span>
                            <a href="#" class="text-muted text-decoration-none fs-12px">
                                <i class="fa fa-fw fa-redo"></i>
                            </a>
                        </div>

                        <div class="px-3 py-3" style="border-bottom: 1px solid #eee">
                            <div class="row">
                                <span>
                                    Please fill in the information below. The field labels marked with * are required input
                                    fields.
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-3 col-md-6" style="padding: 0px">

                    <div class="card border-0 shadow-sm rounded-0 h-100">

                        <div class="card-header d-flex align-items-center rounded-0"
                            style="border-bottom: 3px solid #4caf50; background-color: #c5e1a5">
                            <span class="flex-grow-1 font-weight-600">PRIMARY DETAILS</span>
                        </div>

                        <div class="card-body">

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_sku">Product SKU
                                        <span class="text-danger">*</span></label>
                                    <input id="new_product_sku" name="new_product_sku" type="text" class="form-control"
                                        value="{{ $productCode }}" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_select_category">Select Product
                                        Category
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="new_product_select_category" name="new_product_select_category"
                                        class="form-select">
                                        @foreach ($category as $key => $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_name_lang1">Product Name (Main
                                        Language)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input id="new_product_name_lang1" name="new_product_name_lang1" type="text"
                                        class="form-control" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_name_lang2">Product Name (Sub
                                        Language
                                        1)</label>
                                    <input id="new_product_name_lang2" name="new_product_name_lang2" type="text"
                                        class="form-control" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_name_lang3">Product Name (Sub
                                        Language
                                        2)</label>
                                    <input id="new_product_name_lang3" name="new_product_name_lang3" type="text"
                                        class="form-control" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_select_product_type">Select
                                        Product
                                        Type
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="new_product_select_product_type" name="new_product_select_product_type"
                                        class="form-select">
                                        @foreach ($productType as $key => $productType)
                                            <option value="{{ $productType->id }}">
                                                {{ $productType->product_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_select_brand">Select Product
                                        Brand
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="new_product_select_brand" name="new_product_select_brand"
                                        class="form-select">
                                        @foreach ($brand as $key => $brand)
                                            <option value="{{ $brand->id }}">
                                                {{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6" style="padding: 0px">

                    <div class="card border-0 shadow-sm rounded-0 h-100">

                        <div class="card-header d-flex align-items-center rounded-0"
                            style="border-bottom: 3px solid #4caf50; background-color: #c5e1a5">
                            <span class="flex-grow-1 font-weight-600">SPECIFICATION DETAILS</span>
                        </div>

                        <div class="card-body">

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_des">Product
                                        Description</label>
                                    <textarea id="new_product_des" name="new_product_des" class="summernote"
                                        title="Contents"></textarea>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6" style="padding: 0px">

                    <div class="card border-0 shadow-sm rounded-0 h-100">

                        <div class="card-header d-flex align-items-center rounded-0"
                            style="border-bottom: 3px solid #4caf50; background-color: #c5e1a5">
                            <span class="flex-grow-1 font-weight-600">INVENTORY DETAILS</span>
                        </div>

                        <div class="card-body">

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_select_mes">Select Product
                                        Measurement
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="new_product_select_mes" name="new_product_select_mes"
                                        class="form-select">
                                        @foreach ($measurement as $key => $measurement)
                                            <option value="{{ $measurement->id }}">
                                                {{ $measurement->symbol }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_lsaq">Low Stock Alert
                                        Quantity
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input id="new_product_lsaq" name="new_product_lsaq" type="number"
                                        class="form-control" />
                                </div>

                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label small_font" for="new_product_default_price">Default
                                        Amount
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input id="new_product_default_price" name="new_product_default_price" type="number"
                                        class="form-control" />
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6" style="padding: 0px">

                    <div class="card border-0 shadow-sm rounded-0 h-100">

                        <div class="card-header d-flex align-items-center rounded-0"
                            style="border-bottom: 3px solid #4caf50; background-color: #c5e1a5">
                            <span class="flex-grow-1 font-weight-600">MEDIA DETAILS</span>
                        </div>

                        <div class="card-body">

                            <div class="d-flex d-flex-row">
                                <div class="custom-file">
                                    <div class="text-center">
                                        <img id="product_image1_upload_preview"
                                            src="{{ asset('assets_back_end/img/image_upload_icon.svg') }}" alt=""
                                            class="mb-2" style="width: 100px ; height: 100px">
                                        <label class="btn btn-sm btn-default w-75" for="product_image1_upload">Add
                                            Image</label>
                                        <input type="file" id="product_image1_upload" name="product_image1_upload">
                                    </div>
                                </div>

                                <div class="custom-file">
                                    <div class="text-center">
                                        <img id="product_image2_upload_preview"
                                            src="{{ asset('assets_back_end/img/image_upload_icon.svg') }}" alt=""
                                            class="mb-2" style="width: 100px ; height: 100px">
                                        <label class="btn btn-sm btn-default w-75" for="product_image2_upload">Add
                                            Image</label>
                                        <input type="file" id="product_image2_upload" name="product_image2_upload">
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-12 px-0">
                    <div class="card shadow-sm border-0 rounded-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-3"></div>
                                <div class="col-lg-3"></div>
                                <div class="col-lg-3">
                                    <div class="d-flex d-flex-row justify-content-center">
                                        <div class="me-2 w-100">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fa fa-fw fa-upload"></i>
                                                Save
                                            </button>
                                        </div>

                                        <div class="me-2 w-100">
                                            <button id="product_insert_form_reset" class="btn btn-default w-100">
                                                <i class="fa fa-fw fa-trash"></i>
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </form>

    </div>

@endsection
