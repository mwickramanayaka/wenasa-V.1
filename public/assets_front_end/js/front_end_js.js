Notiflix.Loading.Init({
    svgColor: "#ff5722",
    fontFamily: "Quicksand",
    useGoogleFont: true,
});

Notiflix.Confirm.Init({
    titleColor: '#212121',
    okButtonColor: '#f8f8f8',
    okButtonBackground: '#ff5722',
    cancelButtonColor: '#f8f8f8',
    cancelButtonBackground: '#a9a9a9',
    width: '300px',
    useGoogleFont: true,
    fontFamily: 'Quicksand',
});

$.ajaxSetup({
    beforeSend() {
        Notiflix.Loading.Pulse();
    },
    complete(status) {
        Notiflix.Loading.Remove();
    }
});

$('document').ready(function () {
    $('textarea').each(function () {
        $(this).val($(this).val().trim());
    });
});

function formatDate(dateMilli) {
    var d = (new Date(dateMilli) + '').split(' ');
    d[2] = d[2] + ',';
    return [d[0], d[1], d[2], d[3]].join(' ');
}

var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'LKR',
});

var baseUrl = window.location.origin;

var home_subscriptionMail = $('#home_subscriptionMail');
var home_subscriptionMail_btn = $('#home_subscriptionMail_btn');

home_subscriptionMail_btn.click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "GET",
        url: "/home/user/subscribeEmail",
        data: {
            email: home_subscriptionMail.val()
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {

            Notiflix.Loading.Remove();

            if ($.isEmptyObject(response.error)) {
                if (response['type'] == 'error') {
                    Notiflix.Notify.Failure(response['des']);
                } else if (response['type'] == 'success') {
                    Notiflix.Notify.Success(response['des']);
                    home_subscriptionMail.val('')
                }
            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Notify.Failure(value);
                });
            }
        }
    });
});

var home_featured_vegetables = $('#home_featured_vegetables');
var home_featured_groceries = $('#home_featured_groceries');
var home_featured_meats = $('#home_featured_meats');

$(document).ready(function () {
    getFeaturedProducts(15, home_featured_vegetables);
    getFeaturedProducts(18, home_featured_groceries);
    getFeaturedProducts(16, home_featured_meats);
});

function getFeaturedProducts(categoryId, elementId) {
    $.ajax({
        type: "GET",
        url: "/home/get/featured_products",
        data: {
            category_id: categoryId,
        },
        success: function (response) {

            // elementId.html('');

            $.each(response, function (key, value) {

                content = '<div class="items">' +
                    '<div class="tred-pro p-3">' +
                    '<div class="tr-pro-img">' +
                    '<a onclick="product_add_to_cart_view_func(' + value['id'] + ')">' +
                    '<img class="img-fluid cropped" src="' + baseUrl + "/assets_front_end/image/products/" + value['get_product_media'][0]['get_media']['name'] + '" alt="pro-img1">' +
                    '</a>' +
                    '</div>' +
                    '<div class="Pro-lable">' +
                    '<span class="p-text">New</span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="caption pt-0">' +
                    '<h3>' +
                    '<a>' + value['lang1_name'] + '</a>' +
                    '</h3>' +
                    '<div class="pro-price mt-1">' +
                    '<small class="mb-1">PER 1 ' + value['get_measurement']['symbol'] + '</small>' +
                    '<br>' +
                    '<span class="new-price">' + formatter.format(value['default_price']) + '</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                elementId.owlCarousel().trigger('add.owl.carousel', [jQuery(content)]).trigger('refresh.owl.carousel');

            });

        }
    });
}



// Need to Develop
// ==================================================
function view_product_by_category(id) {
    window.location.href = '/products/' + id + '';
}

var product_view_for_cart_modal = $('#product_view_for_cart_modal');
var product_view_for_cart_modalImage = $('#product_view_for_cart_modalImage');
var product_view_for_cart_modalTitle = $('#product_view_for_cart_modalTitle');
var product_view_for_cart_modalPrice = $('#product_view_for_cart_modalPrice');
var product_view_for_cart_modalDescription = $('#product_view_for_cart_modalDescription');
var product_view_for_cart_modalMeasurement = $('#product_view_for_cart_modalMeasurement');
var product_view_for_cart_modalQty = $('#product_view_for_cart_modalQty');
var product_view_for_cart_modalAddButton = $('#product_view_for_cart_modalAddButton');
var grm_kgm_image = $('#grm_kgm_image');

var unregis_customer_checkout_modal = $('#unregis_customer_checkout_modal');

