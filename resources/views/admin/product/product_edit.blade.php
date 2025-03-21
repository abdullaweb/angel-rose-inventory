@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Product Update Page </h4><br><br>


                            @if (count($errors))
                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger alert-dismissible fade show"> {{ $error }} </p>
                                @endforeach
                            @endif


                            <form method="post" action="{{ route('product.update') }}" id="myForm"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ $product->id }}">

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Product Name</label>
                                    <div class="col-sm-10 form-group">
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $product->name }}">
                                    </div>
                                </div>
                                <!-- end row -->



                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Name</label>
                                    <div class="col-sm-10 form-group">
                                        <select name="supplier_id" id="supplier_id" class="form-control">
                                            @foreach ($suppliers as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $product->supplier_id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->


                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Category Name</label>
                                    <div class="col-sm-10 form-group">
                                        <select name="category_id" id="category_id" class="form-control">
                                            @foreach ($category as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $product->category_id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Unit Name</label>
                                    <div class="col-sm-10 form-group">
                                        <select name="unit_id" id="unit_id" class="form-control">
                                            @foreach ($units as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $product->unit_id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Product Image</label>
                                    <div class="col-sm-10 form-group">
                                        <input type="file" name="image" id="image" class="form-control">
                                    </div>

                                    <div class="col-2 mt-3 text-secondary">
                                    </div>
                                    <div class="col-10 mt-3 text-secondary">
                                        <img id="showImage"
                                            src="{{ !empty($product->image) ? url('upload/product_images/' . $product->image) : url('upload/no-image.jpg') }}"
                                            alt="Employee Image" width="200px" height="200px">
                                    </div>
                                </div>


                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Update Product">
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>



        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#myForm").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    mobile_no: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                },
                message: {
                    name: {
                        required: "Please Enter your name",
                    },
                    email: {
                        required: "Please Enter your email address",
                    },
                    mobile_no: {
                        required: "Please Enter your mobile number",
                    },
                    address: {
                        required: "Please Enter your address",
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-faedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // image on load
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endsection
