<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Invoice All</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(route('invoice.add')); ?>">
                    <button class="btn btn-info"><i class="fa fa-plus-circle" aria-hidden="true"> Add Invoice </i></button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Customer Name</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Company Name</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $__currentLoopData = $allInvoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td>
                                                <?php echo e($item['payment']['customer']['name'] ?? 'N/A'); ?>

                                                <?php if($item['payment']['customer']['status'] == '1'): ?>
                                                    <span class="badge bg-info">Wholesaler</span>
                                                <?php elseif($item['payment']['customer']['status'] == '0'): ?>
                                                    <span class="badge bg-info">Retail</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                #<?php echo e($item->invoice_no); ?>

                                            </td>
                                            <td>
                                                <?php echo e(date('d-m-Y', strtotime($item->date))); ?>

                                            </td>
                                            <td>
                                                BDT <?php echo e($item['payment']['total_amount']); ?>

                                            </td>
                                            <td>
                                                BDT <?php echo e($item['payment']['due_amount']); ?>

                                            </td>

                                            <td>
                                                <a title="Print Invoice" style="margin-left: 5px;"
                                                    href="<?php echo e(route('invoice.print', $item->id)); ?>" class="btn btn-success">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                </a>
                                                <a title="Edit Invoice" style="margin-left: 5px;"
                                                   href="<?php echo e(route('invoice.edit', $item->id)); ?>" class="btn btn-info">
                                                   <i class="fas fa-edit"></i>
                                                </a>
                                                <a title="Delete Invoice" style="margin-left: 5px;"
                                                    href="<?php echo e(route('invoice.delete', $item->id)); ?>" class="btn btn-danger" id="delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <?php echo e($allInvoice->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="<?php echo e(asset('backend/assets/js/code.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/invoice/invoice_all.blade.php ENDPATH**/ ?>