function product_add_to_cart_view_func(id) {

    $.ajax({
        type: "GET",
        url: "/products/get/details",
        data: {
            id: id
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            if(response['get_measurement']['symbol'] == 'KGS'){
                grm_kgm_image.removeClass('grm_kgm_image');
            }else{
                grm_kgm_image.addClass('grm_kgm_image');
            }

            product_view_for_cart_modalImage.attr('src', baseUrl + "/assets_front_end/image/products/" + response['get_product_image'][0]['get_media']['name']);
            product_view_for_cart_modalTitle.html(response['lang1_name']);
            product_view_for_cart_modalPrice.html(formatter.format(response['default_price']));
            product_view_for_cart_modalMeasurement.html('1 ' + response['get_measurement']['symbol']);
            product_view_for_cart_modalAddButton.attr('onclick', 'product_add_to_cart_func(' + response['id'] + ')');
            product_view_for_cart_modalQty.val(1);

            ((response['description'] == null) ? product_view_for_cart_modalDescription.html('<small><i>Unable to find any description for this product</i></small>')
                :
                response['description'] == '<p><br></p>' ?
                    product_view_for_cart_modalDescription.html('<small><i>Unable to find any description for this product</i></small>')
                    :
                    product_view_for_cart_modalDescription.html(response['description'])
            );
            product_view_for_cart_modal.modal('toggle');
        }
    });
}

function product_add_to_cart_func(id) {

    $.ajax({
        type: "GET",
        url: "/products/session/addProductToCart",
        data: {
            id: id,
            qty: product_view_for_cart_modalQty.val(),
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            if ($.isEmptyObject(response.error)) {
                if (response['type'] == 'error') {
                    Notiflix.Notify.Failure(response['des']);
                } else if (response['type'] == 'success') {
                    Notiflix.Notify.Success('Successfully Added to Cart');
                    product_view_for_cart_modal.modal('hide');

                    process_cart_view();

                }
            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Notify.Failure(value);
                });
            }
        }
    });
}

var cart_div_total = $('#cart_div_total');
var cart_total = $('#cart_total');
var cart_item_list = $('#cart_item_list');
var cartList_view_btn = $('#cartList_view_btn');
var cart_sub_total = $('#cart_sub_total');

var unregis_customer_checkoutCity = $('#unregis_customer_checkoutCity');
var unregis_customer_checkoutCity_id = $('#unregis_customer_checkoutCity_id');
var unregis_customer_checkoutFirst_name = $('#unregis_customer_checkoutFirst_name');
var unregis_customer_checkoutLast_name = $('#unregis_customer_checkoutLast_name');
var unregis_customer_checkoutMobile_number = $('#unregis_customer_checkoutMobile_number');
var unregis_customer_checkoutEmail = $('#unregis_customer_checkoutEmail');
var unregis_customer_checkoutAddress = $('#unregis_customer_checkoutAddress');

var unregis_customer_order_save = $('#unregis_customer_order_save');
var unregis_customer_checkoutCity_select = $('#unregis_customer_checkoutCity_select');

var order_billing_summary_total = $('#order_billing_summary_total');
var order_billing_summary_vat = $('#order_billing_summary_vat');
var order_billing_summary_delivery_charges = $('#order_billing_summary_delivery_charges');
var order_billing_summary_sub_total = $('#order_billing_summary_sub_total');

var cart_menu_close = $('#cart_close_btn');

cartList_view_btn.click(function (e) {
    e.preventDefault();
    process_cart_view();
});

function process_cart_view() {

    $.ajax({
        type: "GET",
        url: "/products/session/cartView",
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            if (response != 2) {

                cart_item_list.html('');
                cart_div_total.html(response[1].length);
                cart_total.html(response[1].length);
                cart_sub_total.html(response[0]);

                for (let index = 0; index < response[1].length; index++) {
                    element = response[1][index];
                    cart_item_list.append('<li class="cart-item">'
                        + '<div class="cart-img">'
                        + '<a>'
                        + '      <img id="cart_div_img" src="' + baseUrl + "/assets_front_end/image/products/" + element[5] + '" alt="cart-image"'
                        + '          class="img-fluid">'
                        + '  </a>'
                        + '</div>'
                        + '<div class="cart-title">'
                        + '   <h6>'
                        + '      <a id="cart_div_title">' + element[1] + '</a>'
                        + '  </h6>'
                        + '  <div class="cart-pro-info">'
                        + '      <div class="cart-qty-price">'
                        + '          <span id="cart_div_qty" class="quantity">' + element[3] + ' x </span>'
                        + '          <span id="cart_div_price" class="price-box">' + element[2] + '</span>'
                        + '      </div>'
                        + '      <div class="delete-item-cart">'
                        + '          <a id="cart_div_item_remove_btn" onclick="removeProductFromCart(' + index + ')">'
                        + '              <i class="icon-trash icons"></i>'
                        + '          </a>'
                        + '      </div>'
                        + '  </div>'
                        + '</div>'
                        + '</li>');
                }

            } else {
                cart_item_list.html('');
                cart_sub_total.html('LKR 0.00');
                cart_div_total.html('0');
                cart_total.html('0');
            }
        }
    });

}

