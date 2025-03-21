@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('update.purchase') }}" method="POST" class="custom-validation" novalidate=""
                            autocomplete="off" onsubmit="disableBtn(this.querySelector('button[type=submit]'))">
                            @csrf
                            <input type="hidden" name="id" value="{{ $purchase->id }}">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="example-text-input" class="col-sm-12 col-form-label">Purchase
                                                No</label>
                                            <input class="form-control" type="text" name="purchase_no"
                                                value="{{ $purchase->purchase_no }}" id="purchase_no" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="example-text-input" class="col-sm-12 col-form-label">Date</label>
                                            <input type="text" class="form-control date_picker" name="date"
                                                id="date" value="{{ $purchase->date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="supplier_id" class="col-sm-12 col-form-label">Supplier
                                                Name</label>
                                            <select name="supplier_id" id="supplier_id"
                                                class="form-control form-select select2" required=""
                                                data-parsley-required-message="Supplier Id is required">
                                                <option selected value="">Select Supplier Name</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-5">
                                        <table class="table table-responsive table-striped">
                                            <thead class="bg-body">
                                                <tr>
                                                    <th class="text-center" width="20%">Category</th>
                                                    <th class="text-center" width="30%">Product</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">Unit Price</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">
                                                        <button class="btn btn-success" type="button" onclick="cloneRow()">
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody">
                                                @foreach ($purchase->purchaseMeta as $item)
                                                    <tr class="tr">
                                                        <td class="text-center">
                                                            <select name="category_id[]" id="category_1"
                                                                class="form-control form-select category"
                                                                required=""
                                                                data-parsley-required-message="Category Id is required">
                                                                <option selected value="">Select Category</option>
                                                                @foreach ($categories as $category)
                                                                    <option class="option" value="{{ $category->id }}"
                                                                        {{ $category->id == $item->category_id ? 'selected' : '' }}>
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <select name="product_id[]" id="product_id_1"
                                                                class="form-control form-select select2" required=""
                                                                data-parsley-required-message="Product Id is required">
                                                                <option selected value="">Select Product</option>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                                                        {{ $product->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control quantity"
                                                                placeholder="Quantity" name="quantity[]" id="quantity"
                                                                value="{{ $item->quantity }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control unit_price"
                                                                placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                                value="{{ $item->unit_price }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control total_amount"
                                                                placeholder="Total" readonly
                                                                value="{{ $item->quantity * $item->unit_price }}" disabled>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" onclick="removeRow(event)"
                                                                class="btn btn-danger">
                                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4">
                                                        <label for="">Paid Amount</label>
                                                        <input type="text" readonly  name="previous_paid"
                                                            placeholder="Enter Paid Amount" class="form-control"
                                                            value="{{ $purchase->paid_amount }}">
                                                    </th>
                                                    <th>
                                                        <label for="">Total Amount</label>
                                                        <input type="text" readonly class="form-control"
                                                            name="estimated_total" id="estimated_total"
                                                            placeholder="Grand Total"
                                                            value="{{ $purchase->total_amount }}" readonly>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>


                                        <div class="row mb-3 mt-5">
                                            <div class="col-md-4">
                                                {{-- <label for="">Payment Status </label> --}}
                                                <select class="form-control" name="paid_status" id="paid_status"
                                                    required=""
                                                    data-parsley-required-message="Paid Status is required">
                                                    <option value="" selected disabled>Select Paid Status
                                                    </option>
                                                    <option value="full_paid">Full Paid</option>
                                                    <option value="full_due">Full Due</option>
                                                    <option value="partial_paid">Partial Paid</option>
                                                </select>
                                                <input type="text" placeholder="Enter Paid Amount"
                                                    class="form-control" name="paid_amount" id="paid_amount"
                                                    style="display:none;">
                                            </div>
                                            <div class="col-md-4" id="paid_source_col" style="display: none;">
                                                {{-- <label for="">Payment Type </label> --}}
                                                <select class="form-control" name="paid_source" id="paid_source">
                                                    <option value="" selected disabled>Select Payment Status
                                                    </option>
                                                    <option value="cash">Cash</option>
                                                    <option value="bank">Bank</option>
                                                    <option value="online-banking">Online Banking</option>
                                                    <option value="mobile-banking">Mobile Banking</option>
                                                </select>

                                                <div class="row" id="bank-info" style="display: none;">
                                                    <div class="col-12">
                                                        <select name="bank_name" id="bank_name" class="form-control">
                                                            <option value="" selected disabled>Select Bank</option>
                                                            @foreach ($allBank as $bank)
                                                                <option value="{{ $bank->id }}">{{ $bank->name }} -
                                                                    {{ $bank->branch_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="text" placeholder="Note" class="form-control"
                                                            name="check_number" id="check_number">
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
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-info" id="storeButton">Update
                                                        Purchase</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- js --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script>
        $(document).ready(function() {

            $(document).on("keyup click", ".unit_price,.quantity", function() {
                let product_qty = $(this).closest('tr').find('input.quantity').val();
                let unit_price = $(this).closest('tr').find('input.unit_price').val();
                let total = unit_price * product_qty;
                console.log(total);
                $(this).closest('tr').find('input.total_amount').val(total);
                totalAmountOfPrice();
            });
        });
    </script>
    <script>
        let count = 2;

        function cloneRow() {
            let tr = `
            <tr class="tr">
                <td class="text-center">
                    <select name="category_id[]" id="category_${count}"
                        class="form-control form-select category">
                        <option selected value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="text-center">
                    <select name="product_id[]" id="product_${count}"
                        class="form-control form-select select2">
                        <option selected value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="text-center">
                    <input type="text" class="form-control quantity" placeholder="Quantity"
                        name="quantity[]" id="quantity">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control unit_price" placeholder="Unit Price"
                        name="unit_price[]" id="unit_price">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control total_amount" placeholder="Total"
                        name="total" id="total" readonly>
                </td>
                <td class="text-center">
                    <button type="button" onclick="removeRow(event)" class="btn btn-danger">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>

            `;
            $('.tbody').append(tr);
            $('.select2').select2();
            count++;
        }

        function removeRow(event) {
            if ($('.tr').length > 1) {
                $(event.target).closest('.tr').remove();
                totalAmountOfPrice();
            }
        }
    </script>

    <script>
        // calculate sum of amount
        function totalAmountOfPrice() {
            let sum = 0;
            $('.total_amount').each(function() {
                let value = $(this).val();
                if (!isNaN(value) && value.length != 0) {
                    sum += parseFloat(value);
                }
            });
            $("#estimated_total").val(sum);
        }
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
                } else if (paidSource == 'cash') {
                    $('#bank-info').hide();
                    $('#online-bank-row').hide();
                    $('#mobile-bank-info').hide();
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
        });
    </script>

    <script>
        function disableBtn(button) {
            button.disabled = true;
            button.innerHTML = 'Processing...';
        }
    </script>
@endsection
