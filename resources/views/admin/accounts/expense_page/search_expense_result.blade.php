@php
    $total = $allExpense->sum('amount');
@endphp
@extends('admin.admin_master')
@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-muted ">Filtering Expense Result</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">
                                <div class="d-print-none">
                                    <div class="float-end">
                                        <a href="javascript:window.print()"
                                            class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i>
                                            Print</a>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <!-- DataTales Example -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive" id="printContent">
                    <h4 class="text-muted text-center">Expense of
                        {{ date('d-m-Y', strtotime(Request::post('start_date'))) }} to
                        {{ date('d-m-Y', strtotime(Request::post('end_date'))) }}</h4>
                    <h5 class="text-center text-muted mb-3">Total Expense: <strong>BDT {{ $total }}</strong> </h5>
                    <table class="table table-bordered" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Expense Head</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allExpense as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-capitalize">
                                        {{ $item->head }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $item->description }}
                                    </td>
                                    <td>
                                        {{ $item->amount }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
    <!-- End Page Content -->


    <script>
        function printDiv(printContent) {
            let printContents = document.getElementById(printContent).innerHTML;
            let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
