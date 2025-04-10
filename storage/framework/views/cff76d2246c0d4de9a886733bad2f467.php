<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

        <div class="mt-3 d-flex align-items-center">
            <label class="me-2 fw-semibold">Per Page:</label>
            <select wire:model.live="perPage" class="form-select w-auto">
                <option value="all">All</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        <div class="mb-2">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search"
                    aria-label="Search">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
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
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($key + 1); ?></td>
                        <td>
                            <div class="d-flex align-items-center justify-content-start">
                                <img src="<?php echo e(!empty($item->image) ? asset('upload/product_images/' . $item->image) : url('upload/no_image.jpg')); ?>"
                                    alt="<?php echo e($item->name); ?>" width="40" class="rounded-circle img-thumbnail">
                                <span class="ms-2 text-capitalize"><?php echo e($item->name); ?></span>
                            </div>
                        </td>
                        <?php
                            $purchaseStore = App\Models\PurchaseStore::where('product_id', $item->id)
                                ->where('quantity', '>', 0)
                                ->get();
                            $quantity = $purchaseStore->sum('quantity');
                            $value = $purchaseStore->sum('unit_price') * $quantity;
                        ?>
                        <td><strong><?php echo e($quantity); ?> <?php echo e($item['unit']['short_form']); ?></strong></td>
                        <td>৳<?php echo e(number_format($value, 2)); ?></td>
                        <td>
                            <a href="<?php echo e(route('purchase.history', $item->id)); ?>" class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i> Purchase History
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

            </tbody>
        </table>

        <div class="mt-4 text-center">
            <h5 class="text-muted">Total Stock Quantity:
                <strong><?php echo e(number_format($grandQuantity)); ?> PCS</strong>
            </h5>
            <h5 class="text-muted">Total Stock Amount:
                <strong>৳<?php echo e(number_format($grandTotal, 2)); ?></strong>
            </h5>
        </div>

    </div>

    <div class="mt-4">
        <?php echo e($products->links('livewire::bootstrap')); ?>

    </div>
</div>
<?php /**PATH D:\laragon\www\angelrose-software\resources\views/livewire/product-stock-table.blade.php ENDPATH**/ ?>