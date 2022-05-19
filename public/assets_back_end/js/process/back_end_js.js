Notiflix.Loading.Init({
    svgColor: "#1f6bff",
    fontFamily: "Quicksand",
    useGoogleFont: true,
});

Notiflix.Confirm.Init({
    titleColor: '#212121',
    okButtonColor: '#f8f8f8',
    okButtonBackground: '#1f6bff',
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

    // These options are needed to round to whole numbers if that's what you want.
    //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
    //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
});

var baseUrl = window.location.origin;
var new_grn_vat = $('#new_grn_vat');
var new_invoice_vat = $('#new_invoice_vat');
var new_grn_type = $('#new_grn_type');
var new_grnproduct_vat = $('#new_grnproduct_vat');
var new_invoiceproduct_vat = $('#new_invoiceproduct_vat');
var new_grn_warehouse = $('#new_grn_warehouse');

new_grn_warehouse.ready(function () {

    $.ajax({
        type: "GET",
        url: "/admin/warehouse/get/warehouselist",
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();
            response.forEach(element => {
                new_grn_warehouse.append('<option value="' + element['id'] + '">' + element['name'] + ' (' + (element['code']) + ')</option>');
            });
        }
    });
});

new_grn_vat.ready(function () {

    $.ajax({
        type: "GET",
        url: "/admin/vat/get/vatlist",
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            response.forEach(element => {
                new_grn_vat.append('<option value="' + element['id'] + '">Name : ' + element['vat_name'] + ', Value : ' + element['value'] + '%</option>');
            });
        }
    });
});

new_invoice_vat.ready(function () {

    $.ajax({
        type: "GET",
        url: "/admin/vat/get/vatlist",
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            response.forEach(element => {
                new_invoice_vat.append('<option value="' + element['id'] + '">Name : ' + element['vat_name'] + ', Value : ' + element['value'] + '%</option>');
            });
        }
    });
});

new_grnproduct_vat.ready(function () {

    $.ajax({
        type: "GET",
        url: "/admin/vat/get/vatlist",
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            response.forEach(element => {
                new_grnproduct_vat.append('<option value="' + element['id'] + '">' + element['vat_name'] + ' : ' + element['value'] + '%</option>');
            });
        }
    });
});

new_invoiceproduct_vat.ready(function () {

    $.ajax({
        type: "GET",
        url: "/admin/vat/get/vatlist",
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            response.forEach(element => {
                new_invoiceproduct_vat.append('<option value="' + element['id'] + '">' + element['vat_name'] + ' : ' + element['value'] + '%</option>');
            });
        }
    });
});

new_grn_type.ready(function () {
    $.ajax({
        type: "GET",
        url: "/admin/grnType/get/grnTypelist",
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            response.forEach(element => {
                new_grn_type.append('<option value="' + element['id'] + '">' + element['grn_type'] + '</option>');
            });
        }
    });
});

var new_category_modal = $("#new_category");
var new_category_modal_link = $("#new_category_modal_link");
var new_category_modal_close_btn = $("#new_category_modal_close_btn");
var category_image_form = $("#category_image_form");
var category_image_upload_input = $("#category_image_upload");
var category_image_delete_button = $("#category_image_delete");

var category_image_preview_div = $("#category_image_preview_div");
var category_image_name_preview_label = $("#category_image_name_preview");
var category_image_upload_preview_img = $("#category_image_upload_preview");
var category_image_upload_loading = $('#category_image_upload_loading');
var category_image_name_success_status = $('#category_image_name_success_status');

var productCategoryTempMap = {};
var new_category_sku = $("#new_category_sku");
var new_category_name = $("#new_category_name");
var new_category_sub = $("#new_category_sub");
var new_category_sub_id = $("#new_category_sub_id");

var new_category_save_btn = $("#new_category_save_btn");
var new_category_code = $("#new_category_code");

function categoryImageUploadDefaultView() {
    category_image_upload_input.val('');
    category_image_upload_preview_img.attr("src", null);
    category_image_upload_preview_img.addClass("d-none")
    category_image_name_success_status.addClass("d-none")
    category_image_preview_div.removeClass("d-none");
}

function categoryModalDefaultView() {
    new_category_name.val("");
    new_category_sub.val("");
    categoryImageUploadDefaultView();
}

function categoryModalSaveBtnModal(status) {
    ((status == 1) ? new_category_save_btn.html('<i class="fa fa-floppy-o" aria-hidden="true"></i> Save') : new_category_save_btn.html('<i class="fa fa-pencil" aria-hidden="true"></i> Update'));
}

new_category_modal_link.click(function (e) {
    e.preventDefault();
    categoryModalDefaultView();
    categoryImageUploadDefaultView();
    categoryModalSaveBtnModal(1);
});

category_image_upload_input.change(function (e) {
    e.preventDefault();
    var x = URL.createObjectURL(e.target.files[0]);
    category_image_upload_preview_img.attr("src", x);
    category_image_upload_preview_img.removeClass("d-none");
    category_image_preview_div.addClass("d-none");
});

category_image_delete_button.click(function (e) {
    e.preventDefault();
    categoryImageUploadDefaultView();
});

category_image_form.on('submit', function (event) {
    event.preventDefault();

    $.ajax({
        type: "POST",
        url: "/admin/product/category/uploadCategoryImage",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            if (response['type'] == 'error') {
                categoryImageUploadDefaultView();
                Notiflix.Notify.Failure(response['des']);

            } else if (response['type'] == 'success') {
                category_image_upload_preview_img.removeClass("d-none");
                category_image_name_success_status.removeClass("d-none");
                Notiflix.Notify.Success(response['des']);

            }
        }
    });
});

var productCategoryTempMap = new_category_sub.typeahead({
    source: function (query, process) {
        return $.get('/admin/product/category/get/suggetions', {
            query: query,
        }, function (data) {
            productCategoryTempMap = {};
            data.forEach(element => {
                productCategoryTempMap[element['name']] = element['id'];
            });
            return process(data);
        });
    }
});

productCategoryTempMap.change(function (e) {
    var tempId = productCategoryTempMap[new_category_sub.val()];
    if (tempId != undefined) {
        new_category_sub_id.val(tempId);
    }
});

new_category_sub.keyup(function (e) {
    if ($(this).val().length == 0) {
        new_category_sub.val("");
        new_category_sub_id.val("");
        Notiflix.Notify.Warning("Invalid Category")
    }
});

new_category_save_btn.click(function (e) {
    e.preventDefault();

    var categoryName = new_category_name.val();
    var categorySKU = new_category_sku.val();
    var categorySubCategory = new_category_sub_id.val();

    Notiflix.Confirm.Show('Category Save Confirmation', 'Please confirm to save this category', 'Confirm', 'Ignore', function () {

        $.ajax({
            type: "GET",
            url: "/admin/product/category/db/save",
            data: {
                sku: categoryName,
                name: categoryName,
                category_id: categorySubCategory,
            },
            beforeSend: function () {
                Notiflix.Loading.Pulse();
            },
            success: function (response) {
                if ($.isEmptyObject(response.error)) {
                    Notiflix.Loading.Remove();

                    if (response['type'] == 'error') {

                        Notiflix.Notify.Failure(response['des']);

                    } else if (response['type'] == 'success') {

                        categoryModalDefaultView();
                        new_category_modal.modal('hide');
                        Notiflix.Notify.Success(response['des']);
                        category_table.ajax.reload(null, false);

                    }


                } else {
                    $.each(response.error, function (key, value) {
                        Notiflix.Loading.Remove();
                        Notiflix.Notify.Failure(value);
                    });
                }

            }

        });

    }, function () { });


});

var category_table = $('#category_list').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: true,
    pageLength: 20,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default'
    },
    {
        extend: 'csv',
        className: 'btn btn-default'
    }
    ],
    ajax: {
        url: '/admin/product/category/get/categoryList',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});

