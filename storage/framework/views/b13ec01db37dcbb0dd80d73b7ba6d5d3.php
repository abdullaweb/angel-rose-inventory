
<?php $__env->startSection('admin'); ?>
    <style>
        .table>:not(caption)>*>* {
            padding: 5px !important;
        }
    </style>
    <!-- Begin Page Content -->
    <div class="page-content">
        <div class="card-header pb-3  d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Profit Data</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(url()->previous()); ?>">
                    <button class="btn btn-sm btn-dark">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                    </button>
                </a>
                <button onclick="printDiv('printContent')" class="btn btn-sm btn-success waves-effect waves-light">
                    <i class="fa fa-print"></i> Print
                </button>
            </h6>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="printContent">
                    <div class="card-header">
                        <h4 class="text-muted text-center">
                            Sale Profit from
                            <?php echo e(date('d-m-Y', strtotime(Request::post('start_date')))); ?> to
                            <?php echo e(date('d-m-Y', strtotime(Request::post('end_date')))); ?>

                        </h4>
                        <h5 class="text-center text-muted mb-3">Total Profit: <strong>BDT
                                <?php echo e(number_format($totalSales->sum('profit'), 2)); ?></strong> </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered dt-responsive nowrap" style="width: 100%;">
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
                                                <?php echo e($sales->product->name); ?>

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
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Page Content -->

    <script>
        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/report/profit_result.blade.php ENDPATH**/ ?>