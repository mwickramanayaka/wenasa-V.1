@extends('back_end.layout.app')

@section('content')

    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin" class="small_font font-weight-400  theme_font_color">Dashboard </a></li>
                    <li class="breadcrumb-item active small_font font-weight-400">Supplier Management</li>
                </ul>
            </div>
        </div>

        <div class="row">

            <div class="col-xl-12">

                <div class="card mb-3 rounded-0">

                    <div class="card-header d-flex align-items-center rounded-0">

                        <span class="flex-grow-1 font-weight-600"><i class="fa fa-folder-open pe-2"
                                aria-hidden="true"></i>Registered Supplier Directory</span>
                        <a href="#" class="text-muted text-decoration-none fs-12px">
                            <i class="fa fa-fw fa-redo"></i>
                        </a>

                    </div>

                    <div class="px-3 py-3 d-flex align-items-center">
                        <span class="flex-grow-1">
                            Please use the table below to navigate or filter the results. You can download the table as
                            excel and pdf.
                        </span>
                        <a id="new_category_modal_link" class="btn btn-default btn-sm" data-bs-toggle="modal"
                            data-bs-target="#new_supplier_modal">
                            <i class="fa fa-plus fa-fw"></i>
                            New
                        </a>
                    </div>

                    <div class="list-group list-group-flush p-3">

                        <div id="datatable">

                            <table id="supplier_list" class="table text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th>Actions</th>
                                        <th>Name</th>
                                        <th>Company Name</th>
                                        <th>Address</th>
                                        <th>Contact #</th>
                                        <th>Bank Details</th>
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

    <div class="modal fade" id="new_supplier_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Supplier</h5>
                    <button id="new_supplier_modal_close_btn" type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="new_supplier">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label class="form-label small_font" for="new_supplier_name">Supplier Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_supplier_name" name="new_supplier_name" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label small_font" for="new_supplier_company_name">Company Name</label>
                            <input type="text" class="form-control" id="new_supplier_company_name"
                                name="new_supplier_company_name" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label small_font" for="new_supplier_registration_number">Registration Number</label>
                            <input type="text" class="form-control" id="new_supplier_registration_number"
                                name="new_supplier_registration_number" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label small_font" for="new_supplier_street_address">Street Address <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_supplier_street_address"
                                name="new_supplier_street_address" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label small_font" for="new_supplier_city">City <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_supplier_city" name="new_supplier_city" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label small_font" for="new_supplier_tel1">Telephone (Primary) <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="new_supplier_tel1" name="new_supplier_tel1" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label small_font" for="new_supplier_tel2">Telephone (Secondary)</label>
                            <input type="text" class="form-control" id="new_supplier_tel2" name="new_supplier_tel2" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label small_font" for="new_supplier_email">Email</label>
                            <input type="email" class="form-control" id="new_supplier_email" name="new_supplier_email" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label small_font" for="new_supplier_bank_details">Bank Details</label>
                            <textarea id="new_supplier_bank_details" name="new_supplier_bank_details" class="form-control"
                                rows="3"></textarea>
                        </div>


                    </div>

                    <div class="card-footer">
                        <div class="d-flex flex-row-reverse">
                            <button id="new_supplier_save_btn" class="btn btn-primary ">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                Save
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_supplier_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Supplier</h5>
                    <button id="new_supplier_modal_close_btn" type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="update_supplier_form">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label class="form-label" for="update_supplier_name">Supplier Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="update_supplier_name" name="new_supplier_name"
                                readonly />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="update_supplier_company_name">Company Name</label>
                            <input type="text" class="form-control" id="update_supplier_company_name"
                                name="new_supplier_company_name" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="update_supplier_registration_number">Registration
                                Number</label>
                            <input type="text" class="form-control" id="update_supplier_registration_number"
                                name="new_supplier_registration_number" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="update_supplier_street_address">Street Address <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="update_supplier_street_address"
                                name="new_supplier_street_address" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="update_supplier_city">City <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="update_supplier_city" name="new_supplier_city" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="update_supplier_tel1">Telephone (Primary) <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="update_supplier_tel1" name="new_supplier_tel1" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="update_supplier_tel2">Telephone (Secondary)</label>
                            <input type="text" class="form-control" id="update_supplier_tel2" name="new_supplier_tel2" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="update_supplier_email">Email</label>
                            <input type="email" class="form-control" id="update_supplier_email"
                                name="new_supplier_email" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="update_supplier_bank_details">Bank Details</label>
                            <textarea id="update_supplier_bank_details" name="new_supplier_bank_details"
                                class="form-control" rows="3"></textarea>
                        </div>


                    </div>

                    <div class="card-footer">
                        <div class="d-flex flex-row-reverse">
                            <button id="update_supplier_save_btn" class="btn btn-primary ">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                Update
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
