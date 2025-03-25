<?php $__env->startSection('admin'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-dark"><?php echo e($product->name); ?> Purchase History</h6>
                        <h6 class="m-0 font-weight-bold text-primary">
                            <a href="<?php echo e(url()->previous()); ?>">
                                <button class="btn btn-dark"> <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                    Back</button>
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
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Purchase No</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <h4 class="text-muted text-center   ">Total Purchase Amount:
                                <?php echo e(number_format($purchase->sum('total'))); ?>/-</h4>
                            <?php $__currentLoopData = $purchase; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td class="text-capitalize">
                                        <a href="<?php echo e(route('view.purchase', $item->purchase_id)); ?>">
                                            <?php echo e($item->purchase->purchase_no); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <?php echo e($item->quantity); ?> <?php echo e($item->product->unit->short_form); ?>

                                    </td>
                                    <td>
                                        <?php echo e(number_format($item->unit_price)); ?>

                                    </td>
                                    <td>
                                        <?php echo e(number_format($item->unit_price * $item->quantity)); ?>

                                    </td>
                                    <td class="text-capitalize">
                                        <?php echo e(date('d-M-Y', strtotime($item->purchase->date))); ?>

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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/purchase_page/purchase_history.blade.php ENDPATH**/ ?>