<?php $__env->startSection('admin'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-center align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary text-center">Category Wise Daily Sales Report</h6>
                        <h6 class="m-0 font-weight-bold text-primary">

                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('get.cat.report')); ?>" method="POST" autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <div class="input-group mb-3">
                        <input type="text" placeholder="Start Date" class="form-control ml-2 date_picker"
                            name="start_date" id="start_date" required>
                        <input type="text" placeholder="End Date" class="form-control ml-2 date_picker" name="end_date"
                            id="end_date" required>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="" selected disabled>Select Category</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <select name="report_type" id="report_type" class="form-control" required>
                            <option value="" selected disabled>Select Report Head</option>
                            <option value="purchase">Purchase</option>
                            <option value="sales">Sales</option>
                        </select>
                        <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                    </div>
                </form>
            </div>

            

        </div>

    </div>

    <!-- End Page Content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/report/category_wise_report.blade.php ENDPATH**/ ?>