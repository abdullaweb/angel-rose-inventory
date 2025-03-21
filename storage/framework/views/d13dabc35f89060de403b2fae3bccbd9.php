<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" class="custom-validation" action="<?php echo e(route('store.expense')); ?>" novalidate="">
                            <?php echo csrf_field(); ?>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Expense Purpose</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <select name="expense_head" id="expense_head" class="form-control" required
                                        data-parsley-required-message="Expense Purpose is required." aria-readonly="true">
                                        <option value="">Select Expense Purpose</option>
                                        <option value="House-Rent">House Rent</option>
                                        <option value="Salary">Salary</option>
                                        <option value="Utility">Utility Bill</option>
                                        <option value="Snacks">Snacks</option>
                                        <option value="Other">Others</option>
                                    </select>
                                    <input style="display: none;" type="text" name="others"
                                        placeholder="Enter Your Purpose" class="form-control others">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Expense Description</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" name="description" id="description" class="form-control"
                                        placeholder="Description">
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Amount</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <input type="text" name="amount" class="form-control" id="amount"
                                        placeholder="Enter Amount" required
                                        data-parsley-error-message="Amount is required" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Date</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <input type="text" name="date" class="form-control date_picker" id="date"
                                        placeholder="Enter Date" required
                                        data-parsley-error-message="Date is required" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 col-lg-9 text-secondary">
                                    <input type="submit" class="btn btn-primary px-4" value="Add Expense" />
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

            $(document).on('change', '#expense_head', function() {
                let expense_head = $(this).val();
                if (expense_head == 'Other') {
                    $('.others').show();
                } else {
                    $('.others').hide();
                }
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/accounts/expense_page/add_expense.blade.php ENDPATH**/ ?>