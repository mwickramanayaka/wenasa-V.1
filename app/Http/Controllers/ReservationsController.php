<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ReservationHasProducts;
use App\Models\Reservations;
use App\Models\Rooms;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    public function getReservationList()
    {
        $records = (new Reservations)->orderBy('date', 'DESC')->get();

        $tableData = [];

        foreach ($records as $key => $data) {

            switch ($data->status) {
                case 1:
                    $statusText = 'Active';
                    $statusColor1 = 'success';
                    $statusColor2 = 'green';
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

            $status = '<span class="badge rounded-1 my-1 font-weight-500 bg-'
                . $statusColor2 .
                '-100 text-'
                . $statusColor1 .
                ' px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">
                <i class="fa fa-circle text-' . $statusColor1 . ' fs-9px fa-fw me-5px"></i>'
                . $statusText .
                '</span>';

            $itemsObj = (new ReservationHasProducts)
                ->where('reservation_id', $data->id)
                ->get();

            $itemName = '';

            foreach ($itemsObj as $key => $item) {
                $itemName =  Products::find($item->product_id)->lang1_name . ' : ' . $item->qty . '/ ' . $itemName;
            }

            $tableData[] = [
                ++$key,
                User::find($data->user_id)->name,
                User::find($data->user_id)->tel,
                Rooms::find($data->room_id)->name,
                $data->date,
                $itemName,
                'LKR. ' . number_format($data->total_amount, 2),
                $status,
            ];
        }

        return $tableData;
    }
}