function update_category_func(id) {

    $.ajax({
        type: "GET",
        url: "/admin/product/category/get/category_view_for_update",
        data: {
            id: id,
        },
        success: function (response) {

            new_category_modal.modal('toggle');
            categoryModalSaveBtnModal(2);

            new_category_code.val(response['code']);
            new_category_name.val(response['name']);
            ((response['get_sub_category_by_id'] == null) ? new_category_sub.val('-') : new_category_sub.val(response['get_sub_category_by_id']['name']));
            category_image_upload_preview_img.attr("src", baseUrl + "/assets_front_end/image/categories/" + response['get_category_image']['name']);
            category_image_upload_preview_img.removeClass("d-none");
            category_image_preview_div.addClass("d-none");

        }
    });

}

new_category_modal_close_btn.click(function (e) {
    e.preventDefault();

    new_category_modal.modal('hide');

});

function change_category_status_func(id, status) {

    $.ajax({
        type: "GET",
        url: "/admin/product/category/get/category_view_for_disable",
        data: {
            id: id,
            status: status
        },
        success: function (response) {

            if (response['type'] == 'error') {

                Notiflix.Notify.Failure(response['des']);

            } else if (response['type'] == 'success') {

                categoryModalDefaultView();
                Notiflix.Notify.Success(response['des']);
                category_table.ajax.reload(null, false);

            }

        }
    });

}

var product_image1_upload = $("#product_image1_upload");
var product_image1_upload_preview = $("#product_image1_upload_preview");
var product_image2_upload = $("#product_image2_upload");
var product_image2_upload_preview = $("#product_image2_upload_preview");
var product_insert_form_reset = $("#product_insert_form_reset");
var new_product_form = $("#new_product_form");
var new_product_des = $("#new_product_des");
var new_product_code = $("#new_product_code");
var product_view_modal = $("#product_view_modal");
var product_view_content = $("#product_view_content");
var product_view_code_name = $("#product_view_code_name");
var product_view_img1 = $("#product_view_img1");
var product_view_img2 = $("#product_view_img2");
var quickEdit_update = $('#quickEdit_update');
var update_product_form = $('#update_product_form');
var update_product_des = $('#update_product_des');

var product_list = $('#product_list');

var product_category_edit_modal = $('#product_category_edit_modal');
var edit_product_category_select_name = $('#edit_product_category_select_name');
var edit_product_select_category = $('#edit_product_select_category');
var edit_category_update_btn = $('#edit_category_update_btn');

product_list.DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [20, 30, 40, 50, 100],
    responsive: false,
    pageLength: 20,
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

$('.summernote').summernote({
    height: 300
});

product_image1_upload.change(function (e) {
    e.preventDefault();
    var x = URL.createObjectURL(e.target.files[0]);
    product_image1_upload_preview.attr("src", x);
});

product_image2_upload.change(function (e) {
    e.preventDefault();
    var x = URL.createObjectURL(e.target.files[0]);
    product_image2_upload_preview.attr("src", x);
});

product_insert_form_reset.click(function (e) {
    e.preventDefault();
    location.reload();
});

new_product_form.on('submit', function (e) {
    e.preventDefault();

    Notiflix.Confirm.Show('Product Save Confirmation', 'Please confirm to save this product?', 'Confirm', 'Ignore', function () {

        var formData = new FormData(new_product_form[0]);

        $.ajax({
            url: "/admin/db/save",
            method: "POST",
            data: formData,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                Notiflix.Loading.Pulse();
            },
            success: function (response) {
                Notiflix.Loading.Remove();

                console.log(response);

                if ($.isEmptyObject(response.error)) {
                    if (response['type'] == 'error') {
                        Notiflix.Notify.Failure(response['des']);
                    } else if (response['type'] == 'success') {
                        Notiflix.Notify.Success(response['des']);

                        new_product_form.trigger("reset");
                        new_product_des.summernote('reset');
                        product_image1_upload_preview.attr("src", baseUrl + "/assets_back_end/img/image_upload_icon.svg");
                        product_image2_upload_preview.attr("src", baseUrl + "/assets_back_end/img/image_upload_icon.svg");
                        new_product_code.val(response['code']);

                    }
                } else {
                    $.each(response.error, function (key, value) {
                        Notiflix.Notify.Failure(value);
                    });
                }
            }
        });

    }, function () { });
});

// LOCATION.RELOAD
// NEED TO UPDATE
// =====================================
function change_product_status_func(id, status) {

    Notiflix.Confirm.Show('Product Edit Confirmation', 'Please confirm to change status this product?', 'Confirm', 'Ignore', function () {

        $.ajax({
            type: "GET",
            url: "/admin/changeStatus",
            data: {
                id: id,
                status: status,
            },
            success: function (response) {

                if (response['type'] == 'error') {

                    Notiflix.Notify.Failure(response['des']);

                } else if (response['type'] == 'success') {

                    location.reload();

                }

            }
        });

    }, function () { });

}

// LOCATION.RELOAD
// NEED TO UPDATE
// =====================================
function product_default_price_edit(id) {

    var default_price = prompt("Enter New Price:");

    if (typeof (parseFloat(default_price)) == 'number') {

        $.ajax({
            type: "GET",
            url: "/admin/product/db/updateDefaultPrice",
            data: {
                id: id,
                price: default_price
            },
            beforeSend: function () {
                Notiflix.Loading.Pulse();
            },
            success: function (response) {

                if ($.isEmptyObject(response.error)) {
                    Notiflix.Loading.Remove();
                    if (response['type'] == 'error') {
                        Notiflix.Notify.Failure(response['des']);
                    } else if (response['type'] == 'success') {
                        location.reload();
                    }

                } else {
                    $.each(response.error, function (key, value) {
                        Notiflix.Loading.Remove();
                        Notiflix.Notify.Failure(value);
                    });
                }

            }
        });

    } else {
        Notiflix.Notify.Failure('You did not Enter a Valid Number');
    }

}

function product_category_edit(id) {

    $.ajax({
        type: "GET",
        url: "/admin/product/view/updateCategory",
        data: {
            id: id
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();
            edit_product_category_select_name.html(response[1]);
            edit_product_select_category.html('');

            $.each(response[0], function (key, value) {
                edit_product_select_category.append('<option value=' + value['id'] + '>' + value['name'] + '</option>');
            });

            edit_product_select_category.val(response[2]);

            product_category_edit_modal.modal('toggle');
        }
    });

}

// LOCATION.RELOAD
// NEED TO UPDATE
// =====================================
edit_category_update_btn.click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "GET",
        url: "/admin/product/db/updateCategory",
        data: {
            category_id: edit_product_select_category.val()
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {

            if ($.isEmptyObject(response.error)) {
                Notiflix.Loading.Remove();
                if (response['type'] == 'error') {
                    Notiflix.Notify.Failure(response['des']);
                } else if (response['type'] == 'success') {
                    location.reload();
                }

            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Loading.Remove();
                    Notiflix.Notify.Failure(value);
                });
            }

        }
    });

});

function product_quick_edit(id) {

    $.ajax({
        type: "GET",
        url: "/admin/product/get/edit",
        data: {
            id: id
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();
            console.log(response);
            $('#product_update_code_name').html(response['code'] + ' - ' + response['lang1_name']);
            $('#product_edit_modal').modal('toggle');
        }
    });
}

