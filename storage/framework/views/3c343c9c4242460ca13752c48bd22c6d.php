<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body py-5">
                        <form action="<?php echo e(route('update.opening.balance')); ?>" method="POST" class="custom-validation"
                            novalidate="" autocomplete="off">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <input type="hidden" value="<?php echo e($accountInfo->id); ?>" name="id">
                                <div class="col-12">
                                    <h4 class="m-0 font-weight-bold text-secondary">Update Wholesaler Opening Balance</h4>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="mb-2">
                                        <select name="customer_id" id="customer_id" class="form-control" required
                                            data-parsley-required-message="Wholesaler is required" autocomplete="off">
                                            <option disabled selected>Select Wholesaler</option>
                                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($customer->id); ?>"
                                                    <?php echo e($customer->id == $accountInfo->customer_id ? 'selected' : ''); ?>>
                                                    <?php echo e($customer->name); ?> -
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
                                            data-parsley-required-message="Total Amount is required" autocomplete="off"
                                            value="<?php echo e($accountInfo->total_amount); ?>">
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="mb-3">
                                        <input type="text" autocomplete="off" id="date" name="date"
                                            class="form-control date_picker" required
                                            data-parsley-required-message="Date is required" placeholder="Enter Your Date" value="<?php echo e($accountInfo->date); ?>">
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                        Update Balance
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/wholesaler/opening_balance/edit_opening.blade.php ENDPATH**/ ?>