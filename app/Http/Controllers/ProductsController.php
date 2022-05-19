<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Measurement;
use App\Models\Media;
use App\Models\ProductCategory;
use App\Models\ProductHasMedia;
use App\Models\ProductHasPrices;
use App\Models\Products;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index()
    {
        $measurement = (new Measurement)->getActiveAll();
        $brand = (new Brand)->getActiveAll();
        $category = (new ProductCategory)->getActiveAll();
        $productType = (new ProductType)->getActiveAll();
        $productCode = 'PRDT/' . date('smy') . '/' . str_pad((new Products)->getProductCount() + 1, 3, '0', STR_PAD_LEFT);
        return view('/back_end/products', compact('measurement', 'brand', 'category', 'productType', 'productCode'));
    }

    public function save(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'new_product_sku' => 'required',
            // 'new_product_name_lang1' => 'required',
            // 'new_product_default_price' => 'required|numeric|min:1',
            // 'new_product_lsaq' => 'required|numeric|min:1',
            // 'new_product_select_category' => 'required',
            // 'new_product_select_mes' => 'required',
            // 'new_product_select_brand' => 'required',
            // 'new_product_select_product_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $extentions = array('jpg', 'png', 'jpeg');
        $filestorelocation = public_path('/' . env('PRODUCT_FILE_PATH'));
        $product_image1_url = null;
        $product_image1_url_uploadOk = 0;
        $product_image2_url_uploadOk = 0;

        $img1_data = null;
        $img2_data = null;

        if ($request->hasFile('product_image1_upload') && $request->file('product_image1_upload')->getSize() / 1024 <= 750) {
            $product_image1_url = $request->file('product_image1_upload');
            $product_image1_url_image_type = $product_image1_url->getClientOriginalExtension();
        } else {
            return response()->json([
                'code' => 1,
                'type' => 'error',
                'des' => 'Please select a file before upload',
                'refresh_status' => 1,
                'feild_reset_status' => 1,
            ]);
        }

        if (in_array(strtolower($product_image1_url_image_type), $extentions)) {
            $product_image1_url_uploadOk = 1;
        } else {
            return response()->json([
                'code' => 2,
                'type' => 'error',
                'des' => 'Invalid file extentions, Valid only .JPG / .PNG or .JPEG',
                'refresh_status' => 1,
                'feild_reset_status' => 1,
            ]);
        }

        if ($request->hasFile('product_image2_upload') && $request->file('product_image2_upload')->getSize() / 1024 <= 750) {
            $product_image2_url = $request->file('product_image2_upload');
            $product_image2_url_image_type = $product_image1_url->getClientOriginalExtension();
        } else {
            return response()->json([
                'code' => 1,
                'type' => 'error',
                'des' => 'Please select a file before upload',
                'refresh_status' => 1,
                'feild_reset_status' => 1,
            ]);
        }

        if (in_array(strtolower($product_image2_url_image_type), $extentions)) {
            $product_image2_url_uploadOk = 1;
        } else {
            return response()->json([
                'code' => 2,
                'type' => 'error',
                'des' => 'Invalid file extentions, Valid only .JPG / .PNG or .JPEG',
                'refresh_status' => 1,
                'feild_reset_status' => 1,
            ]);
        }

        if ($product_image1_url_uploadOk == 1) {

            $filename = time() . $product_image1_url->getClientOriginalName();
            $product_image1_url->move($filestorelocation, $filename);

            $data1 = [
                'name' => $filename,
                'alt' => $product_image1_url->getClientOriginalName(),
                'type' => $product_image1_url->getClientOriginalExtension(),
            ];

            $img1_data = (new Media)->add($data1);
        }

        if ($product_image2_url_uploadOk == 1) {

            $filename = time() . '2' . $product_image2_url->getClientOriginalName();
            $product_image2_url->move($filestorelocation, $filename);

            $data2 = [
                'name' => $filename,
                'alt' => $product_image2_url->getClientOriginalName(),
                'type' => $product_image2_url->getClientOriginalExtension(),
            ];

            $img2_data = (new Media)->add($data2);
        }

        $new_product_sku = $request->new_product_sku;
        $new_product_name_lang1 = $request->new_product_name_lang1;
        $new_product_name_lang2 = $request->new_product_name_lang2;
        $new_product_name_lang3 = $request->new_product_name_lang3;
        $default_price = $request->new_product_default_price;
        $new_product_select_category = $request->new_product_select_category;
        $new_product_select_mes = $request->new_product_select_mes;
        $new_product_select_brand = $request->new_product_select_brand;
        $new_product_select_product_type = $request->new_product_select_product_type;
        $new_product_lsaq = $request->new_product_lsaq;
        $new_product_des = $request->new_product_des;

        $product_data = [
            'code' => $new_product_sku,
            'lang1_name' => $new_product_name_lang1,
            'lang2_name' => $new_product_name_lang2,
            'lang3_name' => $new_product_name_lang3,
            'default_price' => $default_price,
            'description' => $new_product_des,
            'low_stock_alert_qty' => $new_product_lsaq,
            'product_category_id' => $new_product_select_category,
            'measurement_id' => $new_product_select_mes,
            'brand_id' => $new_product_select_brand,
            'product_type_id' => $new_product_select_product_type,
            'status' => 1,
        ];

        $product = (new Products)->add($product_data);

        (new ProductHasMedia)->add([
            'product_id' => $product->id,
            'media_id' => $img1_data->id,
        ]);

        (new ProductHasMedia)->add([
            'product_id' => $product->id,
            'media_id' => $img2_data->id,
        ]);

        return response()->json([
            'code' => 'PRDT/' . date('smy') . '/' . str_pad((new Products)->getProductCount() + 1, 3, '0', STR_PAD_LEFT),
            'type' => 'success',
            'des' => 'Successfully saved product',
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }

    public function getAllProducts()
    {
        return (new Products)->getAllProducts();
    }

    public function productList_backend()
    {
        $allproducts = $this->getAllProducts();
        return view('/back_end/product_list', compact('allproducts'));
    }

    public function changeStatus(Request $request)
    {

        (new Products)->edit('id', $request->id, ['status' => $request->status]);

        (($request->status == 1) ? $response_description = 'Product Enabled Successfully' : $response_description = 'Product Disabled Successfully');

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => $response_description,
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }

    public function productSuggestions(Request $request)
    {
        $data = array();

        foreach ((new Products)->suggetions($request->all()) as $product) {
            $data[] = [
                'id' => $product->id,
                'name' => $product->lang1_name . ' ' . '(' . ProductCategory::find($product->product_category_id)->name . ')',
            ];
        }

        return response()->json($data, 200);
    }

    public function productQuickView(Request $request)
    {
        $product = (new Products)->getProductById($request->id);
        Session::put('selected_product', $product);
        return $product;
    }

    public function productDescriptionEdit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'update_product_des' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $product_obj = Session::get('selected_product')->id;
        $update_data = ['description' => $request->update_product_des];
        (new Products)->edit('id', $product_obj, $update_data);
        Session::forget('selected_product');
        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => 'Successfully Update the Product Description',
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }

    public function productDefaultPriceEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $update_data = ['default_price' => $request->price];
        (new Products)->edit('id', $request->id, $update_data);

        $previousPhpData = (new ProductHasPrices)->getProductHasPriceById($request->id);

        $productHasPrice = [
            'product_id' => $request->id,
            'price' => $request->price,
            'status' => 1,
        ];

        foreach ($previousPhpData as $key => $php) {
            (new ProductHasPrices)->edit('product_id', $php->product_id, ['status' => 2]);
        }

        (new ProductHasPrices)->add($productHasPrice);

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => 'Successfully Update the Product Default Price',
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }

    public function updateCategoryForview(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        Session::put('selected_product_id_for_edit_category', $request->id);
        $product = Products::find($request->id);
        return [(new ProductCategory)->getCategoryList(), $product->lang1_name, $product->product_category_id];
    }

    public function updateCategory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (Session::has('selected_product_id_for_edit_category')) {
            (new Products)->edit('id', Session::get('selected_product_id_for_edit_category'), ['product_category_id' => $request->category_id]);
            Session::forget('selected_product_id_for_edit_category');
            return response()->json([
                'code' => 1,
                'type' => 'success',
                'des' => 'Successfully Update the Product Category',
                'refresh_status' => 2,
                'feild_reset_status' => 2,
            ]);
        } else {
            return response()->json([
                'code' => 1,
                'type' => 'error',
                'des' => 'Unable to update',
                'refresh_status' => 2,
                'feild_reset_status' => 2,
            ]);
        }
    }

    public function getProductPrice(Request $request)
    {
        return (new Products)->getProductById($request->id)->default_price;
    }
}
