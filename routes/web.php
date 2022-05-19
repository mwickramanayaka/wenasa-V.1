<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\GrnTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VatController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationsController;
use App\Models\CurrencyType;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceType;
use App\Models\Merchant;
use App\Models\ServiceCharge;
use App\Models\warehouses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes([
    'register' => true,
    'reset' => false,
    'verify' => false,
]);

Route::get('/', [HomeController::class , 'main']);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/logout', [HomeController::class, 'logout'])->middleware('auth');

Route::get('/admin', function () {
    Session::forget('invoiceProducts');
    Session::forget('data');

    $invoiceCode = str_pad((new Invoice)->getInvoiceCount() + 1, 8, '0', STR_PAD_LEFT);

    $invoice_types = InvoiceType::where('status', 1)->get();
    $merchants = Merchant::where('status', 1)->get();
    $service_charges = ServiceCharge::where('status', 1)->get();
    $currency_type = CurrencyType::where('status', 1)->get();
    $empObj = Employee::where('status', 1)->get();

    return view('/back_end/invoice', compact(
        'invoiceCode',
        'invoice_types',
        'merchants',
        'service_charges',
        'currency_type',
        'empObj'
    ));
})->middleware('auth');

//blog routes frontend
Route::get('category/{category_slug}', [HomeController::class, 'viewCategory']);
Route::get('category/{category_slug}/{post_slug}', [HomeController::class, 'viewPost']);

//blog routes backend
Route::get('/admin/category', [App\Http\Controllers\Admin\CategoryController::class, 'index']);
//

Route::get('/CLEAR_SESSIONS', [HomeController::class, 'clearSessions']);
Route::get('/LOGIN', [HomeController::class, 'customerLogin']);
Route::get('/SAVE_PRODUCT_TO_SESSION', [HomeController::class, 'addProductToSession']);
Route::get('/CHECKOUT', [HomeController::class, 'checkout']);

// On Ready Functons' Routes
Route::get('/admin/vat/get/vatlist', [VatController::class, 'getAllVAT']);
Route::get('/admin/grnType/get/grnTypelist', [GrnTypeController::class, 'getAllGrnType']);
Route::get('/admin/warehouse/get/warehouselist', [warehouses::class, 'getAllWarehouses']);
Route::get('/admin/supplier/get/suggetions', [SupplierController::class, 'supplierSuggestions']);

Route::get('/admin/product/category', [ProductCategoryController::class, 'index'])->middleware('auth')->middleware('auth');
Route::get('/admin/product/category/get/suggetions', [ProductCategoryController::class, 'productCategorySuggetions'])->middleware('auth');
Route::get('/admin/product/category/db/save', [ProductCategoryController::class, 'save'])->middleware('auth');
Route::get('/admin/product/category/get/categoryList', [ProductCategoryController::class, 'categoryList'])->middleware('auth');
Route::get('/admin/product/category/get/category_view_for_update', [ProductCategoryController::class, 'categoryViewForUpdate'])->middleware('auth');
Route::get('/admin/product/category/get/category_view_for_disable', [ProductCategoryController::class, 'categoryViewForDisable'])->middleware('auth');
Route::post('/admin/product/category/uploadCategoryImage', [ProductCategoryController::class, 'uploadFile'])->name('uploadCategoryImage.action')->middleware('auth');

Route::get('/admin/product', [ProductsController::class, 'index'])->middleware('auth');
Route::post('/admin/db/save', [ProductsController::class, 'save'])->middleware('auth');
Route::get('/admin/product_list', [ProductsController::class, 'productList_backend'])->middleware('auth');
Route::get('/admin/getAllProducts', [ProductsController::class, 'getAllProducts'])->middleware('auth');
Route::get('/admin/changeStatus', [ProductsController::class, 'changeStatus'])->middleware('auth');
Route::get('/admin/product/get/suggetions', [ProductsController::class, 'productSuggestions'])->middleware('auth');
Route::get('/admin/product/get/details', [ProductsController::class, 'productQuickView'])->middleware('auth');
Route::get('/admin/product/get/edit', [ProductsController::class, 'productQuickView'])->middleware('auth');
Route::post('/admin/product/db/updateDescription', [ProductsController::class, 'productDescriptionEdit'])->middleware('auth');
Route::get('/admin/product/db/updateDefaultPrice', [ProductsController::class, 'productDefaultPriceEdit'])->middleware('auth');
Route::get('/admin/product/view/updateCategory', [ProductsController::class, 'updateCategoryForview'])->middleware('auth');
Route::get('/admin/product/db/updateCategory', [ProductsController::class, 'updateCategory'])->middleware('auth');
Route::get('/admin/product/db/getProductPrice', [ProductsController::class, 'getProductPrice'])->middleware('auth');