function removeProductFromCart(index) {

    Notiflix.Confirm.Show('Confirmation Required', 'Please Confirm to Remove Product from Cart?', 'Yes', 'No',
        function () {

            if (index != null) {
                $.ajax({
                    type: "GET",
                    url: "/products/session/removeProductFromCart",
                    data: {
                        index: index,
                    },
                    success: function (response) {
                        Notiflix.Notify.Success('Succefully Product Remove From Cart');
                        process_cart_view();
                    }
                });
            }

        },
        function () { });

}

var cartCheckout = $('#cartCheckout');

// Need to Develop
// ======================================
cartCheckout.click(function (e) {
    e.preventDefault();

    checkCartAvailability = 0;

    $.ajax({
        type: "GET",
        url: "/products/session/checkCartAvailibility",
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            if (response['type'] == 'error') {
                Notiflix.Notify.Failure(response['des']);

                mini_cart.removeClass('show');

            } else if (response['type'] == 'success') {

                var optionSelectVal = 0;

                $.ajax({
                    type: "GET",
                    url: "/orders/get/allCities",
                    beforeSend: function () {
                        Notiflix.Loading.Pulse();
                    },
                    success: function (response) {
                        Notiflix.Loading.Remove();
                        unregis_customer_checkoutCity_select.html('');

                        $.each(response, function (key, value) {
                            unregis_customer_checkoutCity_select.append('<option value=' + value['id'] + '>' + value['city'] + '</option>');
                        });

                    }
                });

                getBillingSummaryDetails(1);
                unregis_customer_checkout_modal.modal('toggle');

            }
        }
    });
});

unregis_customer_checkoutCity_select.change(function (e) {
    e.preventDefault();
    selectVal = unregis_customer_checkoutCity_select.val();
    getBillingSummaryDetails(selectVal);

});

function getBillingSummaryDetails(selectVal) {

    $.ajax({
        type: "GET",
        url: "/orders/get/getBillingSummary",
        data: {
            city_id: selectVal,
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            order_billing_summary_total.html(response[0]);
            order_billing_summary_vat.html('LKR. 0.00');
            order_billing_summary_delivery_charges.html(response[1]);
            order_billing_summary_sub_total.html(response[2]);

        }
    });

}

var cart_cityTempMap = unregis_customer_checkoutCity.typeahead({
    source: function (query, process) {
        return $.get('/products/session/deliverAreasSuggetions', {
            query: query,
        }, function (data) {
            cartCityTempMap = {};
            data.forEach(element => {
                cart_cityTempMap[element['name']] = element['id'];
            });
            return process(data);
        });
    }
});

cart_cityTempMap.change(function (e) {
    var tempId = cart_cityTempMap[unregis_customer_checkoutCity.val()];
    if (tempId != undefined) {
        unregis_customer_checkoutCity_id.val(tempId);
    }
});

unregis_customer_checkoutCity.keyup(function (e) {
    if ($(this).val().length == 0) {
        unregis_customer_checkoutCity_id.val("");
        Notiflix.Notify.Warning("Invalid City")
    }
});


var mini_cart = $('#mini_cart');

// IF CART AVAILABLE
// =======================================
unregis_customer_order_save.click(function (e) {
    e.preventDefault();

    Notiflix.Confirm.Show('Confirmation Required', 'Please Confirm to Place Order?', 'Yes', 'No',
        function () {

            $.ajax({
                type: "GET",
                url: "/orders/db/saveCart",
                data: {
                    fname: unregis_customer_checkoutFirst_name.val(),
                    lname: unregis_customer_checkoutLast_name.val(),
                    mobile_number: unregis_customer_checkoutMobile_number.val(),
                    email: unregis_customer_checkoutEmail.val(),
                    city_id: unregis_customer_checkoutCity_select.val(),
                    address: unregis_customer_checkoutAddress.val(),
                },
                beforeSend: function () {
                    Notiflix.Loading.Pulse();
                },
                success: function (response) {
                    Notiflix.Loading.Remove();

                    if ($.isEmptyObject(response.error)) {
                        if (response['type'] == 'error') {
                            Notiflix.Notify.Failure(response['des']);

                            mini_cart.removeClass('show');
                            
                        } else if (response['type'] == 'success') {
                            Notiflix.Notify.Success(response['des']);

                            unregis_customer_checkout_modal.modal('hide')
                            order_functionClear();
                            process_cart_view();

                            mini_cart.removeClass('show');
                            

                        }
                    } else {
                        $.each(response.error, function (key, value) {
                            Notiflix.Notify.Failure(value);
                        });
                    }
                }
            });

        },
        function () { });

});

function order_functionClear() {
    unregis_customer_checkoutFirst_name.val('');
    unregis_customer_checkoutLast_name.val('');
    unregis_customer_checkoutMobile_number.val('');
    unregis_customer_checkoutEmail.val('');
    unregis_customer_checkoutCity_select.val('');
    unregis_customer_checkoutAddress.val('');
}
