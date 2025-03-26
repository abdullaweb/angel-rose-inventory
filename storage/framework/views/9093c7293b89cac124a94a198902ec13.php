<?php $__env->startSection('admin'); ?>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All <?php echo e($title); ?></h6>
            <h6 class="m-0 font-weight-bold text-primary">
                <a href="<?php echo e(route('add.customer')); ?>">
                    <button class="btn btn-info">Add <?php echo e($title); ?></button>
                </a>
            </h6>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Info</th>
                                    <th>Total Order</th>
                                    <th>Due Amount</th>
                                    <th>Customer Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Info</th>
                                    <th>Total Order</th>
                                    <th>Due Amount</th>
                                    <th>Customer Type</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $__currentLoopData = $allData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td>
                                            <?php echo e($item->name); ?>

                                        </td>
                                        <td>
                                            <p class="mb-0"><?php echo e($item->phone); ?></p>
                                            <p class="mb-0"><?php echo e($item->email); ?></p>
                                            <p class="mb-0"><?php echo e($item->address); ?></p>
                                        </td>
                                         <?php
                                                $payment = App\Models\Payment::where('customer_id', $item->id)->get();
                                                $due_amount = $payment->get('due_amount');
                                                $opening_due = App\Models\AccountDetail::where('customer_id', $item->id)
                                                    ->where('status', '2')
                                                    ->sum('due_amount');
                                            ?>
                                        <td>
                                          <span class="badge bg-info">
                                              <?php echo e($payment->count()); ?>

                                            </span> 
                                        </td>
                                        <td>
                                           
                                            <?php echo e($due_amount + $opening_due); ?>

                                        </td>
                                        <td>
                                            <?php if($item->status == '1'): ?>
                                                <span class="badge bg-info">Wholesaler</span>
                                            <?php elseif($item->status == '0'): ?>
                                                <span class="badge bg-info">Retaile</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a title="Edit Customer" style="margin-left: 5px;"
                                                href="<?php echo e(route('edit.customer', $item->id)); ?>" class="btn btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a style="margin-left: 5px;" href="<?php echo e(route('customer.bill', $item->id)); ?>"
                                                class="btn btn-dark" title="Customer Bill">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                View Bill
                                            </a>
                                            <a style="margin-left: 5px;"
                                                href="<?php echo e(route('customer.account.details', $item->id)); ?>"
                                                class="btn btn-success text-white" title="Payment Detaits">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                Payment Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>

                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="<?php echo e(asset('backend/assets/js/code.js')); ?>"></script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/customer_page/all_customer.blade.php ENDPATH**/ ?>