quickEdit_update.click(function (e) {
    e.preventDefault();

    Notiflix.Confirm.Show('Product Description Update Confirmation', 'Please confirm to update description of this product?', 'Confirm', 'Ignore', function () {

        var formData = new FormData(update_product_form[0]);

        $.ajax({
            url: "/admin/product/db/updateDescription",
            method: "POST",
            data: formData,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                Notiflix.Loading.Pulse();
            },
            success: function (response) {
                Notiflix.Loading.Remove();

                console.log(response);

                if ($.isEmptyObject(response.error)) {
                    if (response['type'] == 'error') {
                        Notiflix.Notify.Failure(response['des']);
                    } else if (response['type'] == 'success') {
                        Notiflix.Notify.Success(response['des']);
                        update_product_form.trigger("reset");
                        update_product_des.summernote('reset');
                        $('#product_view_modal').modal('hide');
                    }
                } else {
                    $.each(response.error, function (key, value) {
                        Notiflix.Notify.Failure(value);
                    });
                }
            }
        });

    }, function () { });

});

function product_quick_view(id) {
    product_view_modal.modal('toggle');

    $.ajax({
        type: "GET",
        url: "/admin/product/get/details",
        data: {
            id: id
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();
            product_view_code_name.html(response['code'] + ' - ' + response['lang1_name']);
            product_view_content.html(response['description']);
            product_view_img1.attr("src", baseUrl + "/assets_front_end/image/products/" + response['get_product_image'][0]['get_media']['name']);
            if (response['get_product_image'].length == 2) {
                product_view_img2.attr("src", baseUrl + "/assets_front_end/image/products/" + response['get_product_image'][1]['get_media']['name']);
            }
        }
    });

}

new_supplier_form = $("#new_supplier");
new_supplier_modal = $("#new_supplier_modal");
update_supplier_modal = $("#update_supplier_modal");

var update_supplier_name = $("#update_supplier_name");
var update_supplier_company_name = $("#update_supplier_company_name");
var update_supplier_registration_number = $("#update_supplier_registration_number");
var update_supplier_street_address = $("#update_supplier_street_address");
var update_supplier_city = $("#update_supplier_city");
var update_supplier_tel1 = $("#update_supplier_tel1");
var update_supplier_tel2 = $("#update_supplier_tel2");
var update_supplier_email = $("#update_supplier_email");
var update_supplier_bank_details = $("#update_supplier_bank_details");
var update_supplier_form = $("#update_supplier_form");

var supplier_table = $('#supplier_list').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: true,
    pageLength: 20,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default'
    },
    {
        extend: 'csv',
        className: 'btn btn-default'
    }
    ],
    ajax: {
        url: '/admin/supplier/get/supplierList',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});

new_supplier_form.on('submit', function (e) {
    e.preventDefault();

    Notiflix.Confirm.Show('Supplier Save Confirmation', 'Please confirm to save this supplier?', 'Confirm', 'Ignore', function () {

        var formData = new FormData(new_supplier_form[0]);

        $.ajax({
            method: "POST",
            url: "/admin/supplier/db/save",
            data: formData,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
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

                        new_supplier_modal.modal('hide');
                        new_supplier_form.trigger("reset");
                        supplier_table.ajax.reload(null, false);
                    }
                } else {
                    $.each(response.error, function (key, value) {
                        Notiflix.Notify.Failure(value);
                    });
                }
            }
        });

    }, function () { });
});

function createSupplierModalView(data) {
    update_supplier_modal.modal('toggle');

    update_supplier_name.val(data['name']);
    update_supplier_company_name.val(data['company_name']);
    update_supplier_registration_number.val(data['company_regis']);
    update_supplier_street_address.val(data['street_address']);
    update_supplier_city.val(data['city']);
    update_supplier_tel1.val(data['tel1']);
    update_supplier_tel2.val(data['tel2']);
    update_supplier_email.val(data['email']);
    update_supplier_bank_details.val(data['bank_details']);

}

function update_supplier_func_view(id) {

    $.ajax({
        type: "GET",
        url: "/admin/supplier/get/category_view_for_update",
        data: { id: id },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();
            createSupplierModalView(response);
        }
    });

}

update_supplier_form.on('submit', function (e) {
    e.preventDefault();

    Notiflix.Confirm.Show('Supplier Update Confirmation', 'Please confirm to update this supplier?', 'Confirm', 'Ignore', function () {

        var formData = new FormData(update_supplier_form[0]);

        $.ajax({
            method: "POST",
            url: "/admin/supplier/db/updateSupplier",
            data: formData,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
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
                        update_supplier_form.trigger("reset");
                        update_supplier_modal.modal('hide');
                        supplier_table.ajax.reload(null, false);
                    }
                } else {
                    $.each(response.error, function (key, value) {
                        Notiflix.Notify.Failure(value);
                    });
                }
            }
        });

    }, function () { });

});

function change_supplier_status_func(id, status) {

    Notiflix.Confirm.Show('Supplier Edit Confirmation', 'Please confirm to change status this supplier?', 'Confirm', 'Ignore', function () {

        $.ajax({
            type: "GET",
            url: "/admin/supplier/db/changeStatus",
            data: {
                id: id,
                status: status,
            },
            success: function (response) {

                if (response['type'] == 'error') {
                    Notiflix.Notify.Failure(response['des']);
                } else if (response['type'] == 'success') {
                    supplier_table.ajax.reload(null, false);
                }

            }
        });

    }, function () { });
}

var new_grnproduct_product = $("#new_grnproduct_product");
var new_grnproduct_product_id = $("#new_grnproduct_product_id");
var new_grnproduct_unit_price = $("#new_grnproduct_unit_price");
var new_grnproduct_qty = $("#new_grnproduct_qty");
var new_grnproduct_vat = $("#new_grnproduct_vat");
var new_grnproduct_save_btn = $("#new_grnproduct_save_btn");

var productTempMap = new_grnproduct_product.typeahead({
    source: function (query, process) {
        return $.get('/admin/product/get/suggetions', {
            query: query,
        }, function (data) {
            productCategoryTempMap = {};
            data.forEach(element => {
                productTempMap[element['name']] = element['id'];
            });
            return process(data);
        });
    }
});

productTempMap.change(function (e) {
    var tempId = productTempMap[new_grnproduct_product.val()];
    if (tempId != undefined) {
        new_grnproduct_product_id.val(tempId);
    }
});

new_grnproduct_product.keyup(function (e) {
    if ($(this).val().length == 0) {
        new_grnproduct_product.val("");
        new_grnproduct_product_id.val("");
    }
});

new_grnproduct_save_btn.click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "GET",
        url: "/admin/grn/session/addProduct",
        data: {
            product_id: new_grnproduct_product_id.val(),
            unit_price: new_grnproduct_unit_price.val(),
            qty: new_grnproduct_qty.val(),
            vat: new_grnproduct_vat.val(),
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
                    grn_field_remove();
                    grnAddedProductList_table.ajax.reload(null, false);
                    get_grnProductTotal();
                    new_grnproduct_product.focus();
                }
            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Notify.Failure(value);
                });
            }
        }
    });

});

function grn_field_remove() {
    new_grnproduct_product.val('');
    new_grnproduct_product_id.val('');
    new_grnproduct_qty.val('');
    new_grnproduct_unit_price.val('');
}

var new_grn_total_view = $('#new_grn_total_view');
var new_grn_supplier = $('#new_grn_supplier');
var new_grn_supplier_id = $('#new_grn_supplier_id');
var grn_product_view_modal = $('#grn_product_view_modal');
var grn_product_view_code_name = $('#grn_product_view_code_name');
var grn_product_view_content = $('#grn_product_view_content');
var grn_product_view_img1 = $('#grn_product_view_img1');
var grn_product_view_img2 = $('#grn_product_view_img2');
var new_grn_save_btn = $('#new_grn_save_btn');
var new_grn_po_ref = $('#new_grn_po_ref');
var new_grn_remark = $('#new_grn_remark');
var new_grn_code = $('#new_grn_code');

var grn_supplierTempMap = new_grn_supplier.typeahead({
    source: function (query, process) {
        return $.get('/admin/supplier/get/suggetions', {
            query: query,
        }, function (data) {
            productCategoryTempMap = {};
            data.forEach(element => {
                grn_supplierTempMap[element['name']] = element['id'];
            });
            return process(data);
        });
    }
});

