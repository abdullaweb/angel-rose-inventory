<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Product</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(route('product.add')); ?>">
                    <button class="btn btn-info"> <i class="fa fa-plus-circle" aria-hidden="true"> Add Product </i></button>
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
                                        <th>Image</th>
                                        <th>Supplier</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Supplier</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $__currentLoopData = $productAll; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td>
                                                <?php echo e($item->name); ?>

                                            </td>
                                            <td>
                                                <img src="<?php echo e(asset('upload/product_images/' . $item->image)); ?>"
                                                    alt="<?php echo e($item->name); ?>" width="60">
                                            </td>

                                            <td>
                                                <?php echo e($item['supplier']['name']); ?>

                                            </td>

                                            <td>
                                                <?php echo e($item['category']['name']); ?>

                                            </td>

                                            <td>
                                                <?php echo e($item['unit']['name']); ?>

                                            </td>
                                            <td>
                                                <strong><?php echo e($item->quantity); ?></strong>
                                            </td>

                                            <td style="display:flex">
                                                <a style="margin-left: 5px;" href="<?php echo e(route('product.edit', $item->id)); ?>"
                                                    class="btn btn-info">
                                                    <i class="fas fa-edit    "></i>
                                                </a>
                                                <a id="delete" style="margin-left: 5px;"
                                                    href="<?php echo e(route('product.delete', $item->id)); ?>" class="btn btn-danger">
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
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="<?php echo e(asset('backend/assets/js/code.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\developer_mode\ar_distribution\resources\views/admin/product/product_all.blade.php ENDPATH**/ ?>