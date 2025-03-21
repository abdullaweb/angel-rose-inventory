<?php $__env->startSection('admin'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Begin Page Content -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Salary Sheet</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-light"><i
                                    class="bx bx-home-alt text-light"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Salary Sheet</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>



        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card">
                    <?php
                        $date = Carbon\Carbon::now()->format('Y');
                    ?>
                    <h4 class="text-center my-3">Salary Sheet - <?php echo e($date); ?> </h4>
                    <div class="card-body">
                        <div class="employee-info mb-5">
                            <h5 class="text-muted">Employee Name: <?php echo e($employee->name); ?></h5>
                            <h6 class="text-muted">Designation: <?php echo e($employee->designation); ?></h6>
                            <p class="text-muted mb-0">Joining Date:
                                <?php echo e(date('d-M, Y', strtotime($employee->joining_date))); ?>

                            </p>
                            <p class="text-muted mb-0"><strong>Basic Salary: <?php echo e($employee->salary); ?></strong></p>
                            <p class="text-muted mb-0">Phone: <?php echo e($employee->phone); ?></p>
                            <?php
                                $advanced_amount = App\Models\Advanced::where('employee_id', $employee->id)->first();
                            ?>
                        </div>

                        <?php $__currentLoopData = $payment_salary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <table id="datatable" class="table table-bordered dt-responsive nowrap mt-5"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <thead>
                                        <tr class="text-center">
                                            <th colspan="12">
                                                <h4>Month Of
                                                    <strong style="font-weight: 400;"><?php echo e($item->paid_month); ?>,
                                                        <?php echo e($item->paid_year); ?></strong>
                                                </h4>
                                            </th>
                                        </tr>
                                        <tr class="text-center">
                                            <th>Sl</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Salary</th>
                                            <th>OT Hour</th>
                                            <th>OT Amount</th>
                                            <th>Bonus</th>
                                            <th>Payment Date</th>
                                            <th>Note</th>
                                            <th>Paid Amount</th>
                                            <th>Due Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    <?php
                                        $paymentDetails = App\Models\PaySalaryDetail::where('employee_id', $employee->id)
                                            ->where('paid_month', $item->paid_month)
                                            ->where('paid_year', date('Y'))
                                            ->get();
                                    ?>
                                    <tr class="text-center">
                                        <td>1</td>
                                        <td style="vertical-align: middle;" rowspan="<?php echo e(count($paymentDetails) + 1); ?>">
                                            <?php echo e($item->paid_month); ?></td>
                                        <td style="vertical-align: middle;" rowspan="<?php echo e(count($paymentDetails) + 1); ?>">
                                            <?php echo e(date('Y')); ?></td>
                                        <td style="vertical-align: middle;" rowspan="<?php echo e(count($paymentDetails) + 1); ?>">
                                            <?php echo e($employee->salary); ?></td>
                                        <?php
                                            $overtimes = App\Models\Overtime::where('employee_id', $employee->id)
                                                ->where('month', $item->paid_month)
                                                ->where('year', date('Y'))
                                                ->first();
                                            $bonus = App\Models\Bonus::where('employee_id', $employee->id)
                                                ->where('month', $item->paid_month)
                                                ->where('year', date('Y'))
                                                ->first();
                                            $total = '0';
                                            $paid_total = '0';
                                            $total += $employee->salary;
                                        ?>

                                        <!-- overtime -->
                                        <?php if($overtimes == null): ?>
                                            <td>0</td>
                                            <td>0</td>
                                            <?php
                                                $total += 0;
                                            ?>
                                        <?php else: ?>
                                            <td><?php echo e($overtimes->ot_hour); ?></td>
                                            <td><?php echo e(number_format($overtimes->ot_amount)); ?>/-</td>
                                            <?php
                                                $total += $overtimes->ot_amount;
                                            ?>
                                        <?php endif; ?>

                                        <!-- bonus -->
                                        <?php if($bonus == null): ?>
                                            <td>0</td>
                                            <?php
                                                $total += 0;
                                            ?>
                                        <?php else: ?>
                                            <td><?php echo e(number_format($bonus->bonus_amount)); ?>/-</td>
                                            <?php
                                                $total += $bonus->bonus_amount;
                                            ?>
                                        <?php endif; ?>

                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td><?php echo e(number_format($total)); ?>/-</td>
                                    </tr>

                                    <?php $__currentLoopData = $paymentDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="text-center">
                                            <td><?php echo e($key + 2); ?></td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td><?php echo e($details->paid_date); ?></td>
                                            <td><?php echo e($details->note); ?></td>
                                            <td><?php echo e(number_format($details->paid_amount)); ?>/-</td>

                                            <td>
                                                <?php if($details->paid_type == 'Advanced'): ?>
                                                    
                                                    <?php echo e($total - $paid_total); ?>

                                                <?php else: ?>
                                                    <?php
                                                        $paid_total += $details->paid_amount;
                                                    ?>
                                                    <?php echo e(number_format($total - $paid_total)); ?>/-
                                            </td>
                                    <?php endif; ?>

                                    </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>

                        </table>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                    </div>
                </div>
            </div>


        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/admin/salary/pay_salary/payment_details.blade.php ENDPATH**/ ?>