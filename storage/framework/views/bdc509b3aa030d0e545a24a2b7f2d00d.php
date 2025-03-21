
<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"> <span class="text-capitalize"><?php echo e($report_head); ?> Report</span> </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0 align-items-center">
                                <li>
                                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light">
                                        <i class="fa fa-print"></i>
                                    </a>
                                </li>
                                <li style="margin-left: 15px;">
                                    <a href="<?php echo e(URL()->previous()); ?>" class="btn btn-info waves-effect waves-light">
                                        <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Go Back
                                    </a>
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 mt-4">
                                            <h3 class="text-muted text-center mb-2"><span
                                                    class="text-capitalize"><?php echo e($report_head); ?> </span> Report from
                                                <?php echo e(date('d-m-Y', strtotime($sdate))); ?> to
                                                <?php echo e(date('d-m-Y', strtotime($edate))); ?>

                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <div class="">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td><strong>Sl.</strong></td>
                                                            <td class="text-center">
                                                                <strong>
                                                                    <?php if($report_head == 'sales'): ?>
                                                                        Customer Name
                                                                    <?php else: ?>
                                                                        Supplier Name
                                                                    <?php endif; ?>
                                                                </strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <strong>
                                                                    <?php if($report_head == 'sales'): ?>
                                                                        Customer Phone
                                                                    <?php else: ?>
                                                                        Supplier Phone
                                                                    <?php endif; ?>
                                                                </strong>
                                                            </td>
                                                            <td class="text-center text-capitalize">
                                                                <strong>

                                                                    <?php if($report_head == 'purchase'): ?>
                                                                        Purchase No
                                                                    <?php else: ?>
                                                                        Invoice No
                                                                    <?php endif; ?>
                                                                </strong>
                                                            </td>
                                                            <td class="text-center"><strong>Date</strong></td>
                                                            <td class="text-center"><strong>Amount</strong></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                        <?php
                                                            $total_amount = '0';
                                                        ?>
                                                        <?php $__currentLoopData = $allData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($report_head == 'purchase'): ?>
                                                                <tr>
                                                                    <td><?php echo e($key + 1); ?></td>
                                                                    <td class="text-center">
                                                                        <?php echo e($item->supplier->name); ?>

                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo e($item->supplier->mobile_no); ?>

                                                                    </td>
                                                                    <td class="text-center">#<?php echo e($item->purchase_no); ?>

                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo e(date('Y-m-d', strtotime($item->date))); ?>

                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo e(number_format($item->total_amount)); ?>/-
                                                                    </td>
                                                                </tr>

                                                                <?php
                                                                    $total_amount += $item->total_amount;
                                                                ?>
                                                            <?php elseif($report_head == 'sales'): ?>
                                                                <tr>
                                                                    <td><?php echo e($key + 1); ?></td>
                                                                    <td class="text-center">
                                                                        <?php echo e($item['payment']['customer']['name']); ?>

                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo e($item['payment']['customer']['phone']); ?>

                                                                    </td>
                                                                    <td class="text-center">#<?php echo e($item->invoice_no); ?>

                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo e(date('Y-m-d', strtotime($item->date))); ?>

                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo e(number_format($item['payment']['total_amount'])); ?>/-
                                                                    </td>
                                                                </tr>

                                                                <?php
                                                                    $total_amount += $item['payment']['total_amount'];
                                                                ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                    <h4 class="mb-4 text-center text-muted fw-bold text-capitalize">Total
                                                        <?php echo e($report_head); ?>: <?php echo e(number_format($total_amount)); ?>/- </h4>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div> <!-- end row -->


                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/pdf/daily_invoice_report_pdf.blade.php ENDPATH**/ ?>