grn_supplierTempMap.change(function (e) {
    var tempId = grn_supplierTempMap[new_grn_supplier.val()];
    if (tempId != undefined) {
        new_grn_supplier_id.val(tempId);
    }
});

new_grn_supplier.keyup(function (e) {
    if ($(this).val().length == 0) {
        new_grn_supplier.val("");
        new_grn_supplier_id.val("");
        Notiflix.Notify.Warning("Invalid Supplier")
    }
});

function get_grnProductTotal() {
    $.ajax({
        type: "GET",
        data: {
            vat_id: new_grn_vat.val()
        },
        url: "/admin/grn/session/getTotal",
        success: function (response) {

            if ($.isEmptyObject(response.error)) {
                new_grn_total_view.html(response);
            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Notify.Failure(value);
                });
            }

        }
    });
}

new_grn_vat.change(function (e) {
    e.preventDefault();

    get_grnProductTotal();
});

var grnAddedProductList_table = $('#grn_save_product_list').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: false,
    pageLength: 20,
    searching: false,
    paging: false,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default d-none'
    },
    {
        extend: 'csv',
        className: 'btn btn-default d-none'
    }
    ],
    ajax: {
        url: '/admin/grn/session/grnAddedProductList',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});

function grn_product_remove_func(index) {

    Notiflix.Confirm.Show('Confirmation Required', 'Please Confirm to Remove Item From List?', 'Yes', 'No',
        function () {
            $.ajax({
                type: "GET",
                url: "/admin/grn/session/removeProduct",
                data: {
                    grn_product_id: index,
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
                            grnAddedProductList_table.ajax.reload(null, false);
                            get_grnProductTotal();
                        }
                    } else {
                        $.each(response.error, function (key, value) {
                            Notiflix.Notify.Failure(value);
                        });
                    }
                }
            });
        },
        function () { }
    );

}

function grn_product_view_func(id) {
    alert(id);
}

function grn_product_view_func(id) {

    grn_product_view_modal.modal('toggle');
    $.ajax({
        type: "GET",
        url: "/admin/product/get/details",
        data: {
            id: id
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();
            grn_product_view_code_name.html(response['code'] + ' - ' + response['lang1_name']);
            grn_product_view_content.html(response['description']);
            grn_product_view_img1.attr("src", baseUrl + "/assets_front_end/image/products/" + response['get_product_image'][0]['get_media']['name']);
            if (response['get_product_image'].length == 2) {
                grn_product_view_img2.attr("src", baseUrl + "/assets_front_end/image/products/" + response['get_product_image'][1]['get_media']['name']);
            }
        }
    });
}

new_grn_save_btn.click(function (e) {
    e.preventDefault();

    Notiflix.Confirm.Show('Confirmation Required', 'Please Confirm to Save GRN?', 'Yes', 'No',
        function () {
            $.ajax({
                type: "GET",
                url: "/admin/grn/db/saveGRN",
                data: {
                    po_ref: new_grn_po_ref.val(),
                    remark: new_grn_remark.val(),
                    warehouse_id: new_grn_warehouse.val(),
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
                            Notiflix.Notify.Success('Successfully Saved GRN');

                            new_grn_code.val(response['des']);
                            new_grn_clear_fields();
                            grnAddedProductList_table.ajax.reload(null, false);
                            get_grnProductTotal();
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

function new_grn_clear_fields() {
    new_grn_supplier.val('');
    new_grn_supplier_id.val('');
    new_grn_po_ref.val('');
    new_grn_remark.val('');
    new_grnproduct_product.focus();
}

var purchase_list = $('#purchase_list').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: false,
    pageLength: 20,
    searching: true,
    paging: true,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default d-none'
    },
    {
        extend: 'csv',
        className: 'btn btn-default d-none'
    }
    ],
    ajax: {
        url: '/admin/grn/get/purchaseList',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});

var view_purchase_modal = $('#view_purchase');
var view_purchase_payment_modal = $('#view_purchase_payment');
var edit_purchase_modal = $('#edit_purchase');

var view_purchase_supplier_name = $('#view_purchase_supplier_name');
var view_purchase_supplier_address = $('#view_purchase_supplier_address');
var view_purchase_supplier_tel = $('#view_purchase_supplier_tel');
var view_purchase_supplier_email = $('#view_purchase_supplier_email');

var view_purchase_warehouse_name = $('#view_purchase_warehouse_name');
var view_purchase_warehouse_address = $('#view_purchase_warehouse_address');
var view_purchase_warehouse_tel = $('#view_purchase_warehouse_tel');
var view_purchase_warehouse_email = $('#view_purchase_warehouse_email');

var view_purchase_reference = $('#view_purchase_reference');
var view_purchase_date = $('#view_purchase_date');
var view_purchase_status = $('#view_purchase_status');
var view_purchase_payment_status = $('#view_purchase_payment_status');

var view_purchase_table = $('#view_purchase_table');

function view_purchase_func(id) {

    $.ajax({
        type: "GET",
        url: "/admin/grn/view/purchase",
        data: {
            id: id,
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            view_purchase_table.html('');

            if ($.isEmptyObject(response.error)) {

                view_purchase_modal.modal('toggle');

                view_purchase_supplier_name.html((response[0]['get_supplier']['name'] == null) ? '-' : response[0]['get_supplier']['name']);
                view_purchase_supplier_address.html((response[0]['get_supplier']['street_address'] == null) ? '-' : response[0]['get_supplier']['street_address'] + ', ' + response[0]['get_supplier']['city']);
                view_purchase_supplier_tel.html((response[0]['get_supplier']['tel1'] == null) ? '-' : 'Tel: ' + response[0]['get_supplier']['tel1']);
                view_purchase_supplier_email.html((response[0]['get_supplier']['email'] == null) ? '-' : 'Email: ' + response[0]['get_supplier']['email']);

                view_purchase_warehouse_name.html((response[0]['getwarehouse']['name'] == null) ? '-' : response[0]['getwarehouse']['name']);
                view_purchase_warehouse_address.html((response[0]['getwarehouse']['address'] == null) ? '-' : response[0]['getwarehouse']['address']);
                view_purchase_warehouse_tel.html((response[0]['getwarehouse']['telephone'] == null) ? '-' : response[0]['getwarehouse']['telephone']);
                view_purchase_warehouse_email.html((response[0]['getwarehouse']['email'] == null) ? '-' : response[0]['getwarehouse']['email']);

                view_purchase_reference.html((response[0]['grn_code'] == null) ? '-' : 'Reference ' + response[0]['grn_code']);
                view_purchase_date.html('Date: ' + formatDate(response[0]['created_at']));
                view_purchase_status.html('Status: ' + response[0]['get_grn_type']['grn_type']);

                var tbody = "";
                var total = 0;
                var grn_sub_total = 0;

                if (JSON.stringify(response[0]['get_purchases']) == '[]') {
                    view_purchase_payment_status.html('Payment Status: Pending');
                } else {
                    $.each(response[0]['get_purchases'], function (key, value) {
                        total += value['amount'];
                    });
                    view_purchase_payment_status.html((total == response[0]['total']) ? 'Payment Status: Done' : 'Payment Status: Pending');
                }

                $.each(response[0]['get_g_r_n_products'], function (key, value) {
                    tbody += '<tr>' +
                        '<td>' + ++key + '</td>' +
                        '<td>' + value['get_product']['code'] + ' - ' + value['get_product']['lang1_name'] + '</td>' +
                        '<td class="text-end">' + value['in_qty'] + ' ' + value['get_product']['get_measurement']['symbol'] + '</td>' +
                        '<td>' + formatter.format(value['unit_price']) + '</td>' +
                        '<td class="text-end">' + formatter.format(value['total']) + '</td>' +
                        '<td class="text-end">' + value['get_vat']['value'] + '%</td>' +
                        '<td class="text-end">' + formatter.format(value['net_total']) + '</td>' +
                        '</tr>'

                    grn_sub_total += value['net_total'];

                });

                tbody += '<tr>' +
                    '<td colspan="6" class="text-end font-weight-500">Total Net Amount</td>' +
                    '<td class="text-end font-weight-500">' + formatter.format(grn_sub_total) + '</td>' +
                    '</tr>';

                tbody += '<tr>' +
                    '<td colspan="6" class="text-end font-weight-500">VAT</td>' +
                    '<td class="text-end font-weight-500">' + response[0]['get_vat']['value'] + '%</td>' +
                    '</tr>';

                tbody += '<tr>' +
                    '<td colspan="6" class="text-end font-weight-500">Total Sub Amount</td>' +
                    '<td class="text-end font-weight-500">' + formatter.format(response[0]['total']) + '</td>' +
                    '</tr>';

                tbody += '<tr>' +
                    '<td colspan="6" class="text-end font-weight-500">Paid</td>' +
                    '<td class="text-end font-weight-500">' + formatter.format(total) + '</td>' +
                    '</tr>';

                tbody += '<tr>' +
                    '<td colspan="6" class="text-end font-weight-500">Balance</td>' +
                    '<td class="text-end font-weight-500">' + formatter.format(response[0]['total'] - total) + '</td>' +
                    '</tr>';

                view_purchase_table.html(tbody);


            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Notify.Failure(value);
                });
            }
        }
    });

}

function print_purchase_func(id) {
    alert('print purchase' + id);
}

function edit_pending_purchase_func(id) {
    edit_purchase_modal.modal('toggle');
}

function view_purchase_payment_func() {
    view_purchase_payment_modal.modal('toggle');
}

function add_purchase_payment_func(id) {
    alert('add purchase payment' + id);
}

function return_purchase_func(id) {
    alert('return purchase' + id);
}

function delete_purchase_func(id) {
    alert('delete purchase' + id);
}

// Able to Develop
// ======================================================

var stock_list = $('#all_stock_list').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: false,
    pageLength: 20,
    searching: true,
    paging: true,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default d-none'
    },
    {
        extend: 'csv',
        className: 'btn btn-default d-none'
    }
    ],
    ajax: {
        url: '/admin/stock/get/allstock',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});

