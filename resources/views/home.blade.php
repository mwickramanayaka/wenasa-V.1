<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <title>Front End</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link href="{{ asset('assets_back_end/css/notiflix.css') }}" rel="stylesheet" />

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">

        <div class="container d-flex justify-content-between">
            <div>
                <a class="navbar-brand" href="/">Abc</a>
            </div>
            <div>
                <button id="loginModal_btn" type="button" class="btn btn-primary" data-toggle="modal">Login</button>
                <button id="user_name" type="button" class="btn btn-info d-none"></button>
                <a id="post_register" class="btn btn-info d-none">View Order History</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4"
                src="https://s.tmimgcdn.com/scr/800x500/212900/spoon-and-fork-restaurant-logo_212966-original.png"
                style="width: 25%">

            <h2>Reservation Form</h2>

        </div>

        <div class="row">
            <div class="col-md-5 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your Order</span>
                </h4>
                <ul id="product_list" class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (LKR)</span>
                        <strong>0.00</strong>
                    </li>
                </ul>

            </div>

            <div class="col-md-7 order-md-1">
                <h4 class="mb-3">Billing address</h4>

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label for="firstName"> name</label>
                        <input type="text" class="form-control mt-1" id="home_firstName">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username">Email Address</label>
                    <div class="input-group mt-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>
                        <input type="text" class="form-control" id="home_email">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address">Contact Number</label>
                    <input type="text" class="form-control mt-1" id="home_tel">
                </div>

                <div class="mb-3">
                    <label for="address2">Address<span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control mt-1" id="home_address">
                </div>

                <br>
                <h4 class="mb-3">Reservation</h4>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Date</label>
                        <input id="home_date" type="date" class="form-control mt-1">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Room No</label>
                        <select id="home_room" class="form-select mt-1">
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }} : ({{ $room->type }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>
                <h4 class="mb-3">Select Products</h4>

                <div class="row mb-3">
                    <label class="mb-1">Select Products</label>
                    <div class="d-flex">

                        <div class="w-50 me-2">
                            <select name="home_Product" id="home_Product" class="form-select">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->lang1_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="me-2">
                            <input id="home_Qty" type="number" class="form-control" value="1">
                        </div>

                        <div class="me-2">
                            <button id="home_AddProduct_btn" class="btn btn-primary" onclick="addToSession()">
                                Add to Menu
                            </button>
                        </div>

                    </div>
                </div>

                <button class="btn btn-primary btn-lg btn-block mt-3" onclick="checkout()">Continue to checkout</button>

            </div>
        </div>

        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">© 2021-2022 Assignment</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">Privacy</a></li>
                <li class="list-inline-item"><a href="#">Terms</a></li>
                <li class="list-inline-item"><a href="#">Support</a></li>
            </ul>
        </footer>

    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                    <button type="button" class="btn btn-white" onclick="closeLoginModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <form method="POST">
                            @csrf

                            <div class="col-12 mt-2">
                                <label for="user_email">Email</label>
                            </div>

                            <div class="col-12 mt-2">
                                <input id="user_email" name="user_email" type="email" class="form-control"
                                    placeholder="example@gmail.com">
                            </div>

                            <div class="col-12 mt-3">
                                <label for="user_password">Password</label>
                            </div>

                            <div class="col-12 mt-2">
                                <input id="user_password" name="user_password" type="password" class="form-control"
                                    placeholder="******">
                            </div>

                            <div class="col-12 mt-3">
                                <button type="button" class="btn btn-warning" onclick="closeLoginModal()">Close</button>
                                <button type="button" class="btn btn-primary" onclick="login()">Login</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets_back_end/js/notiflix.js') }}"></script>
    <script src="{{ asset('assets_back_end/js/process/print.js') }}"></script>

    <script>
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function closeLoginModal() {
            $('#loginModal').modal('hide');
        }

        $('#loginModal_btn').click(function(e) {
            e.preventDefault();
            $('#loginModal').modal('toggle');
        });

        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "/CLEAR_SESSIONS",
                success: function(response) {
                    console.log(response);
                }
            });
        });

        function login() {

            $.ajax({
                type: "GET",
                url: "/LOGIN",
                data: {
                    email: $('#user_email').val(),
                    password: $('#user_password').val(),
                },
                success: function(response) {

                    if (response['color'] == 'danger') {
                        Notiflix.Notify.Failure(response['msg']);
                    } else {

                        if (response['data']['id'] != null) {
                            $('#loginModal_btn').addClass('d-none');
                            $('#user_name').removeClass('d-none');
                            $('#post_register').removeClass('d-none');
                            $('#user_name').html(response['data']['name']);

                            $('#home_firstName').val(response['data']['name']);
                            $('#home_tel').val(response['data']['tel']);
                            $('#home_email').val(response['data']['email']);
                            $('#home_address').val(response['data']['address']);
                        }

                        $('#loginModal').modal('hide');

                    }
                }
            });
        }

        function addToSession() {

            let product = $('#home_Product').val();
            let qty = $('#home_Qty').val();

            $.ajax({
                type: "GET",
                url: "/SAVE_PRODUCT_TO_SESSION",
                data: {
                    product_id: product,
                    qty: qty,
                },
                success: function(response) {

                    content = '';
                    content_total = '';
                    full_content = '';
                    total = 0;

                    $.each(response, function(key, item) {

                        total += item[4]

                        content +=
                            '<li class="list-group-item d-flex justify-content-between lh-condensed">' +
                            '<div>' +
                            '<h6 class="my-0">' + item[2] + '</h6>' +
                            '<small class="text-muted"> QTY : ' + item[1] +
                            ' AND UNIT PRICE : ' + numberWithCommas(item[3]) + '</small>' +
                            '</div>' +
                            '<span class="text-muted">' + numberWithCommas(item[4]) +
                            '</span>' +
                            '</li>';

                        content_total =
                            '<li class="list-group-item d-flex justify-content-between">' +
                            '<span>Total (LKR)</span>' +
                            '<strong>' + numberWithCommas(total) + '</strong>' +
                            '</li>';
                    });

                    full_content = content + content_total;

                    $('#product_list').html(full_content);

                }
            });
        };

        function checkout() {
            let name = $('#home_firstName').val();
            let email = $('#home_email').val();
            let tel = $('#home_tel').val();
            let address = $('#home_address').val();
            let date = $('#home_date').val();
            let room = $('#home_room').val();

            $.ajax({
                type: "GET",
                url: "/CHECKOUT",
                data: {
                    name: name,
                    email: email,
                    tel: tel,
                    address: address,
                    date: date,
                    room: room,
                },
                success: function(response) {

                    console.log(response);

                    if ($.isEmptyObject(response.error)) {
                        Notiflix.Loading.Remove();

                        if (response == 2) {
                            Notiflix.Notify.Failure('Please add foods');
                        } else if (response == 3) {
                            Notiflix.Notify.Failure('Room already booked');
                        } else if (response == 1) {
                            Notiflix.Notify.Success('Successfully saved');

                            $('#home_firstName').val('');
                            $('#home_firstName').val('');
                            $('#home_email').val('');
                            $('#home_tel').val('');
                            $('#home_address').val('');
                            $('#home_date').val('');
                            $('#home_room').val('');

                            content =
                                '<li class="list-group-item d-flex justify-content-between">' +
                                '<span>Total (LKR)</span>' +
                                '<strong> 0.00 </strong>' +
                                '</li>';

                            $('#product_list').html('');
                            $('#product_list').html(content);

                        }

                    } else {
                        $.each(response.error, function(key, value) {
                            Notiflix.Loading.Remove();
                            Notiflix.Notify.Failure(value);
                        });
                    }

                }
            });

        }
    </script>

</body>

</html>
