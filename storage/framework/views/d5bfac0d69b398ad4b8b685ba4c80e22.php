<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Product Stock</h6>
            <h6 class="m-0 font-weight-bold text-primary">
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
                                        <th>Stock</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Stock</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>

                                    <?php
                                        $grandTotal = 0;
                                    ?>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                           
                                            <td><?php echo e($key + 1); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center jusify-content-center">
                                                    <img src=" <?php echo e(!empty($item->image) ? asset('upload/product_images/' . $item->image) : url('upload/no_image.jpg')); ?>"
                                                        alt="<?php echo e($item->name); ?>" width="40"
                                                        class="rounded-circle img-thumbnail">
                                                    <span style="margin-left: 10px;"><?php echo e($item->name); ?></span>
                                                </div>
                                            </td>
                                            <?php
                                                $total = 0;
                                                $productStock = App\Models\PurchaseStore::where('product_id', $item->id)
                                                    ->where('quantity', '!=', '0')
                                                    ->get();
                                            ?>
                                            <td>
                                                <strong>
                                                     <!--<?php echo e($item->quantity); ?> -->
                                                    <?php echo e($productStock->sum('quantity')); ?>

                                                    <?php echo e($item['unit']['short_form']); ?>

                                                </strong>
                                            </td>
                                            <td>
                                                <?php $__currentLoopData = $productStock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $total += $stock->quantity * $stock->unit_price;
                                                    ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(number_format($total)); ?>/-
                                                <?php
                                                    $grandTotal += $total;
                                                ?>
                                            </td>

                                            <td>
                                                <a style="margin-left: 5px;"
                                                    href="<?php echo e(route('purchase.history', $item->id)); ?>" class="btn btn-info">
                                                    <i class="fa fa-eye" aria-hidden="true"></i> Purchase History
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <h4 class="text-muted text-center   ">Total Stock Amount:
                                        <?php echo e(number_format($grandTotal)); ?>/-
                                    </h4>
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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/stock/stock_all.blade.php ENDPATH**/ ?>