@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.bank') }}">
                    <button class="btn btn-info">Add Bank</button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Account</th>
                                    <th>Branch</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Account</th>
                                    <th>Branch</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($allBank as $key => $bank)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $bank->name }}
                                        </td>
                                        <td>
                                            {{ $bank->account_number }}
                                        </td>
                                        <td>
                                            {{ $bank->branch_name }}
                                        </td>
                                        <td>
                                            {{ number_format($bank->balance) }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <a title="Edit Bank" style="margin-left: 5px;"
                                                    href="{{ route('edit.bank', $bank->id) }}" class="btn btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a title="Delete Bank" id="delete" style="margin-left: 5px;"
                                                    href="{{ route('delete.bank', $bank->id) }}" class="btn btn-danger"
                                                    title="Delete Bank">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                <a title="Bank Details" style="margin-left: 5px;"
                                                    href="{{ route('bank.details', $bank->id) }}" class="btn btn-dark"
                                                    title="Bank Details">
                                                    <i class="fa fa-eye" aria-hidden="true"></i> Bank Details
                                                </a>
                                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
@endsection
