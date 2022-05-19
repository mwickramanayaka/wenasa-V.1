<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ASSIGNMENT</title>

    <!-- CSS files -->
    <link href="{{ asset('assets_back_end/css/vendor.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets_back_end/css/app.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets_back_end/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('assets_back_end/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" />
    <link
        href="{{ asset('assets_back_end/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets_back_end/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets_back_end/plugins/select-picker/dist/picker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets_back_end/plugins/summernote/dist/summernote-lite.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets_back_end/plugins/tag-it/css/jquery.tagit.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets_back_end/plugins/blueimp-file-upload/css/jquery.fileupload.css') }}"
        rel="stylesheet" />

    <link href="{{ asset('assets_back_end/css/notiflix.css') }}" rel="stylesheet" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap');

        .bg-green-custom-100 {
            background-color: #c8e6c9;
        }

        .header_new_text {
            font-family: 'Ubuntu', sans-serif;
        }

        .seldisable {
            pointer-events: none;
            background-color: #DAE0EC;
        }

        .custom-file input[type='file'] {
            display: none;
        }

        .coverimg {
            /* background-image: url('{{ asset('assets_back_end/img/bg/bg.svg') }}'); */
            background-repeat: no-repeat, no-repeat;
            background-size: cover;
        }

        .theme_font_color {
            /* background-color: #4caf50; */
            color: #4caf50;
        }

        .theme_bg_color_dark {
            background-color: #4caf50;
        }

        .theme_bg_color_light {
            background-color: #c5e1a5;
        }

        .small_font {
            font-size: 13px;
        }

        .display-heading th {
            background-color: #99e4ee;
            border-top: 3px solid #1769aa;
            font-weight: 400;
            font-size: 13px;
            /* color: #fff; */
        }

        td {
            font-size: 13px;
            /* font-weight: 400; */
        }

        .table>:not(:last-child)>:last-child>* {
            border-bottom-color: #fff;
        }

        .dataTables_wrapper.dt-bootstrap4 .table thead tr th.sorting:before {
            content: '\f0dc';
            color: #212121;
            opacity: 1;
        }

        .display-heading th:first-child {
            border-top-left-radius: 0px;
        }

        .display-heading th:last-child {
            border-top-right-radius: 0px;
        }

        td {
            border-bottom: 1px solid #ddd;
        }

        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            /* color: #4d6593; */
            background-color: #fff;
            border-color: #c9d2e3 #c9d2e3 #ebeef4;
            border-radius: 0px;
            border-top: 3px solid #4caf50;
            color: #212121;
        }

        .nav-tabs .nav-link {
            color: grey;
            margin-bottom: -1px;
            background: 0 0;
            border: 1px solid transparent;
            border-bottom: 0px solid transparent;
            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
        }

    </style>

</head>

<body class="coverimg">
    <div>
        <main>
            <div id="app" class="app">

                <div id="header" class="app-header bg-dark">

                    <div class="mobile-toggler">
                        <button type="button" class="menu-toggler" data-toggle="sidebar-mobile">
                            <span class="bar"></span>
                            <span class="bar"></span>
                        </button>
                    </div>

                    <div class="brand">
                        <div class="desktop-toggler">
                            <button type="button" class="menu-toggler" data-toggle="sidebar-minify">
                                <span class="bar"></span>
                                <span class="bar"></span>
                            </button>
                        </div>
                        <a href="#" class="brand-logo pt-3 pb-3"></a>
                    </div>

                    <div class="menu justify-content-end">

                        <div class="menu-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                                <div class="menu-img online">
                                    <img src="{{ asset('assets_back_end/img/user.png') }}" alt=""
                                        class="mw-100 mh-100 rounded-circle" />
                                </div>
                                <div class="menu-text text-white">
                                    <span class="" data-cfemail="">{{ Auth::user()->name }}</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end me-lg-3">
                                <a class="dropdown-item d-flex align-items-center" href="/logout">Log Out
                                    <i class="fa fa-toggle-off fa-fw ms-auto text-gray-400 fs-16px"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="sidebar" class="app-sidebar bg-white">

                    <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">

                        <div class="menu">

                            <div class="menu-item">
                                <a href="/admin/product/category" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">Category Registration</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="/admin/product" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">Product Registration</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="/admin/grn" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">Add GRN</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="/admin/customer" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">Customer Registration</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="/admin/invoice" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">Add Invoice</span>
                                </a>
                            </div>

                            <br>

                            <div class="menu-item">
                                <a href="/admin/product_list" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">Product List</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="/admin/grn_list" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">GRN List</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="/admin/stock" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">Stock List</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="/admin/invoice_list" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">Invoice List</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="/admin/viewReservationList" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa fa-angle-right pe-2"></i>
                                    </span>
                                    <span class="menu-text">Reservation List</span>
                                </a>
                            </div>

                        </div>

                    </div>

                    <button class="app-sidebar-mobile-backdrop" data-dismiss="sidebar-mobile"></button>
                </div>

                @yield('content')
                <a href="#" data-click="scroll-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>

            </div>
        </main>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets_back_end/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/js/app.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js">
    </script>
    <script src="{{ asset('assets_back_end/js/jquery.maskedinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets_back_end/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('assets_back_end/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('assets_back_end/plugins/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/js/demo/dashboard.demo.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/summernote/dist/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets_back_end/plugins/tag-it/js/tag-it.min.js') }}"></script>

    <script src="{{ asset('assets_back_end/js/notiflix.js') }}"></script>
    <script src="{{ asset('assets_back_end/js/process/print.js') }}"></script>
    <script src="{{ asset('assets_back_end/js/process/back_end_js.js') }}"></script>

    <script src="{{ asset('assets_back_end/js/rocket-loader.min.js') }}" data-cf-settings="0485eeef8cf3263d1a7b2548-|49"
        defer=""></script>

    <script>
        $('#datatableDefault').DataTable({
            dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
            lengthMenu: [10, 20, 30, 40, 50],
            responsive: true,
            buttons: [{
                    extend: 'print',
                    className: 'btn btn-default'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-default'
                }
            ]
        });

        $(document).ready(function() {
            $('#jquery-tagit').tagit({
                fieldName: 'tags',
                availableTags: ['c++', 'java', 'php', 'javascript', 'ruby', 'python', 'c'],
                autocomplete: {
                    delay: 0,
                    minLength: 2
                }
            });
        });
    </script>

</body>

</html>
