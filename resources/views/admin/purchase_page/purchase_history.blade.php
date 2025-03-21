@extends('admin.admin_master')
@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-dark">{{ $product->name }} Purchase History</h6>
                        <h6 class="m-0 font-weight-bold text-primary">
                            <a href="{{ url()->previous() }}">
                                <button class="btn btn-dark"> <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                    Back</button>
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Purchase No</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Purchase No</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <h4 class="text-muted text-center   ">Total Purchase Amount:
                                {{ number_format($purchase->sum('total')) }}/-</h4>
                            @foreach ($purchase as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-capitalize">
                                        <a href="{{ route('view.purchase', $item->purchase_id) }}">
                                            {{ $item->purchase->purchase_no }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $item->quantity }} {{ $item->product->unit->short_form }}
                                    </td>
                                    <td>
                                        {{ number_format($item->unit_price) }}
                                    </td>
                                    <td>
                                        {{ number_format($item->unit_price * $item->quantity) }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ date('d-M-Y', strtotime($item->purchase->date)) }}
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
@endsection
