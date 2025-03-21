<?php $__env->startSection('admin'); ?>
    <div class="page-content">
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Sales & Purchase Report</h6>
                    <h6 class="m-0 font-weight-bold text-primary">
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <form method="GET" action="<?php echo e(route('daily.invoice.pdf')); ?>" target="_blank"
                            class="custom-validation" novalidate="">
                            <div class="row">
                                <div class="col-md-3 form-group ">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control ml-2 date_picker" name="start_date"
                                        id="start_date" required data-parsley-required-message="Please select start Date">

                                </div>
                                <div class="col-md-3 form-group ">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control ml-2 date_picker" name="end_date"
                                        id="end_date" required data-parsley-required-message="Please select end Date">

                                </div>
                                <div class="col-md-3 form-group ">
                                    <label for="end_date">Report Head</label>
                                    <select name="report_head" id="report_head" class="form-control">
                                        <option value="" disabled selected>Select Report Head</option>
                                        <option value="purchase">Purchase</option>
                                        <option value="sales">Sales</option>
                                    </select>

                                </div>
                                <div class="col-md-3 form-group " style="padding-top:28px;">
                                    <button class="btn btn-primary submit_btn ml-2" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/invoice/daily_invoice_report.blade.php ENDPATH**/ ?>