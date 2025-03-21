@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> <span class="text-capitalize">{{ $report_head }} Report</span> </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0 align-items-center">
                                <li>
                                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light">
                                        <i class="fa fa-print"></i>
                                    </a>
                                </li>
                                <li style="margin-left: 15px;">
                                    <a href="{{ URL()->previous()}}" class="btn btn-info waves-effect waves-light">
                                        <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Go Back
                                    </a>
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 mt-4">
                                            <h3 class="text-muted text-center mb-2"><span
                                                    class="text-capitalize">{{ $report_head }} </span> Report from
                                                {{ date('d-m-Y', strtotime($sdate)) }} to
                                                {{ date('d-m-Y', strtotime($edate)) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <div class="">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td><strong>Sl.</strong></td>
                                                            <td class="text-center">
                                                                <strong>
                                                                    @if ($report_head == 'sales')
                                                                        Customer Name
                                                                    @else
                                                                        Supplier Name
                                                                    @endif
                                                                </strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <strong>
                                                                    @if ($report_head == 'sales')
                                                                        Customer Phone
                                                                    @else
                                                                        Supplier Phone
                                                                    @endif
                                                                </strong>
                                                            </td>
                                                            <td class="text-center text-capitalize">
                                                                <strong>

                                                                    @if ($report_head == 'purchase')
                                                                        Purchase No
                                                                    @else
                                                                        Invoice No
                                                                    @endif
                                                                </strong>
                                                            </td>
                                                            <td class="text-center"><strong>Date</strong></td>
                                                            <td class="text-center"><strong>Amount</strong></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                        @php
                                                            $total_amount = '0';
                                                        @endphp
                                                        @foreach ($allData as $key => $item)
                                                            @if ($report_head == 'purchase')
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td class="text-center">
                                                                        {{ $item->supplier->name }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $item->supplier->mobile_no }}
                                                                    </td>
                                                                    <td class="text-center">#{{ $item->purchase_no }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ date('Y-m-d', strtotime($item->date)) }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ number_format($item->total_amount) }}/-
                                                                    </td>
                                                                </tr>

                                                                @php
                                                                    $total_amount += $item->total_amount;
                                                                @endphp
                                                            @elseif ($report_head == 'sales')
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td class="text-center">
                                                                        {{ $item['payment']['customer']['name'] }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $item['payment']['customer']['phone'] }}
                                                                    </td>
                                                                    <td class="text-center">#{{ $item->invoice_no }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ date('Y-m-d', strtotime($item->date)) }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ number_format($item['payment']['total_amount']) }}/-
                                                                    </td>
                                                                </tr>

                                                                @php
                                                                    $total_amount += $item['payment']['total_amount'];
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                    <h4 class="mb-4 text-center text-muted fw-bold text-capitalize">Total
                                                        {{ $report_head }}: {{ number_format($total_amount) }}/- </h4>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div> <!-- end row -->


                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
