<?php $__env->startSection('admin'); ?>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Credit Company</h6>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Compnay ID</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Due Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Compnay ID</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Due Amount</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $__currentLoopData = $allData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td>
                                            <?php echo e($item['customer']['name']); ?>

                                            <?php if($item['customer']['status'] == '1'): ?>
                                                <span class="badge bg-info">Wholesaler</span>
                                            <?php elseif($item['customer']['status'] == '0'): ?>
                                                <span class="badge bg-info">Retaile</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($item['customer']['customer_id']); ?>

                                        </td>
                                        <td>
                                            #<?php echo e($item['invoice']['invoice_no']); ?>

                                        </td>
                                        <td>
                                            <?php echo e(date('Y-m-d', strtotime($item['invoice']['date']))); ?>

                                        </td>
                                        <td>
                                            <?php echo e($item['due_amount']); ?>

                                        </td>

                                        <td>
                                            <div class="d-flex">
                                                <a style="margin-left: 5px;"
                                                    href="<?php echo e(route('edit.credit.customer', $item->invoice_id)); ?>"
                                                    class="btn btn-info">
                                                    <i class="fas fa-edit"></i> Due Payment
                                                </a>
                                                <a style="margin-left: 5px;"
                                                    href="<?php echo e(route('customer.invoice.details', $item->invoice_id)); ?>"
                                                    class="btn btn-secondary" title="Customer Invoice Details">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>

                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="<?php echo e(asset('backend/assets/js/code.js')); ?>"></script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/customer_page/credit_customer.blade.php ENDPATH**/ ?>