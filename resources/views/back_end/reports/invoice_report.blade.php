<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>

    <style>
        @page {
            size: A4;
            margin-top: 10;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
                padding-left: 10px;
                padding-right: 20px;
                padding-top: 10px;
                padding-bottom: 10px;
            }
        }

        .font {
            font-family: 'Segoe UI';
        }

        .text-center {
            text-align: center;
        }

        .row {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            margin-top: 5px;
        }

        .col-2 {
            width: 16.66%;
        }

        .col-3 {
            width: 25%;
        }

        .col-4 {
            width: 33.33%
        }

        .col-6 {
            width: 50%;
        }

        .tborderth {
            border-top: 1px solid #212121;
            padding: 5px;
            margin: 0px;
        }

        .tbleft {
            padding-left: 10px;
            border-left: 1px solid #212121
        }

        .tbright {
            padding-right: 10px;
            border-right: 1px solid #212121
        }

        .tborder {
            /* border-left: 1px solid #212121; */
            /* border-right: 1px solid #212121; */
            /* border-top: 1px solid #212121; */
            border-bottom: 1px solid #212121;
            /* padding: 5px; */
            padding-top: 10px;
            padding-bottom: 10px;
            margin: 0px;

        }

        .alright {
            text-align: right
        }

        .smargin {
            padding: 5px;
        }

        .bold-100 {
            font-weight: 500;
        }

        .trcolor {
            background-color: rgb(238, 238, 238);
            -webkit-print-color-adjust: exact;
        }

        .text-align-right {
            margin-left: auto;
            margin-right: 0px;
        }

        .text-center {
            text-align: center;
        }

    </style>

</head>

