@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-muted">{{ $title }}</h4>
                            <form class="custom-validation" action="{{ route('update.bank') }}" method="POST" novalidate=""
                                autocomplete="off">
                                @csrf
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <input type="hidden" name="id" value="{{ $bankInfo->id }}">
                                        <div class="mb-3">
                                            <input type="text" id="bank_name" name="bank_name" class="form-control"
                                                required="" placeholder="Bank Name" value="{{ $bankInfo->name }}"
                                                data-parsley-required-message="Bank Name is required">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" id="account_number" name="account_number"
                                                class="form-control @error('account_number') is-invalid @enderror"
                                                required="" placeholder="Bank Account Number"
                                                data-parsley-required-message="Bank Account Number is required"
                                                value="{{ $bankInfo->account_number }}">
                                            @error('account_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" name="branch_name" class="form-control"
                                                placeholder="Enter Branch Name" required
                                                data-parsley-required-message="Branch Name is required"
                                                value="{{ $bankInfo->branch_name }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="digit" name="opening_balance" id="opening_balance"
                                                class="form-control" placeholder="Opening Balance"
                                                value="{{ $bankInfo->opening_balance }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                            Update Bank Info
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
@endsection
