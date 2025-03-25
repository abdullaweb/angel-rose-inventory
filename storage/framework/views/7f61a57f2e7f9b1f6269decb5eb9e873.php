<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Adjustment Product Stock list</h5>
            <h5 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(route('product.adjustment.add')); ?>" class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"></i>  Add Stock</a>
            </h5>
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
                                        <th>Stock Number</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Stock Number</th>
                                        <th><?php echo e(number_format($purchaseStock->sum('total_qty'))); ?> PCS</th>
                                        <th> <?php echo e(number_format($purchaseStock->sum('total_amount'),2)); ?></th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $__currentLoopData = $purchaseStock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td><?php echo e($item->adjustment_no); ?></td>
                                            <td>
                                                <?php echo e($item->total_qty); ?> PCS
                                            </td>
                                            <td>
                                                <?php echo e(number_format($item->total_amount,2)); ?>

                                            </td>

                                            <td>
                                                <a  href="<?php echo e(route('product.adjustment.edit', $item->id)); ?>" class="btn btn-info">
                                                    <i class="fas fa-edit "></i>
                                                </a>
                                                <a  href="<?php echo e(route('product.adjustment.view', $item->id)); ?>" class="btn btn-info">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                                <a  href="<?php echo e(route('product.adjustment.delete', $item->id)); ?>" id="delete" class="btn btn-danger">
                                                    <i class="fa fa-trash-alt" aria-hidden="true"></i>
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
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel-rose-inventory\resources\views/admin/adjustment/adjustment_stock/adjustment_stock_list.blade.php ENDPATH**/ ?>