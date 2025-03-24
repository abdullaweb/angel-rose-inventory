<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form  class="custom-validation"
                            novalidate="" autocomplete="off">
                            <div class="row py-2 align-items-center">
                                <div class="col-md-6">
                                    <h4 class="text-muted mb-0">Product Stock View</h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-muted mb-0 text-end">
                                        <a href="<?php echo e(URL::previous()); ?>" class="btn btn-dark"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                                    </h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3 table-responsive">
                                    <table class="table border table-responsive table-striped">
                                        <thead class="bg-body">
                                            <tr>
                                                <th class="text-center">Category</th>
                                                <th class="text-center" width="30%">Product</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Unit Price</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            <?php $__currentLoopData = $purchaseStore; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                            $purchaseMeta = App\Models\PurchaseMeta::where('purchase_id', $item->purchase_id)
                                                ->first();
                                             ?>
                                                <tr class="tr">
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="<?php echo e($purchaseMeta->category->name ?? ''); ?>"readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="<?php echo e($item->product->name ?? ''); ?>" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="<?php echo e($item->quantity); ?>" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="<?php echo e($item->unit_price); ?>" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control" type="text" value="<?php echo e($item->quantity *$item->unit_price); ?>" readonly>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel-rose-inventory\resources\views/admin/adjustment/adjustment_stock/view_adjustment_stock.blade.php ENDPATH**/ ?>