<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Ledger PDF <?php echo e($customer->name); ?></title>
    <style>
        table tr th,
        table tr td {
            padding: 4px;
            font-size: 12px;
        }

        h3 {
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <h3 class="text-center">Customer Ledger for <?php echo e($customer->name); ?></h3>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Date</th>
                <th>Particular</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $ledger; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key + 1); ?></td>
                    <td><?php echo e($entry->date ?? null); ?></td>
                    <td>
                        <?php if($entry->status == '1'): ?>
                           <?php if($entry->paid_source == NULL): ?>
                            <a href="<?php echo e(route('invoice.print', $entry->invoice_id)); ?>">
                                Sales
                            </a>
                           <?php else: ?>
                            <a href="<?php echo e(route('invoice.print', $entry->invoice_id)); ?>">
                                Sales <?php echo e(' - (' . $entry->paid_source . ')'); ?>

                            </a>
                            <?php endif; ?>
                        <?php elseif($entry->status == '0'): ?>
                            Due Payment
                        <?php elseif($entry->status == '2'): ?>
                            Opening Balance
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($entry->total_amount); ?></td>
                    <td><?php echo e($entry->paid_amount ?? '0.00'); ?></td>
                    <td>
                        <?php echo e($entry->balance ?? '0.00'); ?>

                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>

</html>
<?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/ledger/customer_ledger_pdf.blade.php ENDPATH**/ ?>