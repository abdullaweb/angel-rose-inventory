<?php $__env->startSection('admin'); ?>
    <style>
        .table>:not(caption)>*>* {
            padding: 5px !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="company-details mt-5">
                                    
                                    
                                </div>
                            </div>
                            <div class="col-md-12 py-4">
                                <div class="row">
                                    <form method="POST" action="<?php echo e(route('get.customer.account.detail')); ?>"
                                        id="searchEarning" autocomplete="off">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="customer_id" id="customer_id"
                                            value="<?php echo e($customerInfo->id); ?>">
                                        <div class="errorMsgContainer"></div>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control ml-2 date_picker" name="start_date"
                                                id="start_date" placeholder="Enter Start Date">
                                            <input type="text" class="form-control ml-2 date_picker" name="end_date"
                                                id="end_date" placeholder="Enter End Date">
                                            <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12">
                                <h4 class="text-center">Account Details</h4>
                                <div class="payment-details">
                                    
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                                    <h6 class="fw-bold">Invoice</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Status</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Total Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Payment Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Due Amount</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fw-bold">Paid By</h6>
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $total_sum = '0';
                                            ?>
                                            <?php $__currentLoopData = $accountDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($key + 1); ?></td>
                                                    <td><?php echo e(date('d-m-Y', strtotime($details->date))); ?></td>

                                                    <td>
                                                        <?php if($details->invoice_id != null): ?>
                                                            <a target="_blank"
                                                                href="<?php echo e(route('invoice.view', $details->invoice_id)); ?>">
                                                                <?php echo e($details->account_details->invoice_no); ?></a>
                                                        <?php else: ?>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td>
                                                        <?php if($details->status == '1'): ?>
                                                            Invoice
                                                        <?php elseif($details->status == '0'): ?>
                                                            Due Payment
                                                        <?php elseif($details->status == '2'): ?>
                                                            Opening Balance
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($details->total_amount != null): ?>
                                                            <?php echo e($details->total_amount); ?>

                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($details->paid_amount); ?></td>
                                                    <td><?php echo e($details->due_amount); ?></td>
                                                    <td>
                                                        <span class="badge bg-info"><?php echo e($details->paid_source); ?></span>
                                                    </td>
                                                    
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/customer_page/customer_account_details.blade.php ENDPATH**/ ?>