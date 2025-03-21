@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Purcahse All</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.purchase') }}">
                    <button class="btn btn-info"><i class="fa fa-plus-circle" aria-hidden="true"> Add Purchase </i></button>
                </a>
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
                                        <th>Supplier Name</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Supplier Name</th>
                                        <th>Purcahse No</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <h3 class="text-center text-danger">Total Due :
                                        {{ number_format($purchaseData->sum('due_amount')) }}</h3>
                                    @foreach ($purchaseData as $key => $purchase)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $purchase->supplier->name }}
                                            </td>
                                            <td>
                                                #{{ $purchase->purchase_no }}
                                            </td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($purchase->date)) }}
                                            </td>
                                            <td>
                                                BDT {{ $purchase->total_amount }}
                                            </td>
                                            <td>
                                                BDT {{ $purchase->paid_amount }}
                                            </td>
                                            <td>
                                                BDT {{ $purchase->due_amount }}
                                            </td>

                                            <td style="display:flex">
                                                @if ($purchase->due_amount != 0)
                                                    <a title="Paid Supplier Due Bill" style="margin-left: 5px;"
                                                        href="{{ route('purchase.due.payment', $purchase->id) }}"
                                                        class="btn btn-info">
                                                        <i class="fas fa-edit"></i> Due Payment
                                                    </a>
                                                @else
                                                @endif
                                                <a title="Print Purchase" style="margin-left: 5px;"
                                                    href="{{ route('print.purchase', $purchase->id) }}"
                                                    class="btn btn-success">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
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

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
@endsection