<body class="font">

    <div class="text-center">
        <h3>{{ $data['invoicedetails']['invoice_company_name'] }}</h3>
        <span>{{ $data['invoicedetails']['invoice_location'] }}</span>
        <br>
        <span>{{ $data['invoicedetails']['invoice_contact_number'] }}</span>
        <hr style="color: #eee; border:1px solid #eee">
    </div>

    <br>

    <div style="padding: 0px">

        <div class="row">
            <div class="col-5">
                <table>

                    <tr>
                        <td><b>Invoice Date</b></td>
                        <td>&nbsp;</td>
                        <td>{{ date('d-m-Y', strtotime($data['invoicedetails']['invoice_date'])) }}</td>
                    </tr>

                    <tr>
                        <td><b>Invoice #</b></td>
                        <td>&nbsp;</td>
                        <td>{{ $data['invoicedetails']['invoice_code'] }}</td>
                    </tr>

                    <tr>
                        <td><b>Reference #</b></td>
                        <td>&nbsp;</td>
                        <td>{{ $data['invoicedetails']['invoice_po_no'] }}</td>
                    </tr>

                </table>
            </div>

            <div class="col-5" style="margin-left: auto; margin-right: 0px;">
                <table>


                    <tr>
                        <td><b>Billing To</b></td>
                        <td>&nbsp;</td>
                        <td>{{ $data['invoicedetails']['invoice_bt'] }}</td>
                    </tr>

                    <tr>
                        <td><b>Billing Address</b></td>
                        <td>&nbsp;</td>
                        <td>{{ $data['invoicedetails']['invoice_ba'] }}</td>
                    </tr>

                    <tr>
                        <td><b>Auth Person</b></td>
                        <td>&nbsp;</td>
                        <td>{{ Auth::user()->name }}</td>
                    </tr>

                </table>
            </div>

        </div>

        <br>
        <br>

        <div>
            <table class="table-border"
                style="border-spacing: 0; border-width: 0; padding: 0; border-width: 0; width:100%">
                <thead>
                    <tr class="trcolor">
                        <th class="tborderth tborder tbleft bold-100" style="text-align: left">#</th>
                        <th class="tborderth tborder tbleft bold-100" style="text-align: left">Product Code</th>
                        <th class="tborderth tborder tbleft bold-100" style="text-align: left">Product Name</th>
                        <th class="tborderth tborder tbleft bold-100" style="text-align: right">Qty</th>
                        <th class="tborderth tborder tbleft bold-100" style="text-align: right">&nbsp;Unit Price</th>
                        <th class="tborderth tborder tbleft tbright bold-100" style="text-align: right">Amount</th>
                    </tr>
                </thead>
                <tbody>


                    @foreach ($data['products'] as $key => $product)
                        <?php
                        $tot = (float) $product[4] * (float) $product[3];
                        ?>

                        <tr>
                            <td class="tborder tbleft">{{ $key + 1 }}</td>
                            <td class="tborder tbleft">{{ $product[1] }}</td>
                            <td class="tborder tbleft">{{ $product[2] }}</td>
                            <td class="tborder tbleft alright">
                                {{ env('CURRENCY') . ' ' . number_format($product[4], 2, '.', ',') }}&nbsp;</td>
                            <td class="tborder tbleft alright">{{ $product[3] }}&nbsp;</td>
                            <td class="tborder tbleft alright tbright ">
                                {{ env('CURRENCY') . ' ' . number_format($tot, 2, '.', ',') }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>

    </div>
    <br>

    <div>

        <div class="row">
            <table style="margin-left: auto; margin-right: 0;">
                <tr class="smargin">
                    <td class="smargin"><b>Gross Total (LKR)</b></td>
                    <td>&nbsp;</td>
                    <td style="text-align: right">
                        @if ($data['invoicedetails']['invoice_subtotal'] == '')
                            0
                        @else
                            {{ $data['invoicedetails']['invoice_subtotal'] }}
                        @endif
                    </td>
                </tr>
                {{-- <tr class="smargin">
                    <td class="smargin"><b>Tot Discount (%)</b></td>
                    <td>&nbsp;</td>
                    <td style="text-align: right">
                        @if ($data['invoicedetails']['invoice_discount'] == '')
                            0
                        @else
                            {{ $data['invoicedetails']['invoice_discount'] }}
                        @endif
                    </td>
                </tr> --}}
                <tr class="smargin">
                    <td class="smargin"><b>Tot VAT (%)</b></td>
                    <td>&nbsp;</td>
                    <td style="text-align: right; ">

                        @if ($data['invoicedetails']['invoice_vat'] == '')
                            0
                        @else
                            {{ $data['invoicedetails']['invoice_vat'] }}
                        @endif

                    </td>
                </tr>
                <tr class="smargin">
                    <td class="smargin"><b>Net Total (LKR)</b></td>
                    <td>&nbsp;</td>
                    <td style="text-align: right; ">

                        @if ($data['invoicedetails']['nettotal'] == '')
                            0
                        @else
                            {{ $data['invoicedetails']['nettotal'] }}
                        @endif

                    </td>
                </tr>

                {{-- <tr class="smargin">
                    <td class="smargin"><b>Delivery Charges (LKR)</b></td>
                    <td>&nbsp;</td>
                    <td style="text-align: right;">

                        @if ($data['invoicedetails']['invoice_delivery_charges'] == '')
                            0
                        @else
                            {{ $data['invoicedetails']['invoice_delivery_charges'] }}
                        @endif

                    </td>
                </tr> --}}

                <tr class="smargin">
                    <td class="smargin"><b>Paid Amount (LKR)</b></td>
                    <td>&nbsp;</td>
                    <td style="text-align: right;">

                        @if ($data['invoicedetails']['invoice_paid_amount'] == '')
                            0
                        @else
                            {{ $data['invoicedetails']['invoice_paid_amount'] }}
                        @endif

                    </td>
                </tr>

                <tr class="smargin">
                    <td class="smargin"><b>Balance Amount (LKR)</b></td>
                    <td>&nbsp;</td>
                    <td style="text-align: right; border-bottom: 4px double black; border-top:1px solid black">

                        @if ($data['invoicedetails']['invoice_paybale_amount'] == '')
                            0
                        @else
                            {{ $data['invoicedetails']['invoice_paybale_amount'] }}
                        @endif

                    </td>
                </tr>

            </table>
        </div>
    </div>

    <div style="margin-top: 50px">
        @if ($data['invoicedetails']['invoice_remark'])
            <p style="text-align: justify"><strong>Remark : </strong>{{ $data['invoicedetails']['invoice_remark'] }}
            </p>
        @endif
    </div>

    <div class="row" style="margin-top: 70px">
        <div class="col-4">
            <div style="margin-right: auto; margin-left: 0px;" class="text-center">
                <span>..............................................</span><br><span><i>Prepared by</i></span>
            </div>
        </div>
        <div class="col-4 text-center text-align-right">
            <span>..............................................</span><br><span><i>Approved
                    by</i></span>
        </div>
    </div>

</body>

</html>
