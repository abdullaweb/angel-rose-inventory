@extends('admin.admin_master')
@section('admin')
    <style>
        .table>:not(caption)>*>* {
            padding: 0 !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Bank Details Report</h4>

                        <div class="d-print-none">
                            <div class="float-end">
                                <a class="btn btn-info" href="{{ url()->previous() }}">Go Back</a>
                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i
                                        class="fa fa-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="company-details mt-5">
                                    <h5><strong>Bank Name : {{ $bankInfo->name }}</strong></h5>
                                    <p class="mb-0">Branch Name : {{ $bankInfo->branch_name }}</p>
                                    <p class="mb-0">Account Number : {{ $bankInfo->account_number }}</p>
                                    <p class="mb-0">Total Balance : {{ $bankInfo->balance }}</p>
                                </div>
                            </div>

                            <div class="col-12 py-3">
                                <div class="payment-details">
                                    <table class="table table-bordered border-dark text-center text-dark" width="100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6 class="fw-bold">
                                                        Sl. No
                                                    </h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Purpose</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Date</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Amount</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($bankDetails as $key => $details)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $details->trans_head }}</td>
                                                    <td>{{ date('d-M,Y', strtotime($details->date)) }}</td>
                                                    @if ($details->status == '0')
                                                        <td>({{ number_format($details->balance) }})/-</td>
                                                    @else
                                                        <td>{{ number_format($details->balance) }}/-</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
