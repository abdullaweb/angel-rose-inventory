@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form  class="custom-validation"
                            novalidate="" autocomplete="off">
                            <div class="row py-2 align-items-center">
                                <div class="col-md-6">
                                    <h4 class="text-muted mb-0">Product Stock View</h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-muted mb-0 text-end">
                                        <a href="{{URL::previous()}}" class="btn btn-dark"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                                    </h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3 table-responsive">
                                    <table class="table border table-responsive table-striped">
                                        <thead class="bg-body">
                                            <tr>
                                                <th class="text-center">Category</th>
                                                <th class="text-center" width="30%">Product</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Unit Price</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            @foreach ($purchaseStore as $key => $item)
                                            @php
                                            $purchaseMeta = App\Models\PurchaseMeta::where('purchase_id', $item->purchase_id)
                                                ->first();
                                             @endphp
                                                <tr class="tr">
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="{{ $purchaseMeta->category->name ?? ''}}"readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="{{$item->product->name ?? ''}}" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="{{$item->quantity}}" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="{{$item->unit_price}}" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="{{$item->quantity *$item->unit_price}}" readonly>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
