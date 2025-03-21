@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><strong>
                                                    <h5 class="text-muted">Supplier Name</h5>
                                                </strong></th>
                                            <th class="text-center"><strong>
                                                    <h5 class="text-muted">Supplier Mobile</h5>
                                                </strong></th>
                                            <th class="text-center"><strong>
                                                    <h5 class="text-muted">Supplier Address</h5>
                                                </strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">
                                                <strong>{{ $supplierInfo->name }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $supplierInfo->phone }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $supplierInfo->address }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <form method="POST" class="custom-validation" action="{{ route('store.purchase.due') }}"
                                novalidate="">
                                @csrf
                                <input type="hidden" name="id" value="{{ $purchaseInfo->id }}">
                                <input type="hidden" name="supplier_id" value="{{ $supplierInfo->id }}">

                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3 mt-3">
                                            <label for="">Total Bill Amount </label>
                                            <input type="text" id="total_amount" name="total_amount" class="form-control"
                                                required="" placeholder="Total Amount"
                                                data-parsley-required-message="Purcahse Total Bill"
                                                value="{{ $purchaseInfo->total_amount }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3 mt-3">
                                            <label for="">Total Due Amount </label>
                                            <input type="text" id="due_amount" name="due_amount" class="form-control"
                                                required="" placeholder="Total Due Amount"
                                                data-parsley-required-message="Company Total Bill"
                                                value="{{ $purchaseInfo->due_amount }}" readonly>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <div>
                                                <label for="">Payment Date </label>
                                                <input type="text" class="form-control date_picker" name="date"
                                                    id="date" placeholder="Enter Payment Date" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="">Payment Status </label>
                                                <select class="form-control" name="paid_status" id="paid_status" required>
                                                    <option value="" selected disabled>Select Paid Status</option>
                                                    <option value="full_paid">Full Paid</option>
                                                    <option value="partial_paid">Partial Paid</option>
                                                </select>
                                                <input type="text" placeholder="Enter Paid Amount" class="form-control"
                                                    name="paid_amount" id="paid_amount" style="display:none;">
                                            </div>
                                            <div class="col-md-6" id="paid_source_col" style="display: none;">
                                                <label for="">Payment Type </label>
                                                <select class="form-control" name="paid_source" id="paid_source">
                                                    <option value="" selected disabled>Select Payment Status</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="bank">Bank</option>
                                                    <option value="online-banking">Online Banking</option>
                                                    <option value="mobile-banking">Mobile Banking</option>
                                                </select>

                                                <div class="row" id="bank-info" style="display: none;">
                                                    <div class="col-12">
                                                        <select name="bank_name" id="bank_name" class="form-control">
                                                            <option value="" selected disabled>Select Bank</option>
                                                            <option value="islami">Islami</option>
                                                            <option value="sonali">Sonali</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="text" placeholder="Check Number"
                                                            class="form-control" name="check_number" id="check_number">
                                                    </div>
                                                </div>
                                                <div class="row" id="online-bank-row" style="display: none;">
                                                    <div class="col-12">
                                                        <input type="text" placeholder="Note" class="form-control"
                                                            name="note" id="note">
                                                    </div>
                                                </div>

                                                <div class="row" id="mobile-bank-info" style="display: none;">
                                                    <div class="col-12">
                                                        <select name="mobile_bank" id="mobile_bank" class="form-control">
                                                            <option value="" selected disabled>Select Mobile Bank
                                                            </option>
                                                            <option value="bkash">Bkash</option>
                                                            <option value="nagad">Nagad</option>
                                                            <option value="rocket">Rocket</option>
                                                            <option value="ucash">ucash</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="text" placeholder="Transaction Number"
                                                            class="form-control" name="transaction_number"
                                                            id="transaction_number">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                            Due Payment
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

    <script type="text/javascript">
        $(document).ready(function() {
            // paid source
            $('#paid_status').on('change', function() {
                let paidSource = $(this).val();
                console.log('paidSource', paidSource);
                if (paidSource == 'check' || paidSource == 'online-banking') {
                    $('#check_or_banking').show();
                } else {
                    $('#check_or_banking').hide();
                }
            });
        });
    </script>

    <!-- paid status -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#paid_status').on('change', function() {
                let paidStatus = $(this).val();
                console.log('paidSource', paidStatus);
                if (paidStatus) {
                    $('#paid_source_col').show();
                    $('#vat_tax_col').show();
                }

                if (paidStatus == 'partial_paid') {
                    $('#paid_amount').show();
                } else {
                    $('#paid_amount').hide();
                }

                if (paidStatus == 'full_due') {
                    $('#paid_source_col').hide();
                }

            });


            $('#paid_source').on('change', function() {
                let paidSource = $(this).val();
                console.log('paidSource', paidSource);
                if (paidSource == 'bank') {
                    $('#bank-info').show();
                    $('#online-bank-row').hide();
                    $('#mobile-bank-info').hide();
                } else if (paidSource == 'online-banking') {
                    $('#bank-info').hide();
                    $('#online-bank-row').show();
                    $('#mobile-bank-info').hide();
                } else if (paidSource == 'mobile-banking') {
                    $('#bank-info').hide();
                    $('#online-bank-row').hide();
                    $('#mobile-bank-info').show();
                }
            });

            $('#vat_tax_field').on('change', function() {
                let vatTaxField = $(this).val();
                console.log('vat_tax_field', vatTaxField);
                if (vatTaxField == 'with-vat-tax') {
                    $('.vat').show();
                    $('.tax').show();
                } else {
                    $('.vat').hide();
                    $('.tax').hide();
                }
            });

            // new customer
            $('#company_id').on('change', function() {
                let compnayId = $(this).val();
                console.log(compnayId);
                if (compnayId == '0') {
                    $('#new_company').show();
                    $('#default_addBtn').hide();
                } else {
                    $('#new_company').hide();
                    $('#default_addBtn').show();
                }
            });
        });
    </script>
@endsection
