<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\stock;
use App\Models\stockHasProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{

    public function index()
    {
        return view('/back_end/view_stock');
    }

    public function getStock()
    {

        $tableData = [];

        $records = (new stockHasProducts)->getAllStock();

        foreach ($records as $key => $value) {

            switch ($value->status) {
                case 1:
                    $statusText = 'Available';
                    $statusColor1 = 'success';
                    $statusColor2 = 'success';
                    break;
                case 2:
                    $statusText = 'Unavailable';
                    $statusColor1 = 'danger';
                    $statusColor2 = 'danger';
                    break;
            }

            $stockStatus = '<span class="badge rounded-1 my-1 font-weight-400 bg-' . $statusColor1 . '-transparent-2 text-' . $statusColor2 . ' px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">' .
                '<i class="fa fa-circle text-' . $statusColor2 . '-transparent-8 fs-9px fa-fw me-5px"></i>'
                . $statusText .
                '</span>';

            $net_in_price = $value->in_price * (100 + $value->vat_value) / 100;
            $net_in_price_text = '';

            ($net_in_price > $value->out_price) ? $net_color = 'text-danger' : $net_color = 'text-success';
            ($net_in_price > $value->out_price) ? $net_icon = 'fa-sort-desc' : $net_icon = 'fa-sort-asc';

            $net_in_price_text = '<span class="' . $net_color . '"><i class="fa ' . $net_icon . ' me-1" aria-hidden="true"></i>' . env('CURRANCY') . ' ' . number_format($value->out_price, 2) . '</span>';

            $tableData[] = [
                ++$key,
                $value['getStock']->code,
                $value['getStock']['getWarehouse']->name,
                $value['getProduct']->lang1_name . ' (' . ProductCategory::find($value['getProduct']->product_category_id)->name . ')',
                env('CURRANCY') . ' ' . number_format($value->in_price, 2),
                $value->qty . ' ' . $value['getProduct']['getMeasurement']->symbol,
                env('CURRANCY') . ' ' . number_format($value->vat_value, 2),
                env('CURRANCY') . ' ' . number_format($net_in_price, 2),
                $net_in_price_text,
                $stockStatus,

            ];
        }

        return $tableData;
    }

    public function productwiseStock()
    {

        $tableData = [];
        $tableDataReferance = [];

        $records = (new stockHasProducts)->getAllStock();

        foreach ($records as $key => $value) {

            switch ($value->status) {
                case 1:
                    $statusText = 'Available';
                    $statusColor1 = 'success';
                    $statusColor2 = 'success';
                    break;
                case 2:
                    $statusText = 'Unavailable';
                    $statusColor1 = 'danger';
                    $statusColor2 = 'danger';
                    break;
            }

            $stockStatus = '<span class="badge rounded-1 my-1 font-weight-400 bg-' . $statusColor1 . '-transparent-2 text-' . $statusColor2 . ' px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">' .
                '<i class="fa fa-circle text-' . $statusColor2 . '-transparent-8 fs-9px fa-fw me-5px"></i>'
                . $statusText .
                '</span';

            if ($key == 0) {

                ($value['getProduct']->low_stock_alert_qty > $value->qty) ? $indicate_color = 'text-danger' : $indicate_color = 'text-success';
                ($value['getProduct']->low_stock_alert_qty > $value->qty) ? $indicate_icon = 'fa-sort-desc' : $indicate_icon = 'fa-sort-asc';
                $indicate_text = '<span class="' . $indicate_color . '"><i class="fa ' . $indicate_icon . ' me-1" aria-hidden="true"></i>' . $value->qty . ' ' . $value['getProduct']['getMeasurement']->symbol . '</span>';

                $tableData[] = [
                    ++$key,
                    $value['getProduct']->code,
                    $value['getProduct']->lang1_name . ' (' . ProductCategory::find($value['getProduct']->product_category_id)->name . ')',
                    $value['getProduct']->low_stock_alert_qty,
                    $indicate_text,
                    $stockStatus
                ];

                $tableDataReferance[] = [
                    $value['getProduct']->id,
                    $value->qty,
                ];
            } else {

                $check_status = true;

                foreach ($tableDataReferance as $tableDatakey => $tableDataRefvalue) {

                    if ($tableDataRefvalue[0] == $value['getProduct']->id) {

                        $tableDataReferance[$tableDatakey][1] = $tableDataRefvalue[1] + $value->qty;

                        ($value['getProduct']->low_stock_alert_qty > $tableDataReferance[$tableDatakey][1]) ? $indicate_color = 'text-danger' : $indicate_color = 'text-success';
                        ($value['getProduct']->low_stock_alert_qty > $tableDataReferance[$tableDatakey][1]) ? $indicate_icon = 'fa-sort-desc' : $indicate_icon = 'fa-sort-asc';
                        $indicate_text = '<span class="' . $indicate_color . '"><i class="fa ' . $indicate_icon . ' me-1" aria-hidden="true"></i>' . $tableDataReferance[$tableDatakey][1] . ' ' . $value['getProduct']['getMeasurement']->symbol . '</span>';

                        $tableData[$tableDatakey][4] = $indicate_text;

                        $check_status = false;
                        break;
                    }
                }

                if ($check_status) {

                    ($value['getProduct']->low_stock_alert_qty > $value->qty) ? $indicate_color = 'text-danger' : $indicate_color = 'text-success';
                    ($value['getProduct']->low_stock_alert_qty > $value->qty) ? $indicate_icon = 'fa-sort-desc' : $indicate_icon = 'fa-sort-asc';
                    $indicate_text = '<span class="' . $indicate_color . '"><i class="fa ' . $indicate_icon . ' me-1" aria-hidden="true"></i>' . $value->qty . ' ' . $value['getProduct']['getMeasurement']->symbol . '</span>';

                    $tableData[] = [
                        ++$key,
                        $value['getProduct']->code,
                        $value['getProduct']->lang1_name. ' (' . ProductCategory::find($value['getProduct']->product_category_id)->name . ')',
                        $value['getProduct']->low_stock_alert_qty,
                        $indicate_text,
                        $stockStatus
                    ];

                    $tableDataReferance[] = [
                        $value['getProduct']->id,
                        $value->qty,
                    ];
                }
            }
        }

        return $tableData;
    }
}
