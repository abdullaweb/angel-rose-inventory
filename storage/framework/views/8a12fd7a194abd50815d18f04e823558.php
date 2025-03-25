<?php $__env->startSection('admin'); ?>
    <style>
        #mainWraper {
            display: none;
        }
    </style>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer">Select Customer:</label>
                            <select id="customer" class="form-select select2">
                                <option value="">-- Select Customer --</option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <button id="fetchLedger" class="btn btn-primary">Fetch Ledger</button>
                        <button id="downloadLedger" class="btn btn-success"> <i class="fa fa-download"
                                aria-hidden="true"></i> Download</button>
                        

                        <div class="mt-4" id="mainWraper">
                            <h4 class="text-center">Ledger Details for <span id="customerName"></span></h4>
                            <table class="table table-bordered">
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
                                <tbody id="ledgerTableBody">
                                    <!-- Ledger details will be inserted here by AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#fetchLedger').on('click', function() {
            let customerId = $('#customer').val();
            clearPreviousResults();
            if (customerId) {
                $.ajax({
                    url: '<?php echo e(route('customer.ledger.fetch')); ?>',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customerId
                    },
                    success: function(response) {

                        let resultTable = document.getElementById('mainWraper');
                        let ledgerTableBody = $('#ledgerTableBody');
                        let customerName = response.customer.name;
                        $('#customerName').text(customerName);
                        let serial = 1;
                        if (response.ledger === undefined) {
                            console.log('no data found!');
                            ledgerTableBody.append(`
                                <tr>
                                    <td colspan="6" class="text-center">No Record Found</td>
                                </tr>
                            `);
                        } else {
                            response.ledger.forEach(function(ledger) {
                                console.log(ledger);
                                ledgerTableBody.append(`
                                <tr>
                                    <td> ${serial++}</td>
                                    <td>${ledger.date}</td>
                                    <td>${ledger.status == 0 ? 'Due Payment' : (ledger.status == 1 ? 'Sales' : 'Opening')}
                                    ${ledger.paid_source ? ' (' + ledger.paid_source + ')' : ''}
                                        </td>
                                    <td>${ledger.total_amount.toFixed(2)}</td>
                                    <td>${ledger.paid_amount.toFixed(2) || '0.00'}</td>
                                    <td>${ledger.balance || '0.00'}</td>
                                </tr>
                            `);
                            });
                        }
                        resultTable.style.display = 'block';
                    }
                });
            }
        });

        function clearPreviousResults() {
            let errorMessageDiv = document.getElementById('ledgerTableBody');
            errorMessageDiv.innerHTML = '';
        }

        $('#downloadLedger').on('click', function() {
            let customerId = $('#customer').val();
            if (customerId) {
                $.ajax({
                    url: '<?php echo e(route('customer.ledger.download')); ?>',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        customer_id: customerId
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(blob) {
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'customer_ledger.pdf';
                        link.click();
                    }
                });
            }
        });
    });
</script>



<?php echo $__env->make('admin.admin_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\angel_rose_inventory\resources\views/admin/ledger/customer_ledger.blade.php ENDPATH**/ ?>