
<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!-- start page title -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Date Wise Profit Report</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Profit Report</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <form method="POST" action="<?php echo e(route('profit.filter')); ?>">
                <?php echo csrf_field(); ?>
                <div class="errorMsgContainer"></div>
                <div class="input-group mb-3">
                    <input type="date" class="form-control ml-2 date_picker" required name="start_date" id="start_date">
                    <input type="date" class="form-control ml-2 date_picker" required name="end_date" id="end_date">
                    <button class="btn btn-info submit_btn ml-2" type="submit">Search</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-muted text-center">Total Sales Profit:
                            BDT
                            <?php echo e(number_format($totalProfit,2)); ?> </h4>
                    </div>
                    <div class="card-body">
                        <table id="" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Discount Per Unit</th>
                                    <th>Selling Qty</th>
                                    <th>Profit Per Unit</th>
                                    <th>Profit</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Discount Per Unit</th>
                                    <th>Selling Qty</th>
                                    <th>Profit Per Unit</th>
                                    <th>Profit</th>
                                    <th>Date</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $__currentLoopData = $totalSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $sales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td>
                                            <?php echo e($sales->product->name ?? ''); ?>

                                        </td>
                                        <td> BDT
                                            <?php echo e(number_format($sales->unit_price_purchase, 2)); ?></td>
                                        <td> BDT
                                            <?php echo e($sales->unit_price_sales); ?></td>
                                        <td>
                                            <?php if($sales->discount_per_unit != null): ?>
                                                BDT
                                                <?php echo e(number_format($sales->discount_per_unit, 2)); ?>

                                            <?php else: ?>
                                                No Discount
                                            <?php endif; ?>

                                        </td>
                                        <td><?php echo e($sales->selling_qty); ?></td>
                                        <td>
                                            BDT
                                            <?php echo e(number_format($sales->profit_per_unit, 2)); ?>

                                        </td>
                                        <td>
                                            BDT
                                            <?php echo e(number_format($sales->profit, 2)); ?>


                                        </td>
                                        <td><?php echo e(date('d F, Y', strtotime($sales->date))); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <?php echo e($totalSales->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/report/profit_report.blade.php ENDPATH**/ ?>