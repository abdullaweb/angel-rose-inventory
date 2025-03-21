@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('invoice.store') }}" method="POST" class="custom-validation" novalidate="" onsubmit="disableBtn(this.querySelector('button[type=submit]'))">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="md-3">
                                        <label for="invoice_no" class="col-sm-12 col-form-label">Invoice No</label>
                                        <input class="form-control" type="text" name="invoice_no"
                                            value="{{ $invoice_no }}" id="invoice_no" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="md-3">
                                        <label for="date" class="col-sm-12 col-form-label">Date</label>
                                        <input type="date" class="form-control date_picker" name="date" id="date"
                                            required="" data-parsley-required-message="Purchase Date is required">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="md-3">
                                        <label for="customer_type" class="col-sm-12 col-form-label">Customer
                                            Type</label>
                                        <select name="customer_type" id="customer_type"
                                            class="form-control form-select" required=""
                                            data-parsley-required-message="Customer Type is required">
                                            <option selected value="">Select Customer Type</option>
                                            <option value="0">Retail</option>
                                            <option value="1">Wholesale</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="md-3">
                                        <label for="customer_id" class="col-sm-12 col-form-label">Customer
                                            Name</label>
                                        <select name="customer_id" id="customer_id" class="form-control form-select select2"
                                            required="" data-parsley-required-message="Customer Id is required">
                                            <option selected value="">Select Customer Name</option>
                                            {{-- @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">
                                                    {{ $customer->name }} - {{ $customer->phone }}
                                                </option>
                                            @endforeach --}}
                                            <option value="0">New Customer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3" id="customer-info" style="display: none;">
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="customer_name" class="col-sm-12 col-form-label">Customer Name</label>
                                        <input type="text" class="form-control" name="customer_name" id="customer_name"
                                            placeholder="Customer Name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="customer_email" class="col-sm-12 col-form-label">Customer Email</label>
                                        <input type="email" class="form-control" name="customer_email" id="customer_email"
                                            placeholder="Customer Email">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="customer_phone" class="col-sm-12 col-form-label">Customer Phone</label>
                                        <input type="tel" class="form-control" name="customer_phone" id="customer_phone"
                                            placeholder="Customer Phone">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-5">
                                    <table class="table border table-responsive table-striped">
                                        <thead class="bg-body">
                                            <tr>
                                                <th class="text-center">Category</th>
                                                <th class="text-center" width="25%">Product</th>
                                                <th class="text-center">Current Stock</th>
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
                                            <tr class="tr">
                                                <td class="text-center">
                                                    <select name="category_id[]" id="category_1"
                                                        class="form-control form-select category" required=""
                                                        data-parsley-required-message="Category Id is required">
                                                        <option selected value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <select name="product_id[]" id="product_1"
                                                        class="form-control form-select product" required=""
                                                        data-parsley-required-message="Product Id is required">
                                                        <option value="">Select Product</option>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control stock"
                                                        placeholder="Stock Quantity" name="stock_qty[]" id="stock_qty_1"
                                                        readonly>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control quantity"
                                                        placeholder="Quantity" name="selling_qty[]" id="selling_qty"
                                                        required=""
                                                        data-parsley-required-message="Quantity Id is required">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control unit_price"
                                                        placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                        required=""
                                                        data-parsley-required-message="Unit Price is required">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control selling_price"
                                                        placeholder="Total" readonly name="selling_price[]">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" onclick="removeRow(event)"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6"></th>
                                                <tr>
                                                    <th colspan="4"> </th>
                                                    <th class="text-end" width="15%">
                                                        <select name="discount_type" class="form-control discount_type" id="discount_type">
                                                            <option value="" selected>Select Disocunt Type</option>
                                                            <option value="flat">Flat</option>
                                                            <option value="percentage">Percentage</option>
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <input type="number" name="discount_rate" id="discount_rate"
                                                            class="form-control discount_rate" placeholder="Discount Amount">
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="5"> </th>
                                                    <th>
                                                        <input type="text" readonly class="form-control"
                                                            name="estimated_total" id="estimated_total"
                                                            placeholder="Grand Total" value="0">
                                                    </th>
                                                </tr>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <select class="form-control" name="paid_status" id="paid_status" required>
                                            <option value="" selected disabled>Select Paid Status</option>
                                            <option value="full_paid">Full Paid</option>
                                            <option value="full_due">Full Due</option>
                                            <option value="partial_paid">Partial Paid</option>
                                        </select>
                                        <input type="text" placeholder="Enter Paid Amount" class="form-control" name="paid_amount" id="paid_amount" style="display:none;">
                                    </div>
                                    <div class="col-md-3" id="paid_source_col" style="display: none;">
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
                                                    <option value="" selected disabled>Select Mobile Bank</option>
                                                    <option value="bkash">Bkash</option>
                                                    <option value="nagad">Nagad</option>
                                                    <option value="rocket">Rocket</option>
                                                    <option value="ucash">ucash</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <input type="text" placeholder="Transaction Number"
                                                    class="form-control" name="transaction_nmber" id="transaction_nmber">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="vat_tax_col" style="display: none;">
                                        <select name="vat_tax_field" id="vat_tax_field" class="form-control" required>
                                            <option value="" selected disabled>Select Vat Tax</option>
                                            <option value="with-vat-tax">With Vat/Tax</option>
                                            <option value="without-vat-tax">Without Vat/Tax</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-info" id="storeButton">Invoice Store</button>
                                </div>
                            </div>
                        </form>
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

            $(document).on("keyup click", ".unit_price,.quantity, .discount_type, .discount_rate", function() {
                let product_qty = $(this).closest('tr').find('input.quantity').val();
                let unit_price = $(this).closest('tr').find('input.unit_price').val();
                let total = unit_price * product_qty;
                console.log(total);
                $(this).closest('tr').find('input.selling_price').val(total);
                totalAmountOfPrice();
            });
        });
    </script>
    <script>
        let count = 2;

        function cloneRow() {
            const tr = `
            <tr class="tr">
                <td class="text-center">
                    <select name="category_id[]" id="category_${count}"
                        class="form-control form-select select2 category" required=""
                        data-parsley-required-message="Category Id is required">
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
                        class="form-control form-select select2 product" required=""
                        data-parsley-required-message="Product Id is required">
                        <option value="">Select Product</option>
                    </select>
                </td>
                <td class="text-center">
                        <input type="text" class="form-control stock"
                            placeholder="Stock Quantity" name="stock_qty[]" id="stock_qty_${count}" readonly>
                </td>
                <td class="text-center">
					<input type="text" class="form-control quantity"
						placeholder="Quantity" name="selling_qty[]" id="selling_qty"
						required=""
						data-parsley-required-message="Quantity Id is required">
				</td>
                <td class="text-center">
                    <input type="text" class="form-control unit_price"
                        placeholder="Unit Price" name="unit_price[]" id="unit_price"
                        required=""
                        data-parsley-required-message="Unit Price is required">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control selling_price" name="selling_price[]"
                        placeholder="Total" readonly>
                </td>
                <td class="text-center">
                    <button type="button" onclick="removeRow(event)"
                        class="btn btn-danger">
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



        // $('.category').on('change', function() {
        //     // const id = $(this).val();
        //     const id = $(this).closest('tr').find('option:selected').val();

        //     console.log('cat_id', id);

        //     let dataId = $(this).attr('id');
        //     let num = dataId.split('_');
        //     console.log('num', num, 'data', dataId);


        //     $.ajax({
        //         type: 'GET',
        //         url: "{{ route('get.product', '') }}" + "/" + id,
        //         // dataType = 'json',
        //         success: function(data) {
        //             console.log(data);

        //             let html = '<option value="">Select Product </option>';
        //             $.each(data, function(key, product) {
        //                 html +=
        //                     `<option value="${product.id}">${product.name} </option>`;
        //             });
        //             $("#product_" + num[1]).html(html);
        //         }
        //     });
        // });


        $(document).on("change", ".category", function() {
            // const id = $(this).closest('tr').find('option:selected').val();
            const id = $(this).val();
            console.log(id);


            let dataId = $(this).attr('id');
            let num = dataId.split('_');


            $.ajax({
                type: 'GET',
                url: "{{ route('get.product', '') }}" + "/" + id,
                success: function(data) {

                    let html = '<option value="">Select Product </option>';
                    $.each(data, function(key, product) {
                        html +=
                            `<option value="${product.id}">${product.name}</option>`;
                    });
                    $("#product_" + num[1]).html(html);
                }
            });
        });
    </script>
    <script>
        $(document).on("change", ".product", function() {
            // const id = $(this).closest('tr').find('option:selected').val();
            const id = $(this).val();
            let dataId = $(this).attr('id');
            let num = dataId.split('_');

            $.ajax({
                type: 'GET',
                url: "{{ route('get.product.stock', '') }}" + "/" + id,
                success: function(data) {
                    console.log('date', data);

                     $("#stock_qty_" + num[1]).val(data);
                }
            });
        });
    </script>

    <script>
        // calculate sum of amount
        function totalAmountOfPrice() {
            let sum = 0;
            $('.selling_price').each(function() {
                let value = $(this).val();
                if (!isNaN(value) && value.length != 0) {
                    sum += parseFloat(value);
                }
            });

            let discount_type = $("#discount_type").val();
            let discount_rate = parseFloat($('#discount_rate').val());
            // alert(discount_type);
            let discount_amount = 0;
            if (!isNaN(discount_rate) && discount_rate.length != 0) {
                if (discount_type == 'flat') {
                    sum -= parseFloat(discount_rate);
                    discount_amount = parseFloat(discount_rate);

                } else if (discount_type == 'percentage') {
                    let percentageAmount = Math.round((sum * discount_rate) / 100);
                    discount_amount = percentageAmount;
                    sum -= parseFloat(percentageAmount);
                }
            }

            $("#discount_amount").val(discount_amount);
            $("#after_discount").val(sum);
            $("#estimated_total").val(sum);
            // $("#estimated_total").val(sum);
        }
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#customer_id').on('change', function() {
                let customerId = $(this).val();
                console.log(customerId);
                if (customerId == '0') {
                    $('#customer-info').show();
                } else {
                    $('#customer-info').hide();
                }
            });
        });
    </script>

    {{--  dropdown menu select  --}}
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
    <script>
        $(document).on("change", "#customer_type", function() {
            const id = $(this).val();

            $.ajax({
                type: 'GET',
                url: "{{ route('get.customer', '') }}" + "/" + id,
                success: function(data) {

                    let html = `
                    <option value="">Select Customer </option>
                    <option value="0">New Customer</option>
                    `;
                    $.each(data, function(key, customer) {
                        html +=
                            `<option value="${customer.id}">${customer.name} - ${customer.phone}</option>`;
                    });

                    $("#customer_id").html(html);
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
