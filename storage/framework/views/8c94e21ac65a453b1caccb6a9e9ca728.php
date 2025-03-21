<?php $__env->startSection('admin'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Begin Page Content -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Bonus</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(route('add.bonus')); ?>">
                    <button class="btn btn-info">Add Bonus</button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Employee Name</th>
                                <th>Bonus Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Employee Name</th>
                                <th>Bonus Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $__currentLoopData = $allBonus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $bonus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td class="text-capitalize">
                                        <?php echo e($bonus['employee']['name']); ?>

                                    </td>
                                    <td class="text-capitalize">
                                        <?php echo e($bonus->bonus_amount); ?>

                                    </td>
                                    <td class="text-capitalize">
                                        <?php echo e($bonus->month); ?>, <?php echo e($bonus->year); ?>

                                    </td>
                                    <td style="display:flex">
                                        <a title="Update Advanced" href="<?php echo e(route('edit.bonus', $bonus->id)); ?>"
                                            class="btn btn-dark text-light">
                                            <i class="fas fa-edit    "></i>
                                        </a>
                                        <a id="delete" href="<?php echo e(route('delete.bonus', $bonus->id)); ?>"
                                            class="ml-2 btn btn-danger" id="delete" title="Overtime Delete">
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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/salary/bonus/all_bonus.blade.php ENDPATH**/ ?>