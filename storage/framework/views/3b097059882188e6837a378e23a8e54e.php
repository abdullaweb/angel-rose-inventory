<style>
    .page-link svg,
    .page-link i,
    .page-link::before {
        width: 1em !important;
        height: 1em !important;
        font-size: 1em !important;
        vertical-align: middle;
    }
</style>
<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!-- breadcrumb -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Product Stock</h6>
        </div>
        <!-- end breadcrumb -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('product-stock-table', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-687941882-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\angelrose-software\resources\views/admin/stock/stock_all.blade.php ENDPATH**/ ?>