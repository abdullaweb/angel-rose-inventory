<?php $__env->startSection('admin'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">All Purchase</h6>
                        <h6 class="m-0 font-weight-bold text-primary">
                            <a href="<?php echo e(route('add.purchase')); ?>">
                                <button class="btn btn-info">Add Purchase</button>
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Purchase No</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Date</th>
                                <th>Due Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Purchase No</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $__currentLoopData = $allPurchase; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td class="text-capitalize">
                                        <?php echo e($item->purchase_no); ?>

                                    </td>
                                    <td class="text-capitalize">
                                        <?php echo e($item->total_amount); ?>

                                    </td>
                                    <td class="text-capitalize">
                                        <?php echo e($item->paid_amount); ?>

                                    </td>
                                    <td class="text-capitalize">
                                        <?php echo e(date('d-m-Y', strtotime($item->date))); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->due_amount); ?>

                                    </td>
                                    <td style="display:flex">

                                        <?php if($item->due_amount != 0): ?>
                                            <a href="<?php echo e(route('purchase.due.payment', $item->id)); ?>" class="btn btn-info">
                                                <i class="fas fa-edit"></i> Due Payment
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('edit.purchase', $item->id)); ?>" class="btn btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo e(route('delete.purchase', $item->id)); ?>" class="btn btn-danger"
                                            id="delete" title="Purchase Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <a href="<?php echo e(route('view.purchase', $item->id)); ?>" class="btn btn-dark"
                                            title="View Details">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="<?php echo e(route('print.purchase', $item->id)); ?>" class="btn btn-success"
                                            title="Print Purcahse">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- End Page Content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel-rose-inventory\resources\views/admin/purchase_page/all_purchase.blade.php ENDPATH**/ ?>