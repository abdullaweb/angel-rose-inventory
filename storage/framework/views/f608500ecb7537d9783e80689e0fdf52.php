<?php $__env->startSection('admin'); ?>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Due</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(route('add.due.payment')); ?>">
                    <button class="btn btn-info">Add Due</button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Paid Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Paid Amount</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $__currentLoopData = $dueAll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td>
                                            <?php echo e($item->company->name); ?>

                                        </td>
                                        <td>
                                            <?php echo e($item->date); ?>

                                        </td>
                                        <td>
                                            <?php echo e($item->paid_amount); ?>

                                        </td>

                                        <td>
                                            
                                                <a style="margin-left: 5px;" href="<?php echo e(route('edit.due.payment', $item->id)); ?>" class="btn btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            

                                            
                                                <a style="margin-left: 5px;" href="<?php echo e(route('delete.due.payment', $item->id)); ?>" class="btn btn-danger" title="Delete" id="delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            

                                            <?php if($item->status == 'pending'): ?>
                                                <a style="margin-left: 5px;" href="<?php echo e(route('due.payment.approval.now', $item->id)); ?>" class="btn btn-success" id="approve" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/due_payment/all_due.blade.php ENDPATH**/ ?>