<?php
    // $expenseAmount = App\Models\Expense::all();
    $total = $allExpense->sum('amount');
?>

<?php $__env->startSection('admin'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row mb-4">
                    <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">All Expense</h6>
                        <h6 class="m-0 font-weight-bold text-primary">
                            <a href="<?php echo e(route('add.expense')); ?>">
                                <button class="btn btn-info">Add Expense</button>
                            </a>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <form method="POST" action="<?php echo e(route('get.expense')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="errorMsgContainer"></div>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control ml-2 date_picker" name="start_date" id="start_date">
                            <input type="date" class="form-control ml-2 date_picker" name="end_date" id="end_date">
                            <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                        </div>
                    </form>
                </div>

            </div>
            <div class="card-body">
                <h4 class="text-center text-muted">Total: ৳ <?php echo e(number_format($allExpense->sum('amount',2))); ?> /=</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" id="example" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Expense Head</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
								<th>Sl</th>
								<th>Expense Head</th>
								<th>Description</th>
								<th>Amount</th>
								<th>Date</th>
								<th>Action</th>
							</tr>
                        </tfoot>
                        <tbody>
                            <?php $__currentLoopData = $allExpense; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td class="text-capitalize">
                                        <?php echo e($item->head); ?>

                                    </td>
                                    <td class="text-capitalize">
                                        <?php echo e($item->description); ?>

                                    </td>
                                    <td>
                                        ৳ <?php echo e(number_format($item->amount,2)); ?>

                                    </td>
                                    <td>
                                        <?php echo e(date('d-M-y', strtotime($item->date))); ?>

                                    </td>
                                    <td style="display:flex">
                                        <a href="<?php echo e(route('edit.expense', $item->id)); ?>" class="btn btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a title="Expense Delete" id="delete"
                                            href="<?php echo e(route('delete.expense', $item->id)); ?>" style="margin-left: 5px;"
                                            class="btn btn-danger">
                                            <i class="fas fa-trash-alt    "></i>
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

    <!-- End Page Content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/accounts/expense_page/all_expense.blade.php ENDPATH**/ ?>