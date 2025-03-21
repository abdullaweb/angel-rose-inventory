@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Due</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.due.payment') }}">
                    <button class="btn btn-info">Add Due</button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Paid Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Paid Amount</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($dueAll as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $item->company->name }}
                                        </td>
                                        <td>
                                            {{ $item->date }}
                                        </td>
                                        <td>
                                            {{ $item->paid_amount }}
                                        </td>

                                        <td>
                                            {{-- @if (Auth::user()->can('due.payment.edit')) --}}
                                                <a style="margin-left: 5px;" href="{{ route('edit.due.payment', $item->id) }}" class="btn btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            {{-- @endif --}}

                                            {{-- @if (Auth::user()->can('due.payment.delete')) --}}
                                                <a style="margin-left: 5px;" href="{{ route('delete.due.payment', $item->id) }}" class="btn btn-danger" title="Delete" id="delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            {{-- @endif --}}

                                            @if($item->status == 'pending')
                                                <a style="margin-left: 5px;" href="{{ route('due.payment.approval.now', $item->id) }}" class="btn btn-success" id="approve" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('backend/assets/js/code.js') }}"></script>
    @endsection
