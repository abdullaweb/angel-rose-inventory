<?php $__env->startSection('admin'); ?>
    <style>
        .table>:not(caption)>*>* {
            padding: 0 !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Customer Account Report</h4>

                        <div class="d-print-none">
                            <div class="float-end">
                                <a class="btn btn-info" href="<?php echo e(url()->previous()); ?>">Go Back</a>
                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i
                                        class="fa fa-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="company-details mt-5">
                                    <?php
                                        $customerInfo = App\Models\Customer::where('id', $customer_id)->first();
                                        $due_amount = $billDetails->sum('total_amount') - $billDetails->sum('paid_amount');
                                    ?>
                                    <h5><strong>Supplier Name : <?php echo e($customerInfo->name); ?></strong></h5>
                                    <p class="mb-0">Address : <?php echo e($customerInfo->address); ?></p>
                                    <p class="mb-0">Phone : <?php echo e($customerInfo->mobile_no); ?></p>
                                    <p class="mb-0">E-mail : <?php echo e($customerInfo->email); ?></p>
                                </div>
                            </div>
                            
                            <div class="col-12 py-3">
                                <h4 class="text-center">Account details from <?php echo e($start_date); ?> to <?php echo e($end_date); ?></h4>

                                <h5 class="text-center">Total Due: <?php echo e($due_amount); ?></h5>
                                <div class="payment-details">
                                    <table class="table table-bordered border-dark text-center text-dark" width="100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6 class="fw-bold">
                                                        Sl. No
                                                    </h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Date</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Particular</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Total Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Paid Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Balance</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($billDetails) > 0): ?>
                                                <?php
                                                    $total_sum = '0';
                                                ?>
                                                <?php $__currentLoopData = $billDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($key + 1); ?></td>
                                                        <td><?php echo e(date('d-m-Y', strtotime($details->created_at))); ?></td>
                                                        <td>
                                                            <?php if($details->total_amount != null): ?>
                                                                <?php echo e($details->total_amount); ?>

                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo e($details->paid_amount); ?></td>
                                                        <td><?php echo e($details->balance); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7"> No data found</td>
                                                </tr>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/report/customer_account_detials_report.blade.php ENDPATH**/ ?>