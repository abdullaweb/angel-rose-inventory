<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-muted">Add Opening Balance</h2>
                        <form class="custom-validation" action="<?php echo e(route('store.opening.balance')); ?>" method="POST"
                            novalidate="">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <div class="mb-2">
                                        <select name="customer_id" id="customer_id" class="form-control select2" required
                                            data-parsley-required-message="Customer is required" autocomplete="off">
                                            <option disabled selected>Select Wholesaler</option>
                                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?> -
                                                    <?php echo e($customer->phone); ?> </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="mb-2">
                                        <input type="digit" id="total_amount" name="total_amount" class="form-control"
                                            required="" data-parsley-trigger="keyup"
                                            data-parsley-validation-threshold="0" placeholder="Total Amount"
                                            data-parsley-type="number"
                                            data-parsley-type-message="Input must be positive number"
                                            data-parsley-required-message="Total Amount is required" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="mb-3">
                                        <input type="text" autocomplete="off" id="date" name="date"
                                            class="form-control date_picker" required
                                            data-parsley-required-message="Date is required" placeholder="Enter Your Date">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                    Add Balance
                                </button>
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
            $('input[type=radio][name="opening_type"]').change(function() {
                let status = $(this).val();
                if (status == 'billwise_balance') {
                    $('#bill_no_field').show();
                } else {
                    $('#bill_no_field').hide();
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/wholesaler/opening_balance/add_opening.blade.php ENDPATH**/ ?>