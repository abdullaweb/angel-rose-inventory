<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="text-muted">Update Company</h2>
                            <form method="POST" class="custom-validation" action="<?php echo e(route('update.customer')); ?>"
                                novalidate="">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" value="<?php echo e($customerInfo->id); ?>" name="id">
                                <input type="hidden" value="<?php echo e($customerInfo->status); ?>" name="customer_type">
                                <div class="mb-3 mt-3">
                                    <input type="text" id="name" name="name" class="form-control" required=""
                                        placeholder="Company Name" value="<?php echo e($customerInfo->name); ?>"
                                        data-parsley-required-message="Company Name is required">
                                </div>

                                <div class="mb-3">
                                    <div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            required="" parsley-type="email" placeholder="Enter a valid email"
                                            data-parsley-required-message="Email is required."
                                            value="<?php echo e($customerInfo->email); ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            required="" placeholder="Phone Number"
                                            data-parsley-required-message="Phone Number is required."
                                            value="<?php echo e($customerInfo->phone); ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div>
                                        <input type="tel" id="telephone" name="telephone" class="form-control"
                                            placeholder="Telephone Number" value="<?php echo e($customerInfo->telephone); ?>">
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <div>
                                        <textarea required="" data-parsley-required-message="Address is required." name="address" id="address"
                                            class="form-control" rows="5" placeholder="Enter Your Copmpnay Address"><?php echo e($customerInfo->address); ?></textarea>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                            Update Company
                                        </button>
                                        <button type="reset" class="btn btn-secondary waves-effect">
                                            Cancel
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/customer_page/edit_customer.blade.php ENDPATH**/ ?>