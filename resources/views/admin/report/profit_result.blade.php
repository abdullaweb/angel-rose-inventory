@extends('admin.admin_master')
@section('admin')
    <style>
        .table>:not(caption)>*>* {
            padding: 5px !important;
        }
    </style>
    <!-- Begin Page Content -->
    <div class="page-content">
        <div class="card-header pb-3  d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Profit Data</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ url()->previous() }}">
                    <button class="btn btn-sm btn-dark">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                    </button>
                </a>
                <button onclick="printDiv('printContent')" class="btn btn-sm btn-success waves-effect waves-light">
                    <i class="fa fa-print"></i> Print
                </button>
            </h6>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="printContent">
                    <div class="card-header">
                        <h4 class="text-muted text-center">
                            Sale Profit from
                            {{ date('d-m-Y', strtotime(Request::post('start_date'))) }} to
                            {{ date('d-m-Y', strtotime(Request::post('end_date'))) }}
                        </h4>
                        <h5 class="text-center text-muted mb-3">Total Profit: <strong>BDT
                                {{ number_format($totalSales->sum('profit'), 2) }}</strong> </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Product</th>
                                        <th>Purchase Price</th>
                                        <th>Selling Price</th>
                                        <th>Discount Per Unit</th>
                                        <th>Selling Qty</th>

                                        <th>Profit Per Unit</th>
                                        <th>Profit</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Product</th>
                                        <th>Purchase Price</th>
                                        <th>Selling Price</th>
                                        <th>Discount Per Unit</th>
                                        <th>Selling Qty</th>

                                        <th>Profit Per Unit</th>
                                        <th>Profit</th>
                                        <th>Date</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($totalSales as $key => $sales)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $sales->product->name }}
                                            </td>
                                            <td> BDT
                                                {{ number_format($sales->unit_price_purchase, 2) }}</td>
                                            <td> BDT
                                                {{ $sales->unit_price_sales }}</td>
                                            <td>
                                                @if ($sales->discount_per_unit != null)
                                                    BDT
                                                    {{ number_format($sales->discount_per_unit, 2) }}
                                                @else
                                                    No Discount
                                                @endif

                                            </td>
                                            <td>{{ $sales->selling_qty }}</td>
                                            <td>
                                                BDT
                                                {{-- {{ str_starts_With($sales->profit_per_unit, '-') == true ? '(' . abs(number_format($sales->profit_per_unit, 2)) . ')' : number_format($sales->profit_per_unit, 2) }} --}}
                                                {{ number_format($sales->profit_per_unit, 2) }}
                                            </td>
                                            <td>
                                                BDT
                                                {{-- {{ str_starts_With($sales->profit, '-') == true ? '(' . abs(number_format($sales->profit, 2)) . ')' : number_format($sales->profit, 2) }} --}}
                                                {{ number_format($sales->profit, 2) }}

                                            </td>
                                            <td>{{ date('d F, Y', strtotime($sales->date)) }}</td>
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
    <!-- End Page Content -->

    <script>
        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