Route::get('/admin/supplier', [SupplierController::class, 'index'])->middleware('auth');
Route::post('/admin/supplier/db/save', [SupplierController::class, 'save'])->middleware('auth');
Route::get('/admin/supplier/get/supplierList', [SupplierController::class, 'supplierList'])->middleware('auth');
Route::get('/admin/supplier/get/category_view_for_update', [SupplierController::class, 'supplierViewForUpdate'])->middleware('auth');
Route::post('/admin/supplier/db/updateSupplier', [SupplierController::class, 'updateSupplier'])->middleware('auth');
Route::get('/admin/supplier/db/changeStatus', [SupplierController::class, 'changeStatus'])->middleware('auth');

Route::get('/admin/grn', [GrnController::class, 'index'])->middleware('auth');
Route::get('/admin/grn/session/addProduct', [GrnController::class, 'addSessionProduct'])->middleware('auth');
Route::get('/admin/grn/session/grnAddedProductList', [GrnController::class, 'grnAddedProductList'])->middleware('auth');
Route::get('/admin/grn/session/removeProduct', [GrnController::class, 'grnRemoveSessionProduct'])->middleware('auth');
Route::get('/admin/grn/session/getTotal', [GrnController::class, 'getAddedSessionProductTotal'])->middleware('auth');
Route::get('/admin/grn/db/saveGRN', [GrnController::class, 'saveGRN'])->middleware('auth');

Route::get('/admin/grn/get/purchaseList', [GrnController::class, 'purchaseList'])->middleware('auth');
Route::get('/admin/grn/view/purchase', [GrnController::class, 'viewPurchase'])->middleware('auth');

Route::get('/admin/stock', [StockController::class, 'index'])->middleware('auth');
Route::get('/admin/stock/get/allstock', [StockController::class, 'getStock'])->middleware('auth');
Route::get('/admin/stock/get/product-wise_stock', [StockController::class, 'productwiseStock'])->middleware('auth');

Route::get('/admin/customer', [CustomerController::class, 'index'])->middleware('auth');
Route::get('/admin/customer/get/customerList', [CustomerController::class, 'customerList'])->middleware('auth');
Route::post('/admin/customer/db/save', [CustomerController::class, 'save'])->middleware('auth');
Route::get('/admin/supplier/get/customer_view_for_update', [CustomerController::class, 'customerViewForUpdate'])->middleware('auth');
Route::post('/admin/customer/db/updateCustomer', [CustomerController::class, 'updateCustomer'])->middleware('auth');
Route::get('/admin/customer/db/changeStatus', [CustomerController::class, 'changeStatus'])->middleware('auth');

Route::get('/admin/invoice', [InvoiceController::class, 'index'])->middleware('auth');
Route::get('/admin/customer/get/suggetions', [CustomerController::class, 'customerSuggestions']);
Route::get('/admin/customer/get/suggetions/address', [CustomerController::class, 'getCustomerAddressById']);

Route::get('/admin/invoice/session/addProduct', [InvoiceController::class, 'addSessionProduct'])->middleware('auth');
Route::get('/admin/invoices/session/invoiceTableView', [InvoiceController::class, 'invoiceTableView'])->middleware('auth');
Route::get('/admin//invoices/removeFromSession', [InvoiceController::class, 'removeProductFromSession'])->middleware('auth');
Route::get('/admin/invoice/session/getTotal', [InvoiceController::class, 'getInvoiceTotal'])->middleware('auth');
Route::get('/admin/invoice/db/save', [InvoiceController::class, 'saveInvoice'])->middleware('auth');
Route::get('/admin/invoice/db/get/getInvoiceList', [InvoiceController::class, 'getInvoiceList'])->middleware('auth');
Route::get('/admin/invoice/view/invoice', [InvoiceController::class, 'viewInvoice'])->middleware('auth');
Route::get('/admin/invoice/db/discontinueInvoice', [InvoiceController::class, 'discontinueInvoice'])->middleware('auth');
Route::get('/admin/invoice/db/editStockOfInvoice', [InvoiceController::class, 'editStockOfInvoice'])->middleware('auth');

// Route::get('/admin/invoice/print', [InvoiceController::class, 'printInvoice'])->middleware('auth');
Route::get('/admin/invoice/print', [InvoiceController::class, 'printInvoice']);

Route::get('/admin/invoice_list', [InvoiceController::class, 'loadInvoiceList'])->middleware('auth');
// Route::get('/admin/invoice_list', function () {
//     return view('/back_end/invoice_list');
// })->middleware('auth');

Route::get('/admin/grn_list', function () {
    return view('/back_end/grn_list');
});

Route::get('/admin/online_orders', function () {
    return view('/back_end/online_orders');
});

Route::get('/admin/viewReservationList', function () {
    return view('/back_end/viewReservation');
});

Route::get('/admin/get/reservationList' , [ReservationsController::class,'getReservationList'])->middleware('auth');
