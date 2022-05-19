<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartHasProducts;
use App\Models\Category;
use App\Models\Customer;
use App\Models\DeliverAreas;
use App\Models\Order;
use App\Models\Products;
use App\Models\ReservationHasProducts;
use App\Models\Reservations;
use App\Models\Response;
use App\Models\Rooms;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\vat;
use App\Models\WebUser;
use App\Models\WebUserDeliveryDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function main()
    {
        Session::forget('productList');
        $rooms = (new Rooms)->where('status', 1)->get();
        $products = (new Products)->where('status', 1)->get();
        return view('home', compact('products', 'rooms'));
    }

    public function index()
    {

        $collapse = 1;
        return view('/back_end/dashboard', compact('collapse'));

        //blog routes frontend
        // $setting = Setting::find(1);
        // $all_categories = Category::where('status','0')->get();
        // $latest_posts = Post::where('status','0')->get()->take(6);
        // $latest_posts = Post::where('status','0')->orderBy('created_at','ASC')->get()->take(6);
        // return view('frontend.index', compact('all_categories','latest_posts' ,'setting'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin');
    }

    public function clearSessions()
    {
        Session::forget('login_session');
        return Response::ResponseBody(
            '200',
            'Successfully Cleard Session',
            null,
            null
        );
    }

    public function customerLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return Response::ResponseBody(
                '200',
                null,
                null,
                $validator->errors()->all()
            );
        }

        // USER TYPES
        // 1 => ADMIN
        // 2 => CASHIER
        // 3 => CUSTOMER

        $user = User::where('email', $request->email)->where('user_type_id', 3)->first();

        if (!empty($user)) {

            Session::put('login_session', $user);

            return Response::ResponseBody(
                '200',
                null,
                null,
                $user
            );
        } else {
            return Response::ResponseBody(
                '200',
                'Unable to find any user accounts',
                'danger',
                null
            );
        }
    }

    public function addProductToSession(Request $request)
    {

        $isNew = true;
        $records = [];

        if (Session::has('productList')) {
            $records =  Session::get('productList');
        }

        foreach ($records as $key => $item) {
            if ($item[0] == $request->product_id) {
                $records[$key][0] = $request->product_id;
                $records[$key][1] = $request->qty;
                $records[$key][2] = Products::find($request->product_id)->lang1_name;
                $records[$key][3] = Products::find($request->product_id)->default_price;
                $records[$key][4] = Products::find($request->product_id)->default_price * $request->qty;
                $isNew = false;
                break;
            }
        }

        if ($isNew) {
            $records[] = [
                $request->product_id,
                $request->qty,
                Products::find($request->product_id)->lang1_name,
                Products::find($request->product_id)->default_price,
                Products::find($request->product_id)->default_price * $request->qty
            ];
        }

        Session::put('productList', $records);
        return Session::get('productList');
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'tel' => 'required|numeric|digits:10',
            'address' => 'required|string',
            'date' => 'required|date',
            'room' => 'required|numeric|exists:rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $total_amount = 0;

        if (Session::has('productList')) {
            foreach (Session::get('productList') as $key => $item) {
                $total_amount += $item[4];
            }
        } else {
            return 2;
        }

        $check_room_availability = (new Reservations)
            ->where('date', $request->date)
            ->where('room_id', $request->room)
            ->get();

        if (count($check_room_availability) != 0) {
            return 3;
        }

        if (Session::has('login_session')) {
            $user = Session::get('login_session');
        } else if ((new User)->where('email', $request->email)->first()) {
            $user = (new User)->where('email', $request->email)->first();
        } else {
            $user_data = [
                'user_type_id' => 3,
                'name' => $request->name,
                'email' => $request->email,
                'tel' => $request->tel,
                'address' => $request->address,
                'password' => Hash::make($request->tel),
            ];

            $user = User::create($user_data);
        }

        $reseravtion_data = [
            'user_id' => $user->id,
            'room_id' => $request->room,
            'date' => $request->date,
            'total_amount' =>  $total_amount,
            'status' =>  1,
        ];

        $reservationObj = Reservations::create($reseravtion_data);

        foreach (Session::get('productList') as $key => $item) {

            $reservationHasProducts_data = [
                'reservation_id' => $reservationObj->id,
                'product_id' => $item[0],
                'qty' => $item[1],
                'total_amount' => floatval($item[1]) *  floatval($item[2]),
                'status' => 1,
            ];

            ReservationHasProducts::create($reservationHasProducts_data);
        }

        Session::forget('productList');
        return 1;
    }

//blog routes frontend
    // public function viewCategory($category_slug)
    // {
    //     $category = Category::where('slug', $category_slug)->where('status', '0')->first();
    //     if ($category) {
    //         $post = Post::where('category_id', $category->id)->where('status', '0')->paginate(5);
    //         return view('frontend.post.index',compact('post','category'));
    //     } else {
    //         return redirect('/');
    //     }
      
    // }

    // public function viewPost(string $category_slug, string $post_slug)
    // {
    //     $category = Category::where('slug', $category_slug)->where('status', '0')->first();
    //     if ($category) {
    //         $post = Post::where('category_id', $category->id)->where('slug', $post_slug)->where('status', '0')->first();
    //         $latest_posts = Post::where('category_id', $category->id)->where('status', '0')->orderBy('created_at','DESC')->get()->take(8);
    //         return view('frontend.post.view',compact('post','latest_posts'));
    //     } else {
    //         return redirect('/');
    //     }
    // }
}