var stock_list = $('#product-wise_stock').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: false,
    pageLength: 20,
    searching: true,
    paging: true,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default d-none'
    },
    {
        extend: 'csv',
        className: 'btn btn-default d-none'
    }
    ],
    ajax: {
        url: '/admin/stock/get/product-wise_stock',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});

var new_invoiceproduct_product = $("#new_invoiceproduct_product");
var new_invoiceproduct_product_id = $("#new_invoiceproduct_product_id");
var new_invoiceproduct_unit_price = $("#new_invoiceproduct_unit_price");


var productTempMap = new_invoiceproduct_product.typeahead({
    source: function (query, process) {
        return $.get('/admin/product/get/suggetions', {
            query: query,
        }, function (data) {
            productCategoryTempMap = {};
            data.forEach(element => {
                productTempMap[element['name']] = element['id'];
            });
            return process(data);
        });
    }
});

productTempMap.change(function (e) {
    var tempId = productTempMap[new_invoiceproduct_product.val()];
    if (tempId != undefined) {
        new_invoiceproduct_product_id.val(tempId);

        $.ajax({
            type: "GET",
            url: "/admin/product/db/getProductPrice",
            data: {
                id: new_invoiceproduct_product_id.val()
            },
            success: function (response) {

                new_invoiceproduct_unit_price.val(response);
            }
        });

    }
});

new_invoiceproduct_product.keyup(function (e) {
    if ($(this).val().length == 0) {
        new_invoiceproduct_product.val("");
        new_invoiceproduct_product_id.val("");
    }
});

var new_invoice_code = $('#new_invoice_code');
var new_invoice_ref = $('#new_invoice_ref');
var new_invoice_currency_type = $('#new_invoice_currency_type');
var new_invoice_type = $('#new_invoice_type');
var new_invoice_pay_done_date = $('#new_invoice_pay_done_date');
var new_invoice_service_charge = $('#new_invoice_service_charge');
var new_invoice_staff = $('#new_invoice_staff');
var new_invoice_discount = $('#new_invoice_discount');
var new_invoice_billing_address = $('#new_invoice_billing_address');
var new_invoice_remark = $('#new_invoice_remark');
var new_invoice_billing_to_id = $('#new_invoice_billing_to_id');

var new_invoiceproduct_unit_price = $('#new_invoiceproduct_unit_price');
var new_invoiceproduct_qty = $('#new_invoiceproduct_qty');
var new_invoiceproduct_discount = $('#new_invoiceproduct_discount');
var new_invoiceproduct_vat = $('#new_invoiceproduct_vat');
var new_invoiceproduct_save_btn = $('#new_invoiceproduct_save_btn');
var new_invoice_total_view = $('#new_invoice_total_view');
var new_invoice_save_btn = $('#new_invoice_save_btn');

// var invoice_customerTempMap = new_invoice_billing_to.typeahead({
//     source: function (query, process) {
//         return $.get('/admin/customer/get/suggetions', {
//             query: query,
//         }, function (data) {
//             data.forEach(element => {
//                 invoice_customerTempMap[element['name']] = element['id'];
//             });
//             return process(data);
//         });
//     }
// });

// invoice_customerTempMap.change(function (e) {
//     var tempId = invoice_customerTempMap[new_invoice_billing_to.val()];
//     if (tempId != undefined) {
//         new_invoice_billing_to_id.val(tempId);

//         $.ajax({
//             type: "GET",
//             url: "/admin/customer/get/suggetions/address",
//             data: {
//                 id: new_invoice_billing_to_id.val()
//             },
//             success: function (response) {
//                 new_invoice_billing_address.val(response);
//             }
//         });

//     }
// });

// new_invoice_billing_to.keyup(function (e) {
//     if ($(this).val().length == 0) {
//         new_invoice_billing_to.val("");
//         new_invoice_billing_to_id.val("");
//         Notiflix.Notify.Warning("Invalid Customer")
//     }
// });

function invoice_field_remove() {
    new_invoiceproduct_product.val('');
    new_invoiceproduct_product_id.val('');
    new_invoiceproduct_qty.val('');
    new_invoiceproduct_unit_price.val('');
    new_invoiceproduct_discount.val('0');
}

new_invoiceproduct_save_btn.click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "GET",
        url: "/admin/invoice/session/addProduct",
        data: {
            product_id: new_invoiceproduct_product_id.val(),
            unit_price: new_invoiceproduct_unit_price.val(),
            qty: new_invoiceproduct_qty.val(),
            vat: new_invoiceproduct_vat.val(),
            discount: new_invoiceproduct_discount.val(),
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            if ($.isEmptyObject(response.error)) {

                if (response == 'RESP1') {
                    Notiflix.Notify.Failure('This product does not have any stock');
                } else if (response == 'RESP2') {
                    Notiflix.Notify.Failure('Invalid stock');
                } else {

                    Notiflix.Notify.Success('Product add to invoice list successfully');

                    console.log(response)
                    invoiceAddedProductList_table.ajax.reload(null, false);

                    invoice_field_remove();
                    get_invoiceProductTotal();

                    new_invoiceproduct_product.focus();
                }

            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Notify.Failure(value);
                });
            }
        }
    });
});

new_invoice_service_charge.change(function (e) {
    e.preventDefault();
    get_invoiceProductTotal();
});

new_invoice_type.change(function (e) {
    e.preventDefault();
    get_invoiceProductTotal();
});

new_invoice_discount.keyup(function (e) {
    get_invoiceProductTotal();
});

