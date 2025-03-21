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
                                        <th>Purchase Stock</th>
                                        <th>Selling Stock</th>
                                        <th>Current Stock</th>
                                        <th>Product Stock</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Purchase Stock</th>
                                        <th>Selling Stock</th>
                                        <th>Current Stock</th>
                                        <th>Product Stock</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                        $total = 0;
                                    ?>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td>
                                                <span><?php echo e($item->name); ?></span>
                                                <span> <strong><?php echo e($item->id); ?></strong> </span>
                                            </td>
                                            <?php
                                                $salesStock = App\Models\InvoiceDetail::where('product_id', $item->id)->get();
                                                $purchaseStock = App\Models\PurchaseMeta::where('product_id', $item->id)->get();
                                                $currentStock = App\Models\PurchaseStore::where('product_id', $item->id)->get();
                                            ?>

                                            <td>
                                                <strong>
                                                    <?php echo e($purchaseStock->sum('quantity')); ?>

                                                </strong>
                                            </td>

                                            <td>
                                                <strong>
                                                    <?php echo e($salesStock->sum('selling_qty')); ?>

                                                </strong>
                                            </td>

                                            <td>
                                                <strong>
                                                    <?php echo e($currentStock->sum('quantity')); ?>

                                                </strong>
                                            </td>
                                            <td>
                                                <strong>
                                                    <?php echo e($item->quantity); ?>

                                                </strong>
                                            </td>
                                            <td>
                                                <strong>
                                                    <?php echo e($salesStock->sum('selling_qty') + $currentStock->sum('quantity')); ?>

                                                </strong>
                                            </td>
                                            <td>

                                            </td>


                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <h4 class="text-muted text-center   ">Total Purchase Amount:
                                        
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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/product/product_sales.blade.php ENDPATH**/ ?>