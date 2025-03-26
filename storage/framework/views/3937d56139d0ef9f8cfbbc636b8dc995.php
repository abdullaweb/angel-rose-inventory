<?php $__env->startSection('admin'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">All Opening</h6>
                        <h6 class="m-0 font-weight-bold text-primary">
                            <a href="<?php echo e(route('add.opening.balance')); ?>">
                                <button class="btn btn-info">Add Balance</button>
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
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Total Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Total Amount</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>

                            <?php $__currentLoopData = $allOpening; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td class="text-capitalize">
                                        <?php echo e($item['customer']['name']); ?>

                                    </td>
                                    <td class="text-capitalize">
                                        <?php echo e($item['customer']['phone']); ?>

                                    </td>
                                    <td class="text-capitalize">
                                        <?php echo e($item->total_amount); ?>

                                    </td>
                                    <td style="display:flex;">
                                        <a title="Edit Balance" href="<?php echo e(route('edit.opening.balance', $item->id)); ?>"
                                            class="btn btn-info text-light">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <a title="Delete Balance" style="margin-left: 5px;"
                                            href="<?php echo e(route('delete.opening.balance', $item->id)); ?>" class="btn btn-danger"
                                            id="delete">
                                            <i class="fas fa-trash-alt"></i>
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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/wholesaler/opening_balance/all_opening.blade.php ENDPATH**/ ?>