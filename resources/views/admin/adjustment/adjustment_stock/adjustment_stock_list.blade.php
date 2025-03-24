@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Adjustment Product Stock list</h5>
            <h5 class="m-0 font-weight-bold text-primary">
                <a href="{{route('product.adjustment.add')}}" class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"></i>  Add Stock</a>
            </h5>
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
                                        <th>Stock Number</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Stock Number</th>
                                        <th>{{ number_format($purchaseStock->sum('total_qty')) }} PCS</th>
                                        <th> {{ number_format($purchaseStock->sum('total_amount'),2)  }}</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($purchaseStock as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{$item->adjustment_no}}</td>
                                            <td>
                                                {{ $item->total_qty }} PCS
                                            </td>
                                            <td>
                                                {{ number_format($item->total_amount,2) }}
                                            </td>

                                            <td>
                                                <a  href="{{ route('product.adjustment.edit', $item->id) }}" class="btn btn-info">
                                                    <i class="fas fa-edit "></i>
                                                </a>
                                                <a  href="{{ route('product.adjustment.view', $item->id) }}" class="btn btn-info">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                                <a  href="{{ route('product.adjustment.delete', $item->id) }}" id="delete" class="btn btn-danger">
                                                    <i class="fa fa-trash-alt" aria-hidden="true"></i>
                                                </a>
                                            </td>
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
@endsection
