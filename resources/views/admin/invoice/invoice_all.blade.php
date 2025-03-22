@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Invoice All</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('invoice.add') }}">
                    <button class="btn btn-info"><i class="fa fa-plus-circle" aria-hidden="true"> Add Invoice </i></button>
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
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Company Name</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($allInvoice as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item['payment']['customer']['name'] ?? 'N/A' }}
                                                @if ($item['payment']['customer']['status'] == '1')
                                                    <span class="badge bg-info">Wholesaler</span>
                                                @elseif ($item['payment']['customer']['status'] == '0')
                                                    <span class="badge bg-info">Retail</span>
                                                @endif
                                            </td>
                                            <td>
                                                #{{ $item->invoice_no }}
                                            </td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($item->date)) }}
                                            </td>
                                            <td>
                                                BDT {{ $item['payment']['total_amount'] }}
                                            </td>
                                            <td>
                                                BDT {{ $item['payment']['due_amount'] }}
                                            </td>

                                            <td>

                                                @if ($item['payment']['due_amount'] != 0)
                                                    <a title="Paid Customer Due Bill" style="margin-left: 5px;"
                                                        href="{{ route('edit.credit.customer', $item->id) }}"
                                                        class="btn btn-info">
                                                        <i class="fas fa-edit"></i> Due Payment
                                                    </a>
                                                @endif
                                                <a title="Print Invoice" style="margin-left: 5px;"
                                                    href="{{ route('invoice.print', $item->id) }}" class="btn btn-success">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                </a>
                                                <a title="Edit Invoice" style="margin-left: 5px;"
                                                   href="{{ route('invoice.edit', $item->id) }}" class="btn btn-info">
                                                   <i class="fas fa-edit"></i>
                                                </a>
                                                <a title="Delete Invoice" style="margin-left: 5px;"
                                                    href="{{ route('invoice.delete', $item->id) }}" class="btn btn-danger" id="delete">
                                                    <i class="fas fa-trash-alt"></i>
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
