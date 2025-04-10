<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Adjustment Product Stock Add</h5>
            <h5 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(URL::previous()); ?>" class="btn btn-info">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    Back</a>
            </h5>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(route('product.adjustment.store')); ?>" method="POST" class="custom-validation"
                            novalidate="" autocomplete="off">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-12">
                                    <div class="col-4">
                                        <input type="hidden" name="stock_no" value="<?php echo e($stock_no); ?>">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-5 table-responsive">
                                    <table class="table border table-responsive table-striped">
                                        <thead class="bg-body">
                                            <tr>
                                                <th class="text-center" width="15%">Category</th>
                                                <th class="text-center" width="30%">Product</th>
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
                                            <tr class="tr">
                                                <td class="text-center">
                                                    <select name="category_id[]" id="category_1"
                                                        class="form-control form-select category" required=""
                                                        data-parsley-required-message="Category Id is required">
                                                        <option selected value="">Select Category</option>
                                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($category->id); ?>">
                                                                <?php echo e($category->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <select name="product_id[]" id="product_1"
                                                        class="form-control product form-select select2" required=""
                                                        data-parsley-required-message="Product Id is required">
                                                        <option selected value="">Select Product</option>
                                                    </select>
                                                </td>
                                                <td  class="text-center">
                                                    <input type="text" class="form-control quantity"
                                                        placeholder="Quantity" name="quantity[]" id="quantity"
                                                        required=""
                                                        data-parsley-required-message="Quantity Id is required">
                                                </td>
                                                <td  class="text-center">
                                                    <input type="text" class="form-control unit_price"
                                                        placeholder="Unit Price" name="unit_price[]" id="unit_price_1"
                                                        required=""
                                                        data-parsley-required-message="Unit Price is required">
                                                </td>
                                                <td  class="text-center">
                                                    <input type="text" class="form-control total_amount"
                                                        placeholder="Total" name="total_amount[]" readonly>
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
                                                <th colspan="3"> </th>
                                                <th class="text-end"> Grand Total</th>
                                                <th>
                                                    <input type="number" readonly class="form-control"
                                                        name="estimated_total" id="estimated_total"
                                                        placeholder="Grand Total" value="0" min="-999999999999">

                                                    <input type="hidden" class="form-control" placeholder="Total"
                                                        name="total_quantity" id="total_quantity" readonly>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-info" id="storeButton">Submit</button>
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
        let count = 2;

        function cloneRow() {
            const tr = `
            <tr class="tr">
                <td class="text-center">
                    <select name="category_id[]" id="category_${count}"
                        class="form-control form-select category" required=""
                        data-parsley-required-message="Category Id is required">
                        <option selected value="">Select Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>">
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>
                <td class="text-center">
                    <select name="product_id[]" id="product_${count}"
                        class="form-control product form-select product" required=""
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
                url: "<?php echo e(route('get.products', '')); ?>" + "/" + id,
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\angelrose-software\resources\views/admin/adjustment/adjustment_stock/add_adjustment_stock.blade.php ENDPATH**/ ?>