@extends('admin.admin_master')
@section('admin')
    <style>
        .row.invoice-wrapper.mb-5 {
            height: 100vh;
            position: relative;
        }

        .col-12.invoice_page {
            position: absolute;
            bottom: 3vh;
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
        table.amount_section th {
            font-weight: 600 !important;
            font-size: 18px;
        }

        .card.invoice-page {
            height: 100%;
        }
    </style>
    <div class="page-content">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route('customer.update.invoice', $payment->invoice_id) }}" method="POST">
                                    @csrf
                                    <div class="p-2 d-flex justify-content-between align-items-center">
                                        <h5 class="font-size-16"><strong>Customer Invoice (Invoice No:
                                                #{{ $payment['invoice']['invoice_no'] }})</strong></h5>
                                        <h5 class="font-size-16"><strong>
                                                <a href="{{ url()->previous() }}"> <i class="fa fa-arrow-left"
                                                        aria-hidden="true"></i> Back</a>
                                            </strong></h5>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-12 mt-2">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td class="text-center"><strong>Sl.</strong></td>
                                                        <td class="text-center"><strong>Customer Name</strong></td>
                                                        <td class="text-center"><strong>Customer ID</strong></td>
                                                        <td class="text-center"><strong>Customer Mobile</strong></td>
                                                        <td class="text-center"><strong>Customer Address</strong></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            <strong>{{ $payment['customer']['name'] }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <strong>{{ $payment['customer']['company_id'] }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <strong>{{ $payment['customer']['phone'] }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <strong>{{ $payment['customer']['email'] }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <strong>{{ $payment['customer']['address'] }}</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="p-2">
                                                <h3 class="font-size-16"><strong>Item Summery</strong></h3>
                                            </div>
                                            <div class="">
                                                <table class="invoice_table text-center p-2" border="1" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl.No</th>
                                                            <th>Product Name</th>
                                                            <th>Qty</th>
                                                            <th width="15%">Unit Rate</th>
                                                            <th width="10%">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $invoiceDetails = App\Models\InvoiceDetail::where('invoice_id', $payment->invoice_id)->get();
                                                            $total_sum = '0';
                                                        @endphp
                                                        @foreach ($invoiceDetails as $key => $details)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td class="text-center">{{ $details->product->name }}</td>
                                                                <td class="text-center">{{ $details->selling_qty }}</td>
                                                                <td class="text-center">{{ $details->unit_price }}/-</td>
                                                                <td class="text-center">{{ $details->selling_price }}/-
                                                                </td>
                                                            </tr>

                                                            @php
                                                                $vat = ($payment->sub_total * $payment->vat) / 100;
                                                                $tax = ($payment->sub_total * $payment->tax) / 100;
                                                                $total_sum += $details->selling_price;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <table class="amount_section mt-2" border="1" style="float: right;"
                                                    width="25%">
                                                    <thead class="">
                                                        <tr>
                                                            <th width="60%">Paid Amount</th>
                                                            <td class="text-center">{{ $payment->paid_amount }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Due Amount</th>
                                                            <td class="text-center">{{ $payment->due_amount }}</td>
                                                            <input type="hidden" name="new_paid_amount"
                                                                value="{{ $payment->due_amount }}">
                                                        </tr>
                                                        @if ($payment->vat_amount != 0)
                                                            <tr>
                                                                <th>Vat & Tax({{ $payment->vat_tax }}%)</th>
                                                                <td class="text-center">{{ $payment->vat_amount }}</td>
                                                            </tr>
                                                        @else
                                                        @endif

                                                        <tr>
                                                            <th>Total</th>
                                                            <td class="text-center">{{ $payment->total_amount }}</td>
                                                        </tr>
                                                    </thead>

                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3 mt-5">
                                        <div class="col-md-3">
                                            <label for="" class="form-label">Paid Status</label>
                                            <select class="form-control" name="paid_status" id="paid_status" required>
                                                <option value="" selected disabled>Select Paid Status</option>
                                                <option value="full_paid">Full Paid</option>
                                                <option value="partial_paid">Partial Paid</option>
                                            </select>
                                            <input type="text" placeholder="Enter Paid Amount" class="form-control"
                                                name="paid_amount" id="paid_amount" style="display:none;">
                                        </div>
                                        <div class="col-md-3" id="paid_source_col" style="display: none;">
                                            <label for="" class="form-label">Paid Type</label>
                                            <select class="form-control" name="paid_source" id="paid_source">
                                                <option value="" selected disabled>Select Payment Status</option>
                                                <option value="cash">Cash</option>
                                                <option value="bank">Bank</option>
                                                <option value="online-banking">Online Banking</option>
                                                <option value="mobile-banking">Mobile Banking</option>
                                            </select>

                                            <div class="row" id="bank-info" style="display: none;">
                                                <div class="col-12">
                                                    <select name="bank_name" id="bank_name" class="form-control">
                                                        <option value="" selected disabled>Select Bank</option>
                                                        @foreach ($allBank as $bank)
                                                            <option value="{{ $bank->id }}">{{ $bank->name }} -
                                                                {{ $bank->branch_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" placeholder="Note" class="form-control"
                                                        name="check_number" id="check_number">
                                                </div>
                                            </div>
                                            <div class="row" id="online-bank-row" style="display: none;">
                                                <div class="col-12">
                                                    <input type="text" placeholder="Note" class="form-control"
                                                        name="note" id="note">
                                                </div>
                                            </div>

                                            <div class="row" id="mobile-bank-info" style="display: none;">
                                                <div class="col-12">
                                                    <select name="mobile_bank" id="mobile_bank" class="form-control">
                                                        <option value="" selected disabled>Select Mobile Bank
                                                        </option>
                                                        <option value="bkash">Bkash</option>
                                                        <option value="nagad">Nagad</option>
                                                        <option value="rocket">Rocket</option>
                                                        <option value="ucash">ucash</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" placeholder="Transaction Number"
                                                        class="form-control" name="transaction_nmber"
                                                        id="transaction_nmber">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="" class="form-label">Due Paid Date </label>
                                            <input type="text" class="form-control date_picker"
                                                placeholder="MM/DD/YYYY" id="date" name="date"
                                                autocomplete="off">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <div class="md-3" style="padding-top: 30px;">
                                                <button class="btn btn-info" type="submit">Update
                                                    Invoice</button>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- <div class="row mt-5">
                                        <div class="col-md-3 form-group">
                                            <label for="" class="form-label">Paid Status</label>
                                            <select name="paid_status" id="paid_status" class="form-control" required
                                                data-parsley-required-message="Paid Status is required."
                                                aria-readonly="true">
                                                <option value="">Select Payment Status</option>
                                                <option value="full_paid">Full Paid</option>
                                                <option value="partial_paid">Partial Paid</option>
                                            </select>
                                            <input style="display: none;" type="text" name="paid_amount"
                                                placeholder="Enter Paid Amount" class="form-control paid_amount"
                                                min="0" data-parsley-validation-threshold="0"
                                                data-parsley-trigger="keyup" data-parsley-type="digits"
                                                data-parsley-type-message="Input must be positive number"
                                                autocomplete="off">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="" class="form-label">Check Number</label>
                                            <input type="text" class="form-control" placeholder="Check Number"
                                                id="check_number" name="check_number" autocomplete="off">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="" class="form-label">Due Paid Date </label>
                                            <input type="text" class="form-control date_picker"
                                                placeholder="MM/DD/YYYY" id="date" name="date"
                                                autocomplete="off">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <div class="md-3" style="padding-top: 30px;">
                                                <button class="btn btn-info" type="submit">Update
                                                    Invoice</button>
                                            </div>
                                        </div>
                                    </div> --}}

                                </form>
                            </div>
                        </div> <!-- end customer info row -->
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
            $(document).on('change', '#paid_status', function() {
                let paidStatus = $(this).val();
                console.log(paidStatus);
                if (paidStatus == 'partial_paid') {
                    $('.paid_amount').show();
                } else {
                    $('.paid_amount').hide();
                }
            });
        });
    </script> --}}



    <script type="text/javascript">
        $(document).ready(function() {
            $('#paid_status').on('change', function() {
                let paidStatus = $(this).val();
                console.log('paidSource', paidStatus);
                if (paidStatus) {
                    $('#paid_source_col').show();
                    $('#vat_tax_col').show();
                }

                if (paidStatus == 'partial_paid') {
                    $('#paid_amount').show();
                } else {
                    $('#paid_amount').hide();
                }

                if (paidStatus == 'full_due') {
                    $('#paid_source_col').hide();
                }

            });


            $('#paid_source').on('change', function() {
                let paidSource = $(this).val();
                console.log('paidSource', paidSource);
                if (paidSource == 'bank') {
                    $('#bank-info').show();
                    $('#online-bank-row').hide();
                    $('#mobile-bank-info').hide();
                } else if (paidSource == 'online-banking') {
                    $('#bank-info').hide();
                    $('#online-bank-row').show();
                    $('#mobile-bank-info').hide();
                } else if (paidSource == 'mobile-banking') {
                    $('#bank-info').hide();
                    $('#online-bank-row').hide();
                    $('#mobile-bank-info').show();
                } else if (paidSource == 'cash') {
                    $('#bank-info').hide();
                    $('#online-bank-row').hide();
                    $('#mobile-bank-info').hide();
                }
            });

            $('#vat_tax_field').on('change', function() {
                let vatTaxField = $(this).val();
                console.log('vat_tax_field', vatTaxField);
                if (vatTaxField == 'with-vat-tax') {
                    $('.vat').show();
                    $('.tax').show();
                } else {
                    $('.vat').hide();
                    $('.tax').hide();
                }
            });
        });
    </script>
@endsection
