@extends('back_end.layout.app')

@section('content')

    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/admin/invoice" class="small_font font-weight-400  theme_font_color">Dashboard </a>
                    </li>
                    <li class="breadcrumb-item active small_font font-weight-400">Products / Categories</li>
                </ul>
            </div>
        </div>


        <div class="row">

            <div class="col-xl-12">

                <div class="card mb-3 rounded-0">

                    <div class="card-header d-flex align-items-center rounded-0 ">

                        <span class="flex-grow-1 font-weight-600">
                            <i class="fa fa-folder-open pe-2" aria-hidden="true"></i>Registered Category Directory
                        </span>
                        <a href="#" class="text-muted text-decoration-none fs-12px">
                            <i class="fa fa-fw fa-redo"></i>
                        </a>

                    </div>

                    <div class="px-3 py-3 d-flex align-items-center">
                        <span class="flex-grow-1">
                            Please use the table below to navigate or filter the results. You can download the table as
                            excel and pdf.
                        </span>
                        <a id="new_category_modal_link" class="btn btn-default btn-sm fs-12px"
                            data-bs-toggle="modal" data-bs-target="#new_category">
                            <i class="fa fa-plus fa-fw"></i>
                            New
                        </a>
                    </div>

                    <div class="list-group list-group-flush p-3">

                        <div id="datatable">

                            <table id="category_list" class="table text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th style="width: 30px">#</th>
                                        <th>Actions</th>
                                        <th>Image</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Parent Category</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new_category">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Category</h5>
                    <button id="new_category_modal_close_btn" type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label class="form-label small_font" for="new_category_code">Category Code</label>
                        <input type="text" class="form-control" id="new_category_code" value="{{ $categoryCode }}"
                            readonly />
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label small_font" for="new_category_name">Category Name</label>
                        <input type="text" class="form-control" id="new_category_name" />
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label small_font" for="new_category_sub">Select Sub Category</label>

                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input id="new_category_sub" name="new_category_sub" type="text" class="form-control"
                                placeholder="Type 'Category Name / Code' ">
                        </div>
                        <input type="hidden" id="new_category_sub_id" name="new_category_sub" />

                    </div>

                    <div class="card mb-3">

                        <div class="card-header d-flex align-items-center">
                            <span class="flex-grow-1 small_font font-weight-600">Upload Image</span>
                            <a href="#" class="text-muted text-decoration-none fs-12px">
                                <i class="fa fa-fw fa-redo"></i>
                            </a>
                        </div>

                        <div class="card-body">

                            <div class="d-flex d-flex-row justify-content-center">

                                <form method="post" id="category_image_form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="custom-file me-1">
                                        <label class="btn btn-primary btn-sm" for="category_image_upload">
                                            <i class="fa fa-fw fa-plus"></i>
                                            Add file...
                                        </label>
                                        <input type="file" id="category_image_upload" name="select_file">
                                        <button type="submit" form="category_image_form" class="btn btn-default btn-sm">
                                            <i class="fa fa-fw fa-upload"></i>
                                            Upload file
                                        </button>
                                    </div>
                                </form>

                                <div class="me-1">
                                    <button id="category_image_delete" class="btn btn-default btn-sm">
                                        <i class="fa fa-fw fa-trash"></i>
                                        Delete file
                                    </button>
                                </div>

                            </div>

                            <div>
                                <hr>
                                <div class="mt-3">
                                    <div id="category_image_preview_div" class="text-center">
                                        <div class="text-gray-300 mb-2">
                                            <i id="category_image_default" class="fa fa-file-archive fa-3x"></i>
                                        </div>
                                        <div>
                                            <span id="category_image_name_preview" class="small_font">No file uploaded</span>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div id="category_image_upload_loading" class="spinner-border text-primary d-none">
                                        </div>
                                        <img id="category_image_upload_preview" class="d-none"
                                            style="width:24px ; height: 24px;" />
                                        <span id="category_image_name_success_status" class="d-none text-success">
                                            <i class="fa fa-check" aria-hidden="true"></i>&nbsp;
                                            Successfully add Image
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex flex-row-reverse">
                        <button id="new_category_save_btn" class="btn btn-primary ">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Save
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
