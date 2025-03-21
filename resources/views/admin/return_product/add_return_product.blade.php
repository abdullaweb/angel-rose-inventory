@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add Return Product </h4><br><br>

                            <form method="post" action="{{ route('store.return') }}" enctype="multipart/form-data"
                                class="custom-validation" novalidate="" autocomplete="off">
                                @csrf

                                <div class="row mb-3">
                                    <label for="invoice_id" class="col-sm-2 col-form-label">Invoice Number</label>
                                    <div class="col-sm-10 form-group">
                                        <select name="invoice_id" id="invoice_id" class="form-control select2"
                                            required="" data-parsley-required-message="Invoice Id is required">
                                            <option value="">Select Invoice</option>
                                            @foreach ($allInvoice as $invoice)
                                                <option value="{{ $invoice->id }}">{{ $invoice->invoice_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->
                                <div class="row mb-3">
                                    <label for="return_reason" class="col-sm-2 col-form-label">Return Reason</label>
                                    <div class="col-sm-10 form-group">
                                        <input type="text" name="return_reason" id="return_reason" class="form-control"
                                            placeholder="Type Return Reasons..." required=""
                                            data-parsley-required-message="Return Reasons is required">
                                    </div>
                                </div>
                                <!-- end row -->


                                <div class="row mb-3">
                                    <div class="col-10 mt-3 text-secondary">
                                        <input type="submit" class="btn btn-info waves-effect waves-light"
                                            value="Add Return Product">
                                    </div>
                                </div>
                                <!-- end row -->
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>



        </div>
    </div>
@endsection
