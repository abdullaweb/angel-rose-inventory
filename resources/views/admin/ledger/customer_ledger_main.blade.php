<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Ledger</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #mainWraper {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Supplier Ledger</h2>
        <div class="mb-3">
            <label for="supplier">Select Supplier:</label>
            <select id="supplier" class="form-select">
                <option value="">-- Select Supplier --</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <button id="fetchLedger" class="btn btn-primary">Fetch Ledger</button>
        <button id="downloadLedger" class="btn btn-success">Download Ledger</button>
        <button id="downloadLedgerExcel" class="btn btn-info">Download Excel</button>

        <div class="mt-4" id="mainWraper">
            <h4>Ledger Details</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Date</th>
                        <th>Particular</th>
                        <th>Paid Amount</th>
                        <th>Product Amount</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody id="ledgerTableBody">
                    <!-- Ledger details will be inserted here by AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#fetchLedger').on('click', function() {
                let supplierId = $('#supplier').val();
                if (supplierId) {
                    $.ajax({
                        url: '{{ route('supplier.ledger.fetch') }}',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            supplier_id: supplierId
                        },
                        success: function(response) {

                            // Show the result table and insert data
                            let resultTable = document.getElementById('mainWraper');

                            let ledgerTableBody = $('#ledgerTableBody');
                            ledgerTableBody.empty();
                            let serial = 1;
                            response.ledger.forEach(function(ledger) {
                                console.log(ledger);
                                let transactionDetails = '';
                                ledgerTableBody.append(`
                                    <tr>
                                        <td>${serial++}</td>
                                        <td>${ledger.transaction.date}</td>
                                        <td>${ledger.particular}</td>
                                        <td>${ledger.debit_amount}</td>
                                        <td>${ledger.credit_amount}</td>
                                        <td>${ledger.balance}</td>
                                    </tr>
                                `);

                                resultTable.style.display = 'block';
                            });
                        }
                    });
                }
            });

            $('#downloadLedger').on('click', function() {
                let supplierId = $('#supplier').val();
                if (supplierId) {
                    $.ajax({
                        url: '{{ route('supplier.ledger.download') }}',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            supplier_id: supplierId
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(blob) {
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = 'supplier_ledger.pdf';
                            link.click();
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#downloadLedger').on('click', function() {
                let supplierId = $('#supplier').val();
                if (supplierId) {
                    $.ajax({
                        url: '{{ route('supplier.ledger.download') }}',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            supplier_id: supplierId
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(blob) {
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = 'supplier_ledger.pdf';
                            link.click();
                        }
                    });
                }
            });

            $('#downloadLedgerExcel').on('click', function() {
                let supplierId = $('#supplier').val();
                if (supplierId) {
                    $.ajax({
                        url: '{{ route('supplier.ledger.download.excel') }}',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            supplier_id: supplierId
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(blob) {
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = 'supplier_ledger.xlsx';
                            link.click();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
