@extends('admin.admin_master')
@section('admin')
    <style>
        .row.invoice-wrapper.mb-5 {
            height: 100vh;
            position: relative;
        }

        .col-12.invoice_page {
            position: absolute;
            bottom: 5vh;
        }

        table.invoice_table tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-width: 1px !important;
            padding: 8px;
        }

        table.amount_section tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            padding: 2px;
        }

        table.invoice_table th,
        table.invoice_table td,
        table.amount_section th {
            font-weight: 500 !important;
            font-size: 14px;
        }

        .card.invoice-page {
            /* position: relative; */
            height: 100%;
        }

        td.in_word {
            text-align: left;
        }

        td.des {
            text-align: left !important;
        }

        td.qty {
            text-align: right !important;
        }

        tr.custom-border>td:first-child {
            border-color: transparent;
        }

        tr.custom-border>td:nth-child(2) {
            border-left-color: transparent;
            border-bottom-color: transparent;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Invoice</h4>

                        <div class="d-print-none">
                            <div class="float-end">
                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i
                                        class="fa fa-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row invoice-wrapper mb-5">
            <div class="col-12">
                <div class="card invoice-page">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 pt-3">
                                <div class="invoice-title text-center">
                                    <h3 class="mb-0">AR Distribution</h3>
                                    <p class="mb-0"> All Kinds of Foreign Cosmetics Importer & Wholesalers</p>
                                    <p class="mb-0"> Address: House # 24, Lane # 2, Block # A, Section # 6. Mirpur,Dhaka
                                    </p>
                                    <p class="mb-0">Cell: 01722717700</p>
                                </div>
                                {{-- @php
                                    $payments = App\Models\Payment::where('invoice_id', $invoice->id)->first();
                                @endphp --}}
                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>To</strong>
                                            <br>
                                            <h5 class="mb-0">{{ $invoice->payment->customer->name }}</h5>
                                            {{ $invoice->payment->customer->phone }}<br>
                                            {{ $invoice->payment->customer->address }}<br>
                                        </address>

                                        <br>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div>
                                    <div class="py-2 d-flex justify-content-between">
                                        <h3 class="font-size-16"><strong>Invoice No: {{ $invoice->invoice_no }}</strong>
                                        </h3>
                                        <h3 class="font-size-16"><strong>Date:
                                                {{ date('d-m-Y', strtotime($invoice->date)) }}</strong></h3>
                                    </div>
                                    <div class="">
                                        <table class="invoice_table text-center p-2" border="1" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Sl.No</th>
                                                    <th>Product Name</th>
                                                    <th>Qty</th>
                                                    <th width="15%">Rate</th>
                                                    <th width="10%">Amount</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $total_sum = '0';
                                                @endphp
                                                @foreach ($invoiceDetails as $key => $invoiceItem)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            {{ $invoiceItem->product->name }}
                                                        </td>
                                                        <td>
                                                            {{ $invoiceItem->selling_qty }} {{ $invoiceItem->product->unit->short_form }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($invoiceItem->unit_price) }}/-
                                                        </td>
                                                        <td>
                                                            {{ number_format($invoiceItem->selling_price) }}/-
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td colspan="2" class="in_word">
                                                        @php
                                                            $in_word = numberTowords($invoice->payment->total_amount);
                                                        @endphp
                                                        <i><strong>In Word : {{ $in_word }}</strong> </i>
                                                    </td>
                                                    <td>Total</td>
                                                    <td class="text-center">
                                                        {{ number_format($invoice->payment->total_amount + $invoice->payment->discount_amount) }}/-
                                                    </td>
                                                </tr>
                                                @if ($invoice->payment->discount_amount != NULL)
                                                <tr class="custom-border">
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                    <td>Discount Amount</td>
                                                    <td class="text-center">
                                                        {{ number_format($invoice->payment->discount_amount) }}/-
                                                    </td>
                                                </tr>
                                               @endif
                                                <tr class="custom-border">
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                    <td>Paid Amount</td>
                                                    <td class="text-center">{{ number_format($invoice->payment->paid_amount) }}/-
                                                    </td>
                                                </tr>
                                                @if ($invoice->payment->due_amount != '0')
                                                    <tr class="custom-border">
                                                        <td></td>
                                                        <td colspan="2"></td>
                                                        <td>Due Amount</td>
                                                        <td class="text-center">
                                                            {{ number_format($invoice->payment->due_amount) }}/-
                                                        </td>
                                                    </tr>
                                                @endif
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-12 invoice_page">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted"> Received By
                                        <strong>{{ $invoice->payment->customer->name }}</strong>
                                    </p>
                                    <h5><small class="fs-6">For</small> AR Distribution</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->

    </div>
    <!-- End Page-content -->

@endsection
