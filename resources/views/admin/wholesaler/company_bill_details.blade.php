@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="company-details mt-5">
                                    <h5><strong>Customer Name : {{ $companyInfo->name }}</strong></h5>
                                    <p class="mb-0">Address : {{ $companyInfo->address }}</p>
                                    <p class="mb-0">Phone : {{ $companyInfo->phone }}</p>
                                    @if ($companyInfo->email != null)
                                        <p class="mb-0">E-mail : {{ $companyInfo->email }}</p>
                                    @else
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 mt-5">
                                <h4 class="text-center">Account Details</h4>
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
                                                    <h6 class="fw-bold">Invoice</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Total Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Due Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Payment Amount</h6>
                                                </th>

                                                <th>
                                                    <h6 class="fw-bold">Balance</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_sum = '0';
                                            @endphp
                                            @foreach ($billDetails as $key => $details)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($details->created_at)) }}</td>
                                                    <td>
                                                        <a target="_blank" href="{{ route('invoice.print.local', $details->invoice_id) }}">
                                                            #{{ $details['invoice']['invoice_no_gen'] }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $details->total_amount }}</td>
                                                    <td>{{ $details->due_amount }}</td>
                                                    <td>{{ $details->paid_amount }}</td>
                                                    <td>{{ $total_sum += $details->due_amount }}</td>
                                                </tr>
                                            @endforeach
                                            @foreach ($billPaymentDetails as $key => $details)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td colspan="2">Cash Payment</td>
                                                    <td></td>
                                                    <td>{{ $details->paid_amount }}</td>
                                                    <td></td>
                                                    <td>{{ $total_sum - $details->paid_amount }}</td>
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
