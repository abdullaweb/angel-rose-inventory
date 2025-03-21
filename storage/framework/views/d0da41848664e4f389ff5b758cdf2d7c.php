<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Purchase No</label>
                                    <input class="form-control" type="text" name="purchase_no"
                                        value="<?php echo e($purchase->purchase_no); ?>" id="purchase_no" readonly disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="example-text-input" class="col-sm-12 col-form-label">Date</label>
                                    <input type="text" class="form-control" disabled name="date" id="date"
                                        value="<?php echo e($purchase->date); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-3">
                                    <label for="supplier_id" class="col-sm-12 col-form-label">Supplier
                                        Name</label>
                                    <input type="text" class="form-control" name="supplier_id" id="supplier_id"
                                        value="<?php echo e($purchase->supplier->name); ?>" disabled>
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
                                        <?php $__currentLoopData = $purchase->purchaseMeta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="tr">
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="category_id"
                                                        id="category_id" value="<?php echo e($item->category->name); ?>" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="product_id"
                                                        id="product_id" value="<?php echo e($item->product->name); ?>" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control quantity"
                                                        placeholder="Quantity" name="quantity[]" id="quantity"
                                                        value="<?php echo e($item->quantity); ?>" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control unit_price"
                                                        placeholder="Unit Price" name="unit_price[]" id="unit_price"
                                                        value="<?php echo e($item->unit_price); ?>" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control total_amount"
                                                        placeholder="Total" readonly
                                                        value="<?php echo e($item->quantity * $item->unit_price); ?>" disabled>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">
                                                <label for="">Paid Amount</label>
                                                <input type="text" name="paid_amount" id="paid_amount"
                                                    placeholder="Enter Paid Amount" class="form-control"
                                                    value="<?php echo e($purchase->paid_amount); ?>" disabled>
                                            </th>
                                            <th>
                                                <label for="">Total Amount</label>
                                                <input type="text" readonly class="form-control" name="estimated_total"
                                                    id="estimated_total" placeholder="Grand Total"
                                                    value="<?php echo e($purchase->total_amount); ?>" disabled>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/purchase_page/purchase_view.blade.php ENDPATH**/ ?>