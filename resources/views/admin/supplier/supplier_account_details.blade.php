@extends('admin.admin_master')
@section('admin')
    <style>
        .table>:not(caption)>*>* {
            padding: 5px !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="company-details mt-5">
                                    {{-- <h5><strong>Supplier Name : {{ $accountDetails['supplier']['name'] }}</strong></h5> --}}
                                    {{-- <p class="mb-0">Address : {{ $accountDetails->supplier->address }}</p>
                                    <p class="mb-0">Phone : {{ $accountDetails->supplier->mobile_no }}</p>
                                    <p class="mb-0">E-mail : {{ $accountDetails->supplier->email }}</p> --}}
                                </div>
                            </div>
                            <div class="col-md-12 py-4">
                                <div class="row">
                                    <form method="POST" action="{{ route('get.supplier.account.detail') }}"
                                        id="searchEarning" autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="supplier_id" id="supplier_id"
                                            value="{{ $supplierInfo->id }}">
                                        <div class="errorMsgContainer"></div>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control ml-2 date_picker" name="start_date"
                                                id="start_date" placeholder="Enter Start Date">
                                            <input type="text" class="form-control ml-2 date_picker" name="end_date"
                                                id="end_date" placeholder="Enter End Date">
                                            <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12">
                                <h4 class="text-center">Supplier Account Details</h4>
                                <div class="payment-details">
                                    {{-- <table class="table table-bordered border-dark text-center text-dark" width="100%"> --}}
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                                    <h6 class="fw-bold">Purchase</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Status</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Total Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Payment Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Due Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Paid By</h6>
                                                </th>
                                                {{-- <th>
                                                    <h6 class="fw-bold">Balance</h6>
                                                </th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_sum = '0';
                                            @endphp
                                            @foreach ($accountDetails as $key => $details)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($details->date)) }}</td>

                                                    <td>
                                                        @if ($details->purchase_id != null)
                                                            <a target="_blank"
                                                                href="{{ route('view.purchase', $details->purchase_id) }}">
                                                                {{ $details->purchase->purchase_no }}</a>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($details->status == '1')
                                                            Purchase
                                                        @elseif($details->purchase_id == null && $details->status == 2)
                                                            Opening Balance
                                                        @else
                                                            Due Payment
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($details->total_amount != null)
                                                            {{ number_format($details->total_amount) }}/-
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($details->paid_amount) }}/-</td>
                                                    <td>{{ number_format($details->due_amount) }}/-</td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $details->paid_source }}</span>
                                                    </td>
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
