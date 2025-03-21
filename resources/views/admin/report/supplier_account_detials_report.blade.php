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
                        <h4 class="mb-sm-0">Supplier Account Report</h4>

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
                                    @php
                                        $supplierInfo = App\Models\Supplier::where('id', $supplier_id)->first();
                                        $due_amount = $billDetails->sum('total_amount') - $billDetails->sum('paid_amount');
                                    @endphp
                                    <h5><strong>Supplier Name : {{ $supplierInfo->name }}</strong></h5>
                                    <p class="mb-0">Address : {{ $supplierInfo->address }}</p>
                                    <p class="mb-0">Phone : {{ $supplierInfo->mobile_no }}</p>
                                    <p class="mb-0">E-mail : {{ $supplierInfo->email }}</p>
                                </div>
                            </div>
                            {{-- <div class="col-md-12 py-5">
                                <h3> Account details from {{ $start_date }} to {{ $end_date }}</h3>
                            </div> --}}
                            <div class="col-12 py-3">
                                <h4 class="text-center">Account details from {{ $start_date }} to {{ $end_date }}</h4>

                                <h5 class="text-center">Total Due: {{ $due_amount }}</h5>
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
                                                    <h6 class="fw-bold">Date</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Purchase No</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Total Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Paid Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Due Amount</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($billDetails) > 0)
                                                @php
                                                    $total_sum = '0';
                                                @endphp
                                                @foreach ($billDetails as $key => $details)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($details->created_at)) }}</td>
                                                        <td>{{ $details->purchase->purchase_no }}</td>
                                                        <td>
                                                            @if ($details->total_amount != null)
                                                                {{ $details->total_amount }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ $details->paid_amount }}</td>
                                                        <td>{{ $details->due_amount }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7"> No data found</td>
                                                </tr>
                                            @endif

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
