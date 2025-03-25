<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Supplier</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(route('supplier.add')); ?>">
                    <button class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"> Add Supplier</i> </button>
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
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $__currentLoopData = $supplierAll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td>
                                                <?php echo e($item->name); ?>

                                            </td>
                                            <td>
                                                <?php echo e($item->mobile_no); ?>

                                            </td>
                                            <td>
                                                <?php echo e($item->email); ?>

                                            </td>
                                            <td>
                                                <?php echo e($item->address); ?>

                                            </td>


                                            <td style="display:flex">
                                                <a style="margin-left: 5px;" href="<?php echo e(route('supplier.edit', $item->id)); ?>"
                                                    class="btn btn-info">
                                                    <i class="fas fa-edit    "></i>
                                                </a>
                                                <a id="delete" style="margin-left: 5px;"
                                                    href="<?php echo e(route('supplier.delete', $item->id)); ?>" class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                <a style="margin-left: 5px;"
                                                    href="<?php echo e(route('supplier.all.purchase', $item->id)); ?>"
                                                    class="btn btn-primary">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    View Purchase
                                                </a>
                                                <a style="margin-left: 5px;"
                                                    href="<?php echo e(route('supplier.account.details', $item->id)); ?>"
                                                    class="btn btn-success text-white" title="Payment Detaits">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    Payment Details
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

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="<?php echo e(asset('backend/assets/js/code.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/supplier/supplier_all.blade.php ENDPATH**/ ?>