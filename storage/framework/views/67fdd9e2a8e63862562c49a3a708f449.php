<?php $__env->startSection('admin'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Begin Page Content -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-muted">Pay Salary</h4>
                            <form class="custom-validation" action="<?php echo e(route('pay.salary.store')); ?>" method="POST"
                                novalidate="" id="form" autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <div class="mb-2">
                                            <select name="employee_id" id="employee_id" class="form-control" required
                                                data-parsley-required-message="Employee ID  is required">
                                                <option value="" selected disabled>Select Employee </option>
                                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="mb-2">
                                            <label for="" class="mb-2 mt-2"><b>Total Due Salary</b></label>
                                            <input type="text" name="total_salary" id="total_salary" class="form-control"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <div class="mb-2">
                                            <label for="" class="mb-2 mt-2"><b>Paid Amount</b></label>
                                            <input type="text" id="paid_amount" name="paid_amount" class="form-control"
                                                required="" data-parsley-trigger="keyup"
                                                data-parsley-validation-threshold="0" placeholder="Paid Amount"
                                                data-parsley-type="number"
                                                data-parsley-type-message="Input must be positive number"
                                                data-parsley-required-message="Paid Amount is required">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <div class="mb-3">
                                            <label for="" class="mb-2 mt-2"><b>Note</b></label>
                                            <input type="text" id="note" name="note" class="form-control"
                                                required data-parsley-required-message="Note is required"
                                                placeholder="Note">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="mb-3">
                                            <label for="" class="mb-2 mt-2"><b>Paid Date</b></label>
                                            <input type="text" id="date" name="date"
                                                class="form-control date_picker" required
                                                data-parsley-required-message="Paid Date is required"
                                                placeholder="Paid Date">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                        Pay Now
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('#form').parsley();


            // course dropdown
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#employee_id').on('change', function() {
                let employeeId = this.value;
                console.log(employeeId);
                $.ajax({
                    url: '<?php echo e(route('get.employee.salary')); ?>?employee_id=' + employeeId,
                    type: 'get',
                    success: function(res) {
                        $("#total_salary").val(res);
                        console.log('res', res);
                    }
                });
            }); //end couse dropdown
        });
    </script>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/salary/pay_salary/add_salary.blade.php ENDPATH**/ ?>