<?php $__env->startSection('admin'); ?>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Supplier Add Page </h4><br><br>


                            <?php if(count($errors)): ?>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <p class="alert alert-danger alert-dismissible fade show"> <?php echo e($error); ?> </p>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>


                            <form method="post" action="<?php echo e(route('supplier.store')); ?>" id="myForm">
                                <?php echo csrf_field(); ?>

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Name</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="name" class="form-control" type="text" id="name">
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Email</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="email" class="form-control" type="email" id="email">
                                    </div>
                                </div>
                                <!-- end row -->


                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Mobile</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="mobile_no" class="form-control" type="tel" id="mobile_no">
                                    </div>
                                </div>
                                <!-- end row -->



                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Address</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="address" class="form-control" type="text" id="address">
                                    </div>
                                </div>
                                <!-- end row -->


                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Add Supplier">
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>



        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#myForm").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    mobile_no: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                },
                message: {
                    name: {
                        required: "Please Enter your name",
                    },
                    email: {
                        required: "Please Enter your email address",
                    },
                    mobile_no: {
                        required: "Please Enter your mobile number",
                    },
                    address: {
                        required: "Please Enter your address",
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-faedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/supplier/supplier_add.blade.php ENDPATH**/ ?>