function get_invoiceProductTotal() {
    $.ajax({
        type: "GET",
        data: {
            vat_id: new_invoice_vat.val(),
            invoice_type: new_invoice_type.val(),
            sc: new_invoice_service_charge.val(),
            discount: new_invoice_discount.val()
        },
        url: "/admin/invoice/session/getTotal",
        success: function (response) {

            console.log(response);

            if ($.isEmptyObject(response.error)) {
                new_invoice_total_view.html(response);
            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Notify.Failure(value);
                });
            }
        }
    });
}

new_invoice_vat.change(function (e) {
    e.preventDefault();
    get_invoiceProductTotal();
});

var invoiceAddedProductList_table = $('#invoice_save_product_list').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: false,
    pageLength: 20,
    searching: false,
    paging: false,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default d-none'
    },
    {
        extend: 'csv',
        className: 'btn btn-default d-none'
    }
    ],
    ajax: {
        url: '/admin/invoices/session/invoiceTableView',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});

function invoice_product_remove_func(index) {

    if (index != null) {
        $.ajax({
            type: "GET",
            url: "/admin//invoices/removeFromSession",
            data: {
                index: index,
            },
            success: function (response) {
                console.log(response);
                if (response != '2') {
                    get_invoiceProductTotal();
                    Notiflix.Notify.Success('Product from invoice list successfully');
                    invoiceAddedProductList_table.ajax.reload(null, false);
                }
            }
        });
    }
}

new_invoice_save_btn.click(function (e) {
    e.preventDefault();

    Notiflix.Confirm.Show('Confirmation Required', 'Please Confirm to Save Invoice?', 'Yes', 'No',
        function () {

            $.ajax({
                type: "GET",
                url: "/admin/invoice/db/save",
                data: {
                    currency: new_invoice_currency_type.val(),
                    type_id: new_invoice_type.val(),
                    customer_id: new_invoice_billing_to_id.val(),
                    invoice_remark: new_invoice_remark.val(),
                    invoice_vat: new_invoice_vat.val(),
                    sc: new_invoice_service_charge.val(),
                    emp_id: new_invoice_staff.val(),
                    discount: new_invoice_discount.val()
                },
                beforeSend: function () {
                    $(this).prop("disabled", true);
                    $('#invoice_and_print_save_btn').prop("disabled", true);
                    Notiflix.Loading.Pulse();
                },
                success: function (response) {
                    $('#invoice_save_btn').prop("disabled", false);
                    $('#invoice_and_print_save_btn').prop("disabled", false);
                    Notiflix.Loading.Remove();
                    Notiflix.Notify.Success('Invoice Saved Successful');

                    new_invoice_code.val(response['invoice_code']);
                    new_invoice_remark.val('');
                    new_invoice_discount.val('');
                    invoiceAddedProductList_table.ajax.reload(null, false);
                    get_invoiceProductTotal();
                    print_invoice_func(response['invoice_id']);

                }
            });

        },
        function () { });
});

function print_invoice_func(id) {

    $.ajax({
        type: "GET",
        url: "/admin/invoice/print",
        data: {
            id: id,
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            if (response == 2) {
                Notiflix.Notify.Warning('Something Wrong.');
            } else {
                printReport(response);
            }

        }
    });

}


// ONLINE ORDER
var online_order_list = $('#online_order_list').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: false,
    pageLength: 20,
    searching: true,
    paging: true,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default d-none'
    },
    {
        extend: 'csv',
        className: 'btn btn-default d-none'
    }
    ],
    ajax: {
        url: '/admin/online_orders/get/orders',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});

var view_order = $('#view_order');

var view_order_deliver_area = $('#view_order_deliver_area');
var view_order_deliver_address = $('#view_order_deliver_address');
var view_order_deliver_contacts = $('#view_order_deliver_contacts');
var view_order_deliver_charges = $('#view_order_deliver_charges');

var view_order_code = $('#view_order_code');
var view_order_date = $('#view_order_date');
var view_order_webuser_name = $('#view_order_webuser_name');
var view_order_email = $('#view_order_email');

var view_order_table = $('#view_order_table');

function view_order_func(id) {

    $.ajax({
        type: "GET",
        url: "/admin/online_orders/view/orders",
        data: {
            id: id,
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            view_order_table.html('');

            if ($.isEmptyObject(response.error)) {

                view_purchase_modal.modal('toggle');

                view_order_deliver_area.html(response[0]['get_web_user_delivery']['get_deliver_area']['city']);
                view_order_deliver_address.html(response[0]['get_web_user_delivery']['street_address']);
                view_order_deliver_contacts.html(response[0]['get_web_user']['mobile_number']);
                view_order_deliver_charges.html(formatter.format(response[0]['get_web_user_delivery']['get_deliver_area']['deliver_amount']));

                view_order_code.html(response[0]['order_code']);
                view_order_date.html('Date: ' + formatDate(response[0]['created_at']));
                view_order_webuser_name.html(response[0]['get_web_user']['fname'] + ' ' + response[0]['get_web_user']['lname']);
                view_order_email.html(response[0]['get_web_user']['email']);

                var tbody = "";
                var order_sub_total = 0;

                $.each(response[0]['get_cart']['get_cart_has_products'], function (key, value) {
                    tbody += '<tr>' +
                        '<td>' + ++key + '</td>' +
                        '<td>' + value['get_product']['code'] + ' - ' + value['get_product']['lang1_name'] + '</td>' +
                        '<td class="text-end">' + value['in_qty'] + ' ' + value['get_product']['get_measurement']['symbol'] + '</td>' +
                        '<td>' + formatter.format(value['unit_price']) + '</td>' +
                        '<td class="text-end">' + formatter.format(value['net_total']) + '</td>' +
                        '<td class="text-end">' + value['discount'] + '%</td>' +
                        '<td class="text-end">' + formatter.format(value['sub_total']) + '</td>' +
                        '</tr>'

                    order_sub_total += value['sub_total'];

                });

                tbody += '<tr>' +
                    '<td colspan="6" class="text-end font-weight-500">Total Net Amount</td>' +
                    '<td class="text-end font-weight-500">' + formatter.format(order_sub_total) + '</td>' +
                    '</tr>';

                tbody += '<tr>' +
                    '<td colspan="6" class="text-end font-weight-500">VAT</td>' +
                    '<td class="text-end font-weight-500">' + response[0]['get_vat']['value'] + '%</td>' +
                    '</tr>';

                tbody += '<tr>' +
                    '<td colspan="6" class="text-end font-weight-500">Total Net Amount</td>' +
                    '<td class="text-end font-weight-500">' + formatter.format(response[0]['net_total']) + '</td>' +
                    '</tr>';

                view_order_table.html(tbody);

            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Notify.Failure(value);
                });
            }

            view_order.modal('toggle');

        }
    });

}

function order_changed_status_func(id, status) {

    $.ajax({
        type: "GET",
        url: "/admin/online_orders/edit/orders",
        data: {
            id: id,
            status: status
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            console.log(response);

            if ($.isEmptyObject(response.error)) {
                if (response['type'] == 'error') {
                    Notiflix.Notify.Failure(response['des']);
                } else if (response['type'] == 'success') {
                    Notiflix.Notify.Success(response['des']);
                    online_order_list.ajax.reload(null, false);
                }
            } else {
                $.each(response.error, function (key, value) {
                    Notiflix.Notify.Failure(value);
                });
            }
        }
    });

}

var invoice_list_status_option = $('#invoice_list_status_option');
var invoice_list_code_option = $('#invoice_list_code_option');
var invoice_list_start_date_option = $('#invoice_list_start_date_option');
var invoice_list_end_date_option = $('#invoice_list_end_date_option');
var invoice_list_billing_to_option = $('#invoice_list_billing_to_option');
var invoice_list_payment_type_option = $('#invoice_list_payment_type_option');
var invoice_list_referance_option = $('#invoice_list_referance_option');
var invoice_list_admin_list_option = $('#invoice_list_admin_list_option');
var invoice_list_billing_address_option = $('#invoice_list_billing_address_option');

