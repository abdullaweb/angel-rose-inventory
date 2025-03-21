@extends('admin.admin_master')
@section('admin')
    <style>
        .table>:not(caption)>*>* {
            padding: 0 !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><span class="text-capitalize">{{ $report_type }}</span> Category Report</h4>

                <div class="d-print-none">
                    <div class="float-end">
                        <a class="btn btn-info" href="{{ url()->previous() }}">Go Back</a>
                        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">

            <div class="card-body">
                <div class="table-responsive py-3">
                    <div class="text-center">
                        <h4>
                            <span class="text-capitalize">{{ $report_type }}</span> from
                            {{ date('d-m-Y', strtotime($start_date)) }} to
                            {{ date('d-m-Y', strtotime($end_date)) }}
                        </h4>
                    </div>
                    <table id="" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th>Product</th>
                                <th>Category</th>
                                <th>Bill No</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="text-center">
                                <th>Product</th>
                                <th>Category</th>
                                <th>Bill No</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php
                                $total_purchase = '0';
                            @endphp
                            @foreach ($allSearchResult as $key => $item)
                                @if ($report_type == 'purchase')
                                    <tr class="text-center">
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>
                                            @if ($item->purchase_id != null)
                                                <a target="_blank" href="{{ route('view.purchase', $item->purchase_id) }}">
                                                    {{ $item->purchase->purchase_no }}</a>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit_price }}</td>
                                        @php
                                            $total_purchase += $item->purchase->total_amount;
                                        @endphp
                                        <td> {{ number_format($item->purchase->total_amount) }} </td>
                                        <td>{{ date('d-m-y', strtotime($item->created_at)) }}</td>
                                    </tr>
                                    <h4 class="text-center">Total <span class="text-capitalize">{{ $report_type }}</span>:
                                        {{ number_format($total_purchase) }}</h4>
                                @elseif($report_type = 'sales')
                                    <tr class="text-center">
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>
                                            @if ($item->invoice_id != null)
                                                <a target="_blank" href="{{ route('invoice.view', $item->invoice_id) }}">
                                                    {{ $item->invoice->invoice_no }}</a>
                                            @endif
                                        </td>
                                        <td>{{ $item->selling_qty }} {{ $item->product->unit->short_form }}</td>
                                        <td>{{ $item->unit_price }}</td>

                                        <td> {{ number_format($item->selling_price) }} </td>
                                        <td>{{ date('d-m-y', strtotime($item->created_at)) }}</td>
                                    </tr>
                                    <h4 class="text-center">Total <span class="text-capitalize">{{ $report_type }}</span>:
                                        {{ number_format($allSearchResult->sum('selling_price')) }}</h4>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- End Page Content -->
@endsection
