@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Return Product</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.return') }}">
                    <button class="btn btn-info"><i class="fa fa-plus-circle" aria-hidden="true"> Add Ruturn Product
                        </i></button>
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
                                        <th>Customer Name</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Company Name</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($returnProduct as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item['payment']['customer']['name'] }}
                                                @if ($item['payment']['customer']['status'] == '1')
                                                    <span class="badge bg-info">Wholesaler</span>
                                                @elseif ($item['payment']['customer']['status'] == '0')
                                                    <span class="badge bg-info">Retail</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('invoice.print', $item->id) }}">
                                                    #{{ $item->invoice_no }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($item->date)) }}
                                            </td>

                                            <td>
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