var selected_status = 0;
var typed_code = null;
var selected_start_date = null;
var selected_end_date = null;
var selected_customer = 0;
var selected_payment_type = 0;
var typed_referance = null;
var selected_admin = 0;
var typed_customer_address = null;

function administration_wise_invoice_search_func() {
    selected_status = invoice_list_status_option.val();
    typed_code = invoice_list_code_option.val();
    selected_start_date = invoice_list_start_date_option.val();
    selected_end_date = invoice_list_end_date_option.val();
    selected_customer = invoice_list_billing_to_option.val();
    selected_payment_type = invoice_list_payment_type_option.val();
    typed_referance = invoice_list_referance_option.val();
    selected_admin = invoice_list_admin_list_option.val();
    typed_customer_address = invoice_list_billing_address_option.val();

    console.log(
        selected_status,
        typed_code,
        selected_start_date,
        selected_end_date,
        selected_customer,
        selected_payment_type,
        typed_referance,
        selected_admin,
        typed_customer_address,
    );

    administration_wise_invoices.ajax.reload(null, false);

}

var administration_wise_invoices = $('#administration_wise_invoices').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    ordering: true,
    responsive: false,
    pageLength: 20,
    searching: false,
    paging: true,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default'
    },
    {
        extend: 'csv',
        className: 'btn btn-default'
    }
    ],
    columnDefs: [
        {
            "targets": 1,
            "className": "text-center",
            "width": "4%"
        },
    ],
    ajax: {
        data: {
            status: selected_status,
            code: typed_code,
            start_date: selected_start_date,
            end_date: selected_end_date,
            customer: selected_customer,
            payment_type: selected_payment_type,
            ref: typed_referance,
            admin: selected_admin,
            customer_address: typed_customer_address,
        },
        url: '/admin/invoice/db/get/getInvoiceList',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        console.log(data);
        $(cells).addClass('py-1 align-middle');
    }
});

var view_invoice_invoice_code = $('#view_invoice_invoice_code');
var view_billing_to = $('#view_billing_to');
var view_invoice_billing_address = $('#view_invoice_billing_address');
var view_invoice_warehouse_name = $('#view_invoice_warehouse_name');
var view_invoice_warehouse_address = $('#view_invoice_warehouse_address');
var view_invoice_warehouse_tel = $('#view_invoice_warehouse_tel');
var view_invoice_warehouse_email = $('#view_invoice_warehouse_email');
var view_invoice_referance = $('#view_invoice_referance');
var view_invoice_date = $('#view_invoice_date');
var view_invoice_status = $('#view_invoice_status');
var view_invoice_payment_status = $('#view_invoice_payment_status');

var view_invoice_table = $('#view_invoice_table');
var view_invoice = $('#view_invoice');
var view_inovice_print_btn = $('#view_inovice_print_btn');

function view_invoice_func(id) {

    $.ajax({
        type: "GET",
        url: "/admin/invoice/view/invoice",
        data: {
            id: id,
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            console.log(response);

            view_invoice.modal('toggle');

            view_invoice_invoice_code.html(response[0]['invoice_code']);
            view_billing_to.html();
            view_invoice_billing_address.html(response[0]['billing_address']);
            view_invoice_warehouse_name.html(response[0]['getwarehouse']['name']);
            view_invoice_warehouse_address.html(response[0]['getwarehouse']['address']);
            view_invoice_warehouse_tel.html(response[0]['getwarehouse']['telephone']);
            view_invoice_warehouse_email.html(response[0]['getwarehouse']['email']);
            view_invoice_referance.html((response[0]['referance'] == '' ? '-' : response[0]['referance']));
            view_invoice_date.html('Date: ' + formatDate(response[0]['created_at']));
            view_invoice_status.html((response[0]['status'] == 1 ? 'Approved' : 'Unavailable'));

            tbody = '';

            $.each(response[0]['getproducts'], function (key, value) {
                tbody += '<tr>' +
                    '<td>' + ++key + '</td>' +
                    '<td>' + value['getshp']['getproduct']['code'] + ' - ' + value['getshp']['getproduct']['lang1_name'] + '</td>' +
                    '<td>' + formatter.format(value['unit_price']) + '</td>' +
                    '<td class="text-end">' + value['qty'] + ' ' + value['getshp']['getproduct']['get_measurement']['symbol'] + '</td>' +
                    '<td class="text-end">' + formatter.format(value['net_total']) + '</td>' +
                    '<td class="text-end">' + value['getvat']['value'] + '%</td>' +
                    '<td class="text-end">' + formatter.format(value['total']) + '</td>' +
                    '</tr>'

            });

            invoice_net_total = response[0]['total'] * (100 - response[0]['get_vat']['value']) / 100;

            tbody += '<tr>' +
                '<td colspan="6" class="text-end font-weight-500">Total Net Amount</td>' +
                '<td class="text-end font-weight-500">' + formatter.format(invoice_net_total) + '</td>' +
                '</tr>';

            tbody += '<tr>' +
                '<td colspan="6" class="text-end font-weight-500">VAT</td>' +
                '<td class="text-end font-weight-500">' + response[0]['get_vat']['value'] + '%</td>' +
                '</tr>';

            tbody += '<tr>' +
                '<td colspan="6" class="text-end font-weight-500">Total Net Amount</td>' +
                '<td class="text-end font-weight-500">' + formatter.format(response[0]['total']) + '</td>' +
                '</tr>';

            view_invoice_table.html(tbody);

            view_inovice_print_btn.attr('onclick', 'view_invoice_print(' + response[0]['id'] + ')');

        }
    });

}

// INVOICE RETURN

