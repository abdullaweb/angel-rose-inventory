@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Edit Adjustment Stock Update</h5>
            <h5 class="m-0 font-weight-bold text-primary">
                <a href="{{ URL::previous() }}" class="btn btn-info">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Back</a>
            </h5>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('product.adjustment.update') }}" method="POST" class="custom-validation"
                            novalidate="" autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="{{ $stockInfo->id }}">
                            <div class="row">
                                <div class="col-md-12 mt-5 table-responsive">
                                    <table class="table border table-responsive table-striped">
                                        <thead class="bg-body">
                                            <tr>
                                                <th class="text-center" width="15%">Category</th>
                                                <th class="" width="30%">Product</th>
                                                <th class="text-center" width="15%">Quantity</th>
                                                <th class="text-center" width="15%">Unit Price</th>
                                                <th class="text-center" width="20%">Total</th>
                                                <th class="text-center">
                                                    <button class="btn btn-success" type="button" onclick="cloneRow()">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            @foreach ($purchaseStore as $key => $item)
                                            @php
                                                $purchaseMeta = App\Models\PurchaseMeta::where('purchase_id', $item->purchase_id)
                                                    ->first();
                                            @endphp
                                                <tr class="tr">
                                                    <td class="text-center">
                                                        <select name="category_id[]" id="category_{{ $key + 1 }}"
                                                            class="form-control form-select category" required=""
                                                            data-parsley-required-message="Category Id is required">
                                                            <option selected value="">Select Category</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ $purchaseMeta->category_id == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            $productSingle = App\Models\Product::where(
                                                                'id',
                                                                $item->product_id,
                                                            )
                                                                ->get();
                                                        @endphp
                                                        <select name="product_id[]" id="product_{{ $key + 1 }}"
                                                            class="form-control product form-select select2" required=""
                                                            data-parsley-required-message="Product Id is required">
                                                            <option selected value="">Select Product</option>
                                                            @foreach ($productSingle as $product)
                                                                <option value="{{ $product->id }}"
                                                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td width="10%" class="text-center">
                                                        <input type="text" class="form-control quantity"
                                                            placeholder="Quantity" name="quantity[]" id="quantity"
                                                            required=""
                                                            data-parsley-required-message="Quantity Id is required"
                                                            value="{{ $item->quantity }}">
                                                    </td>
                                                    <td width="10%" class="text-center">
                                                        <input type="text" class="form-control unit_price"
                                                            placeholder="Unit Price" name="unit_price[]"
                                                            id="unit_price_{{ $key + 1 }}" required=""
                                                            data-parsley-required-message="Unit Price is required"
                                                            value="{{ $item->unit_price }}">
                                                    </td>
                                                    <td width="10%" class="text-center">
                                                        <input type="text" class="form-control total_amount"
                                                            placeholder="Total" name="total_amount[]"
                                                            value="{{ $item->quantity * $item->unit_price }}" readonly>
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
                                                <th colspan="3"> </th>
                                                <th class="text-end"> Grand Total</th>
                                                <th>
                                                    <input type="text" readonly class="form-control"
                                                        name="estimated_total" id="estimated_total"
                                                        placeholder="Grand Total" value="{{ $stockInfo->total_amount }}"
                                                        min="0">

                                                    <input type="hidden" class="form-control" placeholder="Total"
                                                        name="total_quantity" id="total_quantity"
                                                        value="{{ $stockInfo->total_qty }}" readonly>

                                                    <input type="hidden" class="form-control" placeholder="Total"
                                                        name="total_stock" id="total_stock"
                                                        value="{{ count($purchaseStore) }}" readonly>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-info"
                                                    id="storeButton">Submit</button>
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

            $(document).on("keyup click", ".quantity", function() {
                let product_qty = $(this).closest('tr').find('input.quantity').val();
                totalQuantity();
            });
        });
    </script>
    <script>
        let count = parseInt($("#total_stock").val()) + 1;

        function cloneRow() {
            const tr = `
            <tr class="tr">
                <td class="text-center">
                    <select name="category_id[]" id="category_${count}"
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
                    <select name="product_id[]" id="product_${count}"
                        class="form-control form-select select2 product" required=""
                        data-parsley-required-message="Product Id is required">
                        <option selected value="">Select Product</option>
                    </select>
                </td>
                <td class="text-center">
                    <input type="text" class="form-control quantity"
                        placeholder="Quantity" name="quantity[]" id="quantity"
                        required=""
                        data-parsley-required-message="Quantity Id is required">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control unit_price"
                        placeholder="Unit Price" name="unit_price[]" id="unit_price_${count}"
                        required=""
                        data-parsley-required-message="Unit Price is required">
                </td>
                <td class="text-center">
                    <input type="text" class="form-control total_amount"
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


        $(document).on("change", ".category", function() {
            // const id = $(this).closest('tr').find('option:selected').val();
            const id = $(this).val();
            console.log(id);

            let dataId = $(this).attr('id');
            let num = dataId.split('_');


            $.ajax({
                type: 'GET',
                url: "{{ route('get.products', '') }}" + "/" + id,
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
        // calculate sum of amount
        function totalAmountOfPrice() {
            let sum = 0;
            $('.total_amount').each(function() {
                let value = $(this).val();
                if (!isNaN(value) && value.length != 0) {
                    sum += parseFloat(value);
                }
            });
            $("#estimated_total").val(Math.round(sum));
        }


        function totalQuantity() {
            let quantity = 0;
            $('.quantity').each(function() {
                let value = $(this).val();
                if (!isNaN(value) && value.length != 0) {
                    quantity += parseFloat(value);
                }
            });
            // console.log('quantity',quantity);
            $("#total_quantity").val(quantity);
        }
    </script>

    <script>
        $(document).on("change", ".product", function() {
            const id = $(this).closest('tr').find('option:selected').val();
            const product = $(this).closest('.product').find('option:selected').text();
            let product_id = $(this).closest('tr').find('.product').val();


            let unitId = $(this).closest('tr').find('.unit_price').attr('id');
            let unitNum = unitId.split('_');



            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/product/price/opening',
                type: "post",
                data: {
                    product_id: product_id,
                },

                success: function(data) {
                    console.log(data);
                    $("#unit_price_" + unitNum[2]).val(data.purchasePrice);
                }
            });
        });
    </script>
@endsection
