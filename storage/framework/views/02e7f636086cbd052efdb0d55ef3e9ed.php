<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="text-muted">Add Customer</h2>
                            <form method="POST" class="custom-validation" action="<?php echo e(route('store.customer')); ?>"
                                novalidate="">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3 mt-3">
                                    <input type="text" id="name" name="name" class="form-control" required=""
                                        placeholder="Customer Name"
                                        data-parsley-required-message="Company Name is required">
                                </div>

                                <div class="mb-3">
                                    <div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Enter a valid email">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            required="" placeholder="Phone Number"
                                            data-parsley-required-message="Phone Number is required.">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div>
                                        <input type="tel" id="telephone" name="telephone" class="form-control"
                                            placeholder="Telephone Number">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div>
                                        <select name="customer_type" id="customer_type" class="form-control" required=""
                                            data-parsley-required-message="Customer Type is required.">
                                            <option value="">Select Customer Type</option>
                                            <option value="1">WholeSale</option>
                                            <option value="0">Retailer</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <div>
                                        <textarea required="" data-parsley-required-message="Address is required." name="address" id="address"
                                            class="form-control" rows="5" placeholder="Enter Your Customer Address"></textarea>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                            Add Customer
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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/customer_page/add_customer.blade.php ENDPATH**/ ?>