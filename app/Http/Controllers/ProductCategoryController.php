<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;


class ProductCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        Session::forget('category_media');
        Session::forget('current_view_category');
        $categoryCode = 'CAT/' . date('smy') . '/' . str_pad((new ProductCategory)->getProductCategoryCount() + 1, 3, '0', STR_PAD_LEFT);
        return view('/back_end/category', compact('categoryCode'));
    }

    public function productCategorySuggetions(Request $request)
    {
        $data = array();

        foreach ((new ProductCategory)->suggetions($request->all()) as $productCategory) {
            $data[] = [
                'id' => $productCategory->id,
                'name' => $productCategory->name . ' ' . '(' . $productCategory->code . ')',
            ];
        }

        return response()->json($data, 200);
    }

    public function uploadFile(Request $request)
    {

        $extentions = array('jpg', 'png', 'jpeg');
        $filestorelocation = public_path('/' . env('CATEGORY_FILE_PATH'));
        $requestFile = null;
        $uploadOk = 0;

        if ($request->hasFile('select_file')) {
            $requestFile = $request->file('select_file');
            $imageType = $requestFile->getClientOriginalExtension();
        } else {
            return response()->json([
                'code' => 1,
                'type' => 'error',
                'des' => 'Please select a file before upload',
                'refresh_status' => 1,
                'feild_reset_status' => 1,
            ]);
        }

        if (in_array(strtolower($imageType), $extentions)) {
            $uploadOk = 1;
        } else {
            return response()->json([
                'code' => 2,
                'type' => 'error',
                'des' => 'Invalid file extentions, Valid only .JPG / .PNG or .JPEG',
                'refresh_status' => 1,
                'feild_reset_status' => 1,
            ]);
        }

        if ($uploadOk == 1) {

            $filename = time() . $requestFile->getClientOriginalName();
            $requestFile->move($filestorelocation, $filename);
            
            $data = [
                'name' => $filename,
                'alt' => $requestFile->getClientOriginalName(),
                'type' => $requestFile->getClientOriginalExtension(),
            ];

            $category_media = (new Media)->add($data);

            Session::put('category_media', $category_media);

            return response()->json([
                'code' => 3,
                'type' => 'success',
                'des' => 'File uploaded successfully',
                'refresh_status' => 1,
                'feild_reset_status' => 1,
            ]);
        }
    }

    public function save(Request $request)
    {

        if (Session::has('category_media')) {
            $media_id = Session::get('category_media')->id;
        } else {
            $media_id = 1;
        }

        if (Session::has('current_view_category')) {
            // Update Category

            $data = [
                'name' => $request->name,
                'media_id' => $media_id,
            ];

            (new ProductCategory)->edit('id', Session::get('current_view_category')->id, $data);
            Session::forget('category_media');
            Session::forget('current_view_category');

            return response()->json([
                'code' => 1,
                'type' => 'success',
                'des' => 'Category updated successfully',
                'refresh_status' => 2,
                'feild_reset_status' => 2,
            ]);
        } else {
            // Save Category
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()]);
            }

            $data = [
                'code' => 'CAT/' . date('smy') . '/' . str_pad((new ProductCategory)->getProductCategoryCount() + 1, 3, '0', STR_PAD_LEFT),
                'name' => $request->name,
                'category_id' => $request->category_id,
                'media_id' => $media_id,
            ];

            (new ProductCategory)->add($data);
            Session::forget('category_media');

            return response()->json([
                'code' => 1,
                'type' => 'success',
                'des' => 'Category saved successfully',
                'refresh_status' => 2,
                'feild_reset_status' => 2,
            ]);
        }
    }

    public function categoryList()
    {
        $records = (new ProductCategory)->getCategoryList();

        $tableData = [];

        foreach ($records as $index => $value) {

            switch ($value->status) {
                case 1:
                    $statusText = 'Active';
                    $statusColor1 = 'success';
                    $statusColor2 = 'green-custom';
                    break;

                case 2:
                    $statusText = 'In-active';
                    $statusColor1 = 'danger';
                    $statusColor2 = 'red';
                    break;

                default:
                    $statusText = '-';
                    $statusColor1 = 'default';
                    $statusColor2 = 'white';
                    break;
            }

            if ($value->category_id == null) {
                $catText = 'Main Category';
                $catColor1 = 'black';
                $catColor2 = 'gray';
            } else {
                $catText = 'Sub Category';
                $catColor1 = 'black';
                $catColor2 = 'yellow';
            }

            $status = '<span class="badge rounded-1 my-1 font-weight-500 bg-'
                . $statusColor2 .
                '-100 text-'
                . $statusColor1 .
                ' px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">
                    <i class="fa fa-circle text-' . $statusColor1 . ' fs-9px fa-fw me-5px"></i>'
                . $statusText .
                '</span>';

            $category_status = '<span class="badge bg-' . $catColor2 .
                '-100 text-' . $catColor1 .
                '-transparent-5 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center"><i class="fa fa-circle text-' . $catColor1 .
                '-500 fs-9px fa-fw me-5px"></i>' . $catText .
                '</span>';

            $actions = '<div class="mt-md-0 mt-2">' .
                '<a href="#" data-bs-toggle="dropdown"class="btn btn-sm btn-default text-decoration-none">' .
                '<i class="fa fa-bars" aria-hidden="true"></i></a><div class="dropdown-menu bg-white rounded-0">' .
                '<a class="dropdown-item font-weight-400 small_font" onclick="update_category_func(' . $value->id . ')">' .
                '<i class="fa fa-pencil-square-o pe-3" aria-hidden="true"></i>'.
                'Update' .
                '</a>'.
                (($value->status == 1) ?
                    '<a onclick="change_category_status_func(' . $value->id . ',2)" class="dropdown-item font-weight-400 small_font">' .
                    '<i class="fa fa-ban pe-3" aria-hidden="true"></i>' .
                    'Disable' .
                    '</a>
                ' : '
                <a onclick="change_category_status_func(' . $value->id . ',1)" class="dropdown-item font-weight-400 small_font">' .
                    '<i class="fa fa-check pe-3" aria-hidden="true"></i>' .
                    'Enable' .
                    '</a>
                ') .
                '</div></div>';

            $media = Media::where('id', $value->media_id)->first();

            if (!empty($media->name)) {
                '<img src="' . URL::to('') . '/' . env('CATEGORY_FILE_PATH') . '/' . $media->name . '" alt="" style="width: 25px; height: 25px;">';
            } else {
                $image = '<img src="' . URL::to('') . '/' . env('CATEGORY_FILE_PATH') . '/defaultIcon.png' . $media->name . '" alt="" style="width: 25px; height: 25px;">';
            }

            $tableData[] = [
                ++$index,
                $actions,
                '<img src="' . URL::to('') . '/' . env('CATEGORY_FILE_PATH') . '/' . $media->name . '" alt="" style="width: 25px; height: 25px;">',
                $value->code,
                $value->name,
                ((!empty(ProductCategory::find($value->category_id)->name)) ? ProductCategory::find($value->category_id)->name : '-'),
                $status,
            ];
        }

        return $tableData;
    }

    public function categoryViewForUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        Session::put('current_view_category', ProductCategory::find($request->id));
        return (new ProductCategory)->getCategoryById($request->id)->first();
    }

    public function categoryViewForDisable(Request $request)
    {
        (new ProductCategory)->edit('id', $request->id, ['status' => $request->status]);

        (($request->status == 1) ? $response_description = 'Category Enabled' : $response_description = 'Category Disabled');

        return response()->json([
            'code' => 1,
            'type' => 'success',
            'des' => $response_description,
            'refresh_status' => 2,
            'feild_reset_status' => 2,
        ]);
    }
}
