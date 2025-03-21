<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-muted"><?php echo e($title); ?></h4>
                            <form class="custom-validation" action="<?php echo e(route('store.bank')); ?>" method="POST" novalidate=""
                                enctype="multipart/form-data" autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" id="name" name="name" class="form-control"
                                                required="" placeholder="Bank Name"
                                                data-parsley-required-message="Bank Name is required">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" id="account_number" name="account_number"
                                                class="form-control <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                required="" placeholder="Bank Account Number"
                                                data-parsley-required-message="Bank Account Number is required">
                                            <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="text-danger"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" name="branch_name" class="form-control"
                                                placeholder="Enter Branch Name" required
                                                data-parsley-required-message="Branch Name is required">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="digit" name="opening_balance" id="opening_balance"
                                                class="form-control" placeholder="Opening Balance">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-info waves-effect waves-light me-1">
                                            Add Bank
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


    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // image on load
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/accounts/bank/add_bank.blade.php ENDPATH**/ ?>