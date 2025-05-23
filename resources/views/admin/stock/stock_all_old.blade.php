@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!-- breadcrumb -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Product Stock</h6>
        </div>
        <!-- end breadcrumb -->

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
                                        $grandQuantity = 0;
                                        $grandTotal = 0;

                                    @endphp
                                    @foreach ($products as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start">
                                                    <img src="{{ !empty($item->image) ? asset('upload/product_images/' . $item->image) : url('upload/no_image.jpg') }}"
                                                        alt="{{ $item->name }}" width="40"
                                                        class="rounded-circle img-thumbnail">
                                                    <span class="ms-2">{{ $item->name }}</span>
                                                </div>
                                            </td>
                                            @php
                                                $total = 0;
                                                $quantity = 0;
                                                $purchaseStore = App\Models\PurchaseStore::where(
                                                    'product_id',
                                                    $item->id,
                                                )
                                                    ->where('quantity', '>', 0)
                                                    ->get();
                                                foreach ($purchaseStore as $purchase) {
                                                    $quantity += $purchase->quantity;
                                                    $total += $purchase->unit_price * $purchase->quantity;
                                                }

                                                $grandQuantity += $quantity;
                                                $grandTotal += $total;
                                            @endphp
                                            <td>{{ $quantity }}</td>
                                            <td>{{ $total }}</td>
                                            <td>
                                                <a href="{{ route('purchase.history', $item->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i> Purchase History
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                            <div class="mt-4 text-center">
                                <h5 class="text-muted">Total Stock Quantity:
                                    <strong>{{ number_format($grandQuantity, 2) }}</strong>
                                </h5>
                                <h5 class="text-muted">Total Stock Amount:
                                    <strong>৳{{ number_format($grandTotal, 2) }}</strong>
                                </h5>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
