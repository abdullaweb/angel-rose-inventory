@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Product Stock</h6>
            <h6 class="m-0 font-weight-bold text-primary">
            </h6>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Purchase Stock</th>
                                        <th>Selling Stock</th>
                                        <th>Current Stock</th>
                                        <th>Product Stock</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Purchase Stock</th>
                                        <th>Selling Stock</th>
                                        <th>Current Stock</th>
                                        <th>Product Stock</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($products as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <span>{{ $item->name }}</span>
                                                <span> <strong>{{ $item->id }}</strong> </span>
                                            </td>
                                            @php
                                                $salesStock = App\Models\InvoiceDetail::where('product_id', $item->id)->get();
                                                $purchaseStock = App\Models\PurchaseMeta::where('product_id', $item->id)->get();
                                                $currentStock = App\Models\PurchaseStore::where('product_id', $item->id)->get();
                                            @endphp

                                            <td>
                                                <strong>
                                                    {{ $purchaseStock->sum('quantity') }}
                                                </strong>
                                            </td>

                                            <td>
                                                <strong>
                                                    {{ $salesStock->sum('selling_qty') }}
                                                </strong>
                                            </td>

                                            <td>
                                                <strong>
                                                    {{ $currentStock->sum('quantity') }}
                                                </strong>
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ $item->quantity }}
                                                </strong>
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ $salesStock->sum('selling_qty') + $currentStock->sum('quantity') }}
                                                </strong>
                                            </td>
                                            <td>

                                            </td>


                                        </tr>
                                    @endforeach
                                    <h4 class="text-muted text-center   ">Total Purchase Amount:
                                        {{-- {{ number_format($total) }}/- --}}
                                    </h4>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
@endsection
