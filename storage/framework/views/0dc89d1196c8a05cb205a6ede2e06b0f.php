<?php $__env->startSection('admin'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Begin Page Content -->
    <div class="page-content">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-12 py-3 d-flex justify-content-center align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary text-center">Category Wise Sale Report Summary</h6>
                        <h6 class="m-0 font-weight-bold text-primary">
                        </h6>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="<?php echo e(route('get.cat.report.summary.print')); ?>" method="POST" autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <div class="input-group mb-3">
                        <input type="text" placeholder="Start Date" class="form-control ml-2 date_picker"
                            name="start_date" id="start_date" required>
                        <input type="text" placeholder="End Date" class="form-control ml-2 date_picker" name="end_date"
                            id="end_date" required>
                        <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th>Category</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="text-center">
                                <th>Category</th>
                                <th>Amount</th>
                            </tr>
                        </tfoot>
                        <tbody>

                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="text-center">
                                    <td> <?php echo e($category->name); ?> </td>
                                    <?php
                                        $sellingAmount = App\Models\InvoiceDetail::where('category_id', $category->id)->sum('selling_price');
                                    ?>
                                    <td> <?php echo e(number_format($sellingAmount)); ?> </td>
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

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/report/category_wise_report_summary.blade.php ENDPATH**/ ?>