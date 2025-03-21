@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Employee</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="{{ route('add.employee') }}">
                    <button class="btn btn-info">Add Employee</button>
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
                                    <th>Employee ID</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Designation</th>
                                    <th>Salary</th>
                                    <th>Joining Date</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Employee ID</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Designation</th>
                                    <th>Salary</th>
                                    <th>Joining Date</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($allData as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->employee_id }}
                                        </td>
                                        <td>
                                            {{ $item->email }}
                                        </td>
                                        <td>
                                            {{ $item->phone }}
                                        </td>
                                        <td>
                                            {{ $item->designation }}
                                        </td>
                                        <td>
                                            {{ $item->salary }}
                                        </td>
                                        <td>
                                            {{ $item->joining_date }}
                                        </td>
                                        <td>
                                            <img src="{{ asset($item->image) }}" class="rounded-circle" width="46"
                                                height="46" alt="" />
                                        </td>
                                        <td style="display:flex">
                                            {{-- @if (Auth::user()->can('employee.edit')) --}}
                                            <a title="Edit Employee" style="margin-left: 5px;"
                                                href="{{ route('edit.employee', $item->id) }}" class="btn btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            {{-- @endif --}}

                                            <a title="Viw Employee" style="margin-left: 5px;"
                                                href="{{ route('employee.view', $item->id) }}" class="btn btn-info">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a title="Payment Details" style="margin-left: 5px;"
                                                href="{{ route('employee.payment.details', $item->id) }}"
                                                class="btn btn-info">
                                                Payment Details
                                            </a>
                                            <a title="Salary Increment" style="margin-left: 5px;"
                                                href="{{ route('employee.salary.increment', $item->id) }}"
                                                class="btn btn-info">
                                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                Increment Salary
                                            </a>
                                            <a title="Employee Salary Details" style="margin-left: 5px;"
                                                href="{{ route('employee.salary.details', $item->id) }}"
                                                class="btn btn-dark">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                Salary Details
                                            </a>
                                            {{-- @if (Auth::user()->can('employee.delete')) --}}
                                            <a title="Delete Employee" id="delete" style="margin-left: 5px;"
                                                href="{{ route('delete.employee', $item->id) }}" class="btn btn-danger"
                                                title="Delete Employee">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            {{-- @endif --}}
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
