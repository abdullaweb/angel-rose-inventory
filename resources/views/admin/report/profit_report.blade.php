@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!-- start page title -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Date Wise Profit Report</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Profit Report</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <form method="POST" action="{{ route('profit.filter') }}">
                @csrf
                <div class="errorMsgContainer"></div>
                <div class="input-group mb-3">
                    <input type="date" class="form-control ml-2 date_picker" required name="start_date" id="start_date">
                    <input type="date" class="form-control ml-2 date_picker" required name="end_date" id="end_date">
                    <button class="btn btn-info submit_btn ml-2" type="submit">Search</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-muted text-center">Total Sales Profit:
                            BDT
                            {{ number_format($totalProfit,2) }} </h4>
                    </div>
                    <div class="card-body">
                        <table id="" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                            {{ $sales->product->name ?? ''  }}
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
                                            {{ number_format($sales->profit_per_unit, 2) }}
                                        </td>
                                        <td>
                                            BDT
                                            {{ number_format($sales->profit, 2) }}

                                        </td>
                                        <td>{{ date('d F, Y', strtotime($sales->date)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $totalSales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
