@extends('back_end.layout.app')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin"
                            class="small_font font-weight-400  theme_font_color">Dashboard </a></li>
                    <li class="breadcrumb-item active small_font font-weight-400">Supplier Management</li>
                </ul>
            </div>
        </div>

        <div class="row">

            <div class="col-xl-12">

                <div class="card mb-3 rounded-0">

                    <div class="card-header d-flex align-items-center rounded-0">

                        <span class="flex-grow-1 font-weight-600"><i class="fa fa-folder-open pe-2"
                                aria-hidden="true"></i>Reservation List</span>
                        <a href="#" class="text-muted text-decoration-none fs-12px">
                            <i class="fa fa-fw fa-redo"></i>
                        </a>

                    </div>

                    <div class="px-3 py-3 d-flex align-items-center">
                        <span class="flex-grow-1">
                            Please use the table below to navigate or filter the results. You can download the table as
                            excel and pdf.
                        </span>
                    </div>

                    <div class="list-group list-group-flush p-3">

                        <div id="datatable">

                            <table id="reservation_list" class="table text-nowrap w-100">
                                <thead class="display-heading">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone Number</th>
                                        <th>Room</th>
                                        <th>Date</th>
                                        <th>Item List</th>
                                        <th>Total Amount</th>
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
@endsection
