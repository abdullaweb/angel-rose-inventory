@extends('admin.admin_master')
@section('admin')
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
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone}}</option>
                                @endforeach
                            </select>
                        </div>

                        <button id="fetchLedger" class="btn btn-primary">Fetch Ledger</button>
                        <button id="downloadLedger" class="btn btn-success"> <i class="fa fa-download"
                                aria-hidden="true"></i> Download</button>
                        {{-- <button id="downloadLedgerExcel" class="btn btn-info"> <i class="fas fa-file-excel"></i>
                            Excel</button> --}}

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
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#fetchLedger').on('click', function() {
            let customerId = $('#customer').val();
            clearPreviousResults();
            if (customerId) {
                $.ajax({
                    url: '{{ route('customer.ledger.fetch') }}',
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
                                    ${ledger.paid_source ? ' (' + ledger.paid_source.toUpperCase() + ')' : ''}
                                        </td>
                                    <td>${ledger.total_amount ? ledger.total_amount.toFixed(2) : '0.00'}</td>
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
                    url: '{{ route('customer.ledger.download') }}',
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

{{-- <script>
    $(document).ready(function() {
        $('#downloadLedger').on('click', function() {
            let customerId = $('#customer').val();
            if (customerId) {
                $.ajax({
                    url: '{{ route('customer.ledger.download') }}',
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
</script> --}}
