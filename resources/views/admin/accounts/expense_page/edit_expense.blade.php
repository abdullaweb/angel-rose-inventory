@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Update Expense</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Expense</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container">
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('update.expense') }}" method="POST" id="AddExpense">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $expenseInfo->id }}">

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Expense Purpose</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">

                                            @if (
                                                $expenseInfo->head == 'Salary' ||
                                                    $expenseInfo->head == 'House Rent' ||
                                                    $expenseInfo->head == 'Utility' ||
                                                    $expenseInfo->head == 'Snacks')
                                                <select name="expense_head" id="expense_head" class="form-control" required
                                                    data-parsley-required-message="Expense Purpose is required."
                                                    aria-readonly="true">

                                                    <option value="House Rent"
                                                        {{ $expenseInfo->head == 'House Rent' ? 'selected' : '' }}>
                                                        House Rent</option>
                                                    <option value="Salary"
                                                        {{ $expenseInfo->head == 'Salary' ? 'selected' : '' }}>
                                                        Salary</option>
                                                    <option value="Utility"
                                                        {{ $expenseInfo->head == 'Utility' ? 'selected' : '' }}>
                                                        Utility</option>
                                                    <option value="Snacks"
                                                        {{ $expenseInfo->head == 'Snacks' ? 'selected' : '' }}>
                                                        Snacks</option>
                                                    <option value="Other">Others</option>
                                                </select>
                                                <input style="display: none;" type="text" name="others"
                                                    placeholder="Enter Your Purpose" class="form-control others">
                                            @else
                                                <select name="expense_head" id="expense_head" class="form-control" required
                                                    data-parsley-required-message="Expense Purpose is required."
                                                    aria-readonly="true">

                                                    <option value="House Rent">House Rent</option>
                                                    <option value="Salary">Salary</option>
                                                    <option value="Utility">Utility</option>
                                                    <option value="Snacks">Snacks</option>
                                                    <option value="Other" selected>Others</option>
                                                </select>
                                                <input type="text" name="others" value="{{ $expenseInfo->head }}"
                                                    class="form-control others">
                                            @endif




                                            {{-- <select name="expense_head" id="expense_head" class="form-control" required
                                                data-parsley-required-message="Expense Purpose is required."
                                                aria-readonly="true">
                                                @if ($expenseInfo->head !== 'House-Rent' || $expenseInfo->head !== 'Salary' || $expenseInfo->head !== 'Utility' || $expenseInfo->head !== 'Snacks')
                                                    <option value="Other">Others</option>
                                                    <input value="{{ $expenseInfo->head }}" type="text" name="others"
                                                        placeholder="Enter Your Purpose" class="form-control others">
                                                @else
                                                    <option value="">Select Expense Purpose</option>
                                                    <option value="House-Rent"
                                                        {{ $expenseInfo->head == 'House-Rent' ? 'selected' : '' }}>House
                                                        Rent
                                                    </option>
                                                    <option value="Salary"
                                                        {{ $expenseInfo->head == 'Salary' ? 'selected' : '' }}>Salary
                                                    </option>
                                                    <option value="Utility"
                                                        {{ $expenseInfo->head == 'Utility' ? 'selected' : '' }}>Utility Bill
                                                    </option>
                                                    <option value="Snacks"
                                                        {{ $expenseInfo->head == 'Snacks' ? 'selected' : '' }}>Snacks
                                                    </option>
                                                @endif
                                            </select> --}}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Expense Description</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input type="text" name="description" class="form-control" id="description"
                                                value="{{ $expenseInfo->description }}" required
                                                data-parsley-error-message="Description is required" />
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Amount</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            @error('amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input type="text" name="amount" class="form-control" id="amount"
                                                value="{{ $expenseInfo->amount }}" required
                                                data-parsley-error-message="Amount is required" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Date</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input type="text" name="date" class="form-control date_picker"
                                                id="date" placeholder="Enter Date" required
                                                data-parsley-error-message="Date is required"
                                                value="{{ $expenseInfo->date }}" />
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9 col-lg-9 text-secondary">
                                            <input type="submit" class="btn btn-primary px-4" value="Update Expense" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- js --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#AddExpense').parsley();


            $(document).on('change', '#expense_head', function() {
                let expense_head = $(this).val();
                if (expense_head == 'Other') {
                    $('.others').show();
                } else {
                    $('.others').hide();
                }
            });
        });
    </script>
@endsection
