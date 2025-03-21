@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                {{-- <div class="invoice-title">
                                    <h4 class="float-end font-size-16"><strong>Invoice No
                                            #{{ $payment['invoice']['invoice_no'] }}</strong></h4>
                                    <h3>
                                        <strong>{{ $payment['customer']['name'] }}
                                        </strong>
                                    </h3>
                                </div>
                                <hr> --}}
                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>{{ $payment['customer']['name'] }}</strong><br>
                                            {{ $payment['customer']['phone'] }}<br>
                                            {{ $payment['customer']['email'] }}
                                        </address>
                                    </div>
                                    <div class="col-6 mt-4 text-end">
                                        <address>
                                            <strong>Invoice Date:
                                                {{ date('d-m-Y', strtotime($payment['invoice']['date'])) }} <br>
                                                Invoice No: #{{ $payment['invoice']['invoice_no'] }}
                                            </strong>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h5 class="font-size-16"><strong>Item Summary</strong></h5>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td><strong>Sl.</strong></td>
                                                        <td class="text-center"><strong>Product</strong></td>
                                                        <td class="text-center"><strong>Quantity</strong></td>
                                                        <td class="text-center"><strong>Rate</strong></td>
                                                        <td class="text-center"><strong>Total</strong></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $total_sum = '0';
                                                        $invoice_details = App\Models\InvoiceDetail::where('invoice_id', $payment->invoice_id)->get();
                                                    @endphp


                                                    @foreach ($invoice_details as $key => $details)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td class="text-center">{{ $details->product->name }}</td>
                                                            <td class="text-center">{{ $details->selling_qty }}
                                                                {{ $details->product->unit->short_form }}</td>
                                                            <td class="text-center">
                                                                {{ number_format($details->unit_price) }}/-</td>
                                                            <td class="text-center">
                                                                {{ number_format($details->selling_price) }}/-</td>
                                                        </tr>
                                                        @php
                                                            $total_sum += $details->selling_price;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Paid Amount</strong>
                                                        </td>
                                                        <td class="no-line text-center">
                                                            {{ number_format($payment->paid_amount) }}/-
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Due Amount</strong>
                                                        </td>
                                                        <td class="no-line text-center">
                                                            {{ number_format($payment->due_amount) }}/-</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Grand Total</strong>
                                                        </td>
                                                        <td class="no-line text-center">
                                                            <h4 class="m-0">
                                                                {{ number_format($payment->total_amount) }}/-
                                                            </h4>
                                                        </td>
                                                    </tr>

                                                    @php
                                                        $payment_details = App\Models\PaymentDetail::where('invoice_id', $payment->invoice_id)->get();
                                                    @endphp


                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong style="visibility: hidden;">Paid Summery</strong>
                                                        </td>
                                                        <td class="no-line text-center">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Paid Summery</strong>
                                                        </td>
                                                        <td class="no-line text-center">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Paid Date</strong>
                                                        </td>
                                                        <td class="no-line text-center">
                                                            <strong>Paid Amount</strong>
                                                        </td>
                                                    </tr>
                                                    @foreach ($payment_details as $item)
                                                        <tr>
                                                            <td class="no-line"></td>
                                                            <td class="no-line"></td>
                                                            <td class="no-line"></td>
                                                            <td class="no-line text-center">
                                                                {{ date('d-m-Y', strtotime($item->date)) }}
                                                            </td>
                                                            <td class="no-line text-center">
                                                                {{ number_format($item->current_paid_amount) }}/-
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="d-print-none">
                                            <div class="float-end">
                                                <a href="javascript:window.print()"
                                                    class="btn btn-success waves-effect waves-light"><i
                                                        class="fa fa-print"></i></a>
                                                <a href="#"
                                                    class="btn btn-primary waves-effect waves-light ms-2">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end customer info row -->

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
@endsection