function invoice_discontinue_func(id) {

    Notiflix.Confirm.Show('Confirmation Required', 'Please Confirm to Discontinue Invoice?', 'Yes', 'No',
        function () {

            $.ajax({
                type: "GET",
                url: "/admin/invoice/db/discontinueInvoice",
                data: {
                    id: id,
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
                            administration_wise_invoices.ajax.reload(null, false);
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

}

var edit_invoice_invoice_code = $('#edit_invoice_invoice_code');
var edit_billing_to = $('#edit_billing_to');
var edit_invoice_billing_address = $('#edit_invoice_billing_address');
var edit_invoice_warehouse_name = $('#edit_invoice_warehouse_name');
var edit_invoice_warehouse_address = $('#edit_invoice_warehouse_address');
var edit_invoice_warehouse_tel = $('#edit_invoice_warehouse_tel');
var edit_invoice_warehouse_email = $('#edit_invoice_warehouse_email');
var edit_invoice_referance = $('#edit_invoice_referance');
var edit_invoice_date = $('#edit_invoice_date');
var edit_invoice_status = $('#edit_invoice_status');
var edit_invoice_payment_status = $('#edit_invoice_payment_status');
var edit_inovice_save_btn = $('#edit_inovice_save_btn');

var edit_invoice_table = $('#edit_invoice_table');
var edit_invoice = $('#edit_invoice');

var idArray_list = Array();

function invoice_return_func(id) {

    idArray_list = Array();

    $.ajax({
        type: "GET",
        url: "/admin/invoice/view/invoice",
        data: {
            id: id,
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            edit_invoice_invoice_code.html(response[0]['invoice_code']);
            edit_billing_to.html();
            edit_invoice_billing_address.html(response[0]['billing_address']);
            edit_invoice_warehouse_name.html(response[0]['getwarehouse']['name']);
            edit_invoice_warehouse_address.html(response[0]['getwarehouse']['address']);
            edit_invoice_warehouse_tel.html(response[0]['getwarehouse']['telephone']);
            edit_invoice_warehouse_email.html(response[0]['getwarehouse']['email']);
            edit_invoice_referance.html((response[0]['referance'] == '' ? '-' : response[0]['referance']));
            edit_invoice_date.html('Date: ' + formatDate(response[0]['created_at']));
            edit_invoice_status.html((response[0]['status'] == 1 ? 'Approved' : 'Unavailable'));

            tbody = '';

            $.each(response[0]['getproducts'], function (key, value) {

                idArray_list.push([value['id'], value['shp_id']]);

                tbody += '<tr>' +
                    '<td>' + ++key + '</td>' +
                    '<td>' + value['getshp']['getproduct']['code'] + ' - ' + value['getshp']['getproduct']['lang1_name'] + '</td>' +
                    '<td>' + formatter.format(value['unit_price']) + '</td>' +
                    '<td class="text-end"><input type="number" id="qty' + value['shp_id'] + '" class="form-control form-control-sm" value="' + value['qty'] + '" /></td>' +
                    '<td class="text-end">' + value['getshp']['getproduct']['get_measurement']['symbol'] + '</td>' +
                    '<td class="text-end">' + formatter.format(value['net_total']) + '</td>' +
                    '<td class="text-end">' + value['getvat']['value'] + '%</td>' +
                    '<td class="text-end">' + formatter.format(value['total']) + '</td>' +
                    '</tr>'
            });

            invoice_net_total = response[0]['total'] * (100 - response[0]['get_vat']['value']) / 100;

            tbody += '<tr>' +
                '<td colspan="7" class="text-end font-weight-500">Total Net Amount</td>' +
                '<td class="text-end font-weight-500">' + formatter.format(invoice_net_total) + '</td>' +
                '</tr>';

            tbody += '<tr>' +
                '<td colspan="7" class="text-end font-weight-500">VAT</td>' +
                '<td class="text-end font-weight-500">' + response[0]['get_vat']['value'] + '%</td>' +
                '</tr>';

            tbody += '<tr>' +
                '<td colspan="7" class="text-end font-weight-500">Total Net Amount</td>' +
                '<td class="text-end font-weight-500">' + formatter.format(response[0]['total']) + '</td>' +
                '</tr>';

            edit_inovice_save_btn.html('<button class="btn btn-primary me-1" onclick="edit_invoice_product_qty_func()">' +
                '<i class="fa fa-edit me-1" aria-hidden="true"></i> Save Invoice' +
                '</button>');

            edit_invoice_table.html(tbody);
            edit_invoice.modal('toggle');

        }
    });
}

function edit_invoice_product_qty_func() {

    var productQtyArray = Array();

    idArray_list.forEach(element => {
        element_id = '#qty' + element[1];
        productQtyArray.push([element, $(element_id).val()]);
    });

    Notiflix.Confirm.Show('Confirmation Required', 'Please Confirm to Stock Edit of Invoice?', 'Yes', 'No',
        function () {

            $.ajax({
                type: "GET",
                url: "/admin/invoice/db/editStockOfInvoice",
                data: {
                    product_array: productQtyArray,
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
                            edit_invoice.modal('hide');
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
}

// INVOICE RETURN END
new_customer_form = $("#new_customer");
new_customer_modal = $("#new_customer_modal");
update_customer_modal = $("#update_customer_modal");

var update_customer_name = $("#update_customer_name");
var update_customer_company_name = $("#update_customer_company_name");
var update_customer_registration_number = $("#update_customer_registration_number");
var update_customer_street_address = $("#update_customer_street_address");
var update_customer_city = $("#update_customer_city");
var update_customer_tel1 = $("#update_customer_tel1");
var update_customer_tel2 = $("#update_customer_tel2");
var update_customer_email = $("#update_customer_email");
var update_customer_bank_details = $("#update_customer_bank_details");
var update_customer_form = $("#update_customer_form");

var customer_table = $('#customer_list').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: true,
    pageLength: 20,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default'
    },
    {
        extend: 'csv',
        className: 'btn btn-default'
    }
    ],
    ajax: {
        url: '/admin/customer/get/customerList',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});

new_customer_form.on('submit', function (e) {
    e.preventDefault();

    Notiflix.Confirm.Show('Customer Save Confirmation', 'Please confirm to save this supplier?', 'Confirm', 'Ignore', function () {

        var formData = new FormData(new_customer_form[0]);

        $.ajax({
            method: "POST",
            url: "/admin/customer/db/save",
            data: formData,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
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

                        new_customer_modal.modal('hide');
                        new_customer_form.trigger("reset");
                        customer_table.ajax.reload(null, false);
                    }
                } else {
                    $.each(response.error, function (key, value) {
                        Notiflix.Notify.Failure(value);
                    });
                }
            }
        });

    }, function () { });
});

function createCustomerModalView(data) {
    update_customer_modal.modal('toggle');

    update_customer_name.val(data['name']);
    update_customer_company_name.val(data['company_name']);
    update_customer_registration_number.val(data['company_regis']);
    update_customer_street_address.val(data['street_address']);
    update_customer_city.val(data['city']);
    update_customer_tel1.val(data['tel1']);
    update_customer_tel2.val(data['tel2']);
    update_customer_email.val(data['email']);
    update_customer_bank_details.val(data['bank_details']);

}

function update_customer_func_view(id) {

    $.ajax({
        type: "GET",
        url: "/admin/supplier/get/customer_view_for_update",
        data: { id: id },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();
            createCustomerModalView(response);
        }
    });

}

update_customer_form.on('submit', function (e) {
    e.preventDefault();

    Notiflix.Confirm.Show('Customer Update Confirmation', 'Please confirm to update this customer?', 'Confirm', 'Ignore', function () {

        var formData = new FormData(update_customer_form[0]);

        $.ajax({
            method: "POST",
            url: "/admin/customer/db/updateCustomer",
            data: formData,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
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
                        update_customer_form.trigger("reset");
                        update_customer_modal.modal('hide');
                        customer_table.ajax.reload(null, false);
                    }
                } else {
                    $.each(response.error, function (key, value) {
                        Notiflix.Notify.Failure(value);
                    });
                }
            }
        });

    }, function () { });

});

function change_customer_status_func(id, status) {

    Notiflix.Confirm.Show('Customer Edit Confirmation', 'Please confirm to change status this customer?', 'Confirm', 'Ignore', function () {

        $.ajax({
            type: "GET",
            url: "/admin/customer/db/changeStatus",
            data: {
                id: id,
                status: status,
            },
            success: function (response) {

                if (response['type'] == 'error') {
                    Notiflix.Notify.Failure(response['des']);
                } else if (response['type'] == 'success') {
                    customer_table.ajax.reload(null, false);
                }

            }
        });

    }, function () { });
}

var view_purchase_payment = $('#view_purchase_payment');

function invoice_add_payment_func(id) {

    view_purchase_payment.modal('toggle');

}

// 2022/01/03 UPDATE
function view_invoice_print(id) {

    $.ajax({
        type: "GET",
        url: "/admin/invoice/print",
        data: {
            id: id,
        },
        beforeSend: function () {
            Notiflix.Loading.Pulse();
        },
        success: function (response) {
            Notiflix.Loading.Remove();

            if (response == 2) {
                Notiflix.Notify.Warning('Something Wrong.');
            } else {
                printReport(response);
            }

        }
    });

}

var reservation_list = $('#reservation_list').DataTable({
    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
    lengthMenu: [10, 20, 30, 40, 50],
    responsive: false,
    pageLength: 20,
    searching: true,
    paging: true,
    buttons: [{
        extend: 'print',
        className: 'btn btn-default d-none'
    },
    {
        extend: 'csv',
        className: 'btn btn-default d-none'
    }
    ],
    ajax: {
        url: '/admin/get/reservationList',
        dataSrc: ''
    },
    createdRow: function (row, data, dataIndex, cells) {
        $(cells).addClass('py-1 align-middle');
    }
});





