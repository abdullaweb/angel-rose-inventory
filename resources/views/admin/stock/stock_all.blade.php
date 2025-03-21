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
                                        <th>Stock</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Stock</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>

                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @foreach ($products as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center jusify-content-center">
                                                    <img src="{{ asset('upload/product_images/' . $item->image) }}"
                                                        alt="{{ $item->name }}" width="40"
                                                        class="rounded-circle img-thumbnail">
                                                    <span style="margin-left: 10px;">{{ $item->name }}</span>
                                                </div>
                                            </td>
                                            @php
                                                $total = 0;
                                                $productStock = App\Models\PurchaseStore::where('product_id', $item->id)
                                                    ->where('quantity', '!=', '0')
                                                    ->get();
                                            @endphp
                                            <td>
                                                <strong>
                                                     <!--{{ $item->quantity }} -->
                                                    {{ $productStock->sum('quantity') }}
                                                    {{ $item['unit']['short_form'] }}
                                                </strong>
                                            </td>
                                            <td>
                                                @foreach ($productStock as $stock)
                                                    @php
                                                        $total += $stock->quantity * $stock->unit_price;
                                                    @endphp
                                                @endforeach
                                                {{ number_format($total) }}/-
                                                @php
                                                    $grandTotal += $total;
                                                @endphp
                                            </td>

                                            <td>
                                                <a style="margin-left: 5px;"
                                                    href="{{ route('purchase.history', $item->id) }}" class="btn btn-info">
                                                    <i class="fa fa-eye" aria-hidden="true"></i> Purchase History
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <h4 class="text-muted text-center   ">Total Stock Amount:
                                        {{ number_format($grandTotal) }}/-
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
