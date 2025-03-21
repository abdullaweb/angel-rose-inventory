<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Purcahse All</h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(route('add.purchase')); ?>">
                    <button class="btn btn-info"><i class="fa fa-plus-circle" aria-hidden="true"> Add Purchase </i></button>
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
                                        <th>Supplier Name</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Supplier Name</th>
                                        <th>Purcahse No</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <h3 class="text-center text-danger">Total Due :
                                        <?php echo e(number_format($purchaseData->sum('due_amount'))); ?></h3>
                                    <?php $__currentLoopData = $purchaseData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td>
                                                <?php echo e($purchase->supplier->name); ?>

                                            </td>
                                            <td>
                                                #<?php echo e($purchase->purchase_no); ?>

                                            </td>
                                            <td>
                                                <?php echo e(date('d-m-Y', strtotime($purchase->date))); ?>

                                            </td>
                                            <td>
                                                BDT <?php echo e($purchase->total_amount); ?>

                                            </td>
                                            <td>
                                                BDT <?php echo e($purchase->paid_amount); ?>

                                            </td>
                                            <td>
                                                BDT <?php echo e($purchase->due_amount); ?>

                                            </td>

                                            <td style="display:flex">
                                                <?php if($purchase->due_amount != 0): ?>
                                                    <a title="Paid Supplier Due Bill" style="margin-left: 5px;"
                                                        href="<?php echo e(route('purchase.due.payment', $purchase->id)); ?>"
                                                        class="btn btn-info">
                                                        <i class="fas fa-edit"></i> Due Payment
                                                    </a>
                                                <?php else: ?>
                                                <?php endif; ?>
                                                <a title="Print Purchase" style="margin-left: 5px;"
                                                    href="<?php echo e(route('print.purchase', $purchase->id)); ?>"
                                                    class="btn btn-success">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/supplier/supplier_invoice.blade.php ENDPATH**/ ?>