@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Invoice No</label>
                                    <input class="form-control" type="text" name="invoice_no"
                                        value="{{ $invoice->invoice_no }}" id="purchase_no" readonly disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Date</label>
                                    <input type="text" class="form-control" disabled name="date" id="date"
                                        value="{{ $invoice->date }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="supplier_id" class="col-sm-12 col-form-label">Customer
                                        Name</label>
                                    <input type="text" class="form-control" name="supplier_id" id="supplier_id"
                                        value="{{ $invoice->payment->customer->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <table class="table table-responsive table-striped">
                                    <thead class="bg-body">
                                        <tr>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach ($invoice->invoice_details as $item)
                                            <tr class="tr">
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="category_id"
                                                        id="category_id" value="{{ $item->category->name }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="product_id"
                                                        id="product_id" value="{{ $item->product->name }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control quantity"
                                                        placeholder="Quantity" name="quantity[]" id="quantity"
                                                        value="{{ $item->selling_qty }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control unit_price"
                                                        placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                        value="{{ $item->unit_price }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control total_amount"
                                                        placeholder="Total" readonly
                                                        value="{{ $item->selling_price }}" disabled>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">
                                                <label for="">Paid Amount</label>
                                                <input type="text" name="paid_amount" id="paid_amount"
                                                    placeholder="Enter Paid Amount" class="form-control"
                                                    value="{{ $invoice->payment->paid_amount }}" disabled>
                                            </th>
                                            <th>
                                                <label for="">Total Amount</label>
                                                <input type="text" readonly class="form-control" name="estimated_total"
                                                    id="estimated_total" placeholder="Grand Total"
                                                    value="{{ $invoice->payment->total_amount }}" disabled>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="form-group mt-5">

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
    {{-- <script>
        function cloneRow() {
            const tr = `
            <tr class="tr">
                                            <td class="text-center">
                                                <select name="category_id[]" id="category_id"
                                                    class="form-control form-select select2">
                                                    <option selected value="">Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select name="product_id[]" id="product_id"
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
        }

        function removeRow(event) {
            if ($('.tr').length > 1) {
                $(event.target).closest('.tr').remove();
                totalAmountOfPrice();
            }
        }
    </script> --}}

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
@endsection
