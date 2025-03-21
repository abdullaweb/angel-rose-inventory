@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Add Product </h4><br><br>


                            {{-- @if (count($errors))
                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger alert-dismissible fade show"> {{ $error }} </p>
                                @endforeach
                            @endif --}}


                            <form method="post" action="{{ route('product.store') }}" id="myForm"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Product
                                        Name</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="name" placeholder="Product Name" class="form-control" type="text"
                                            id="name">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- end row -->



                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Caterogy Name</label>
                                    <div class="col-sm-10 form-group">
                                        <select name="category_id" id="category_id" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Supplier Name</label>
                                    <div class="col-sm-10 form-group">
                                        <select name="supplier_id" id="supplier_id" class="form-control">
                                            <option value="">Select Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Unit Name</label>
                                    <div class="col-sm-10 form-group">
                                        <select name="unit_id" id="unit_id" class="form-control">
                                            <option value="">Select Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Product Image</label>
                                    <div class="col-sm-10 form-group">
                                        <input type="file" name="image" id="image" class="form-control" required>
                                    </div>

                                    <div class="col-2 mt-3 text-secondary">

                                    </div>
                                    <div class="col-10 mt-3 text-secondary">
                                        <img id="showImage" src="{{ url('upload/no-image.jpg') }}" alt="Employee Image"
                                            width="200px" height="200px">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2 mt-3 text-secondary">

                                    </div>
                                    <div class="col-10 mt-3 text-secondary">
                                        <input type="submit" class="btn btn-info waves-effect waves-light"
                                            value="Add Product">
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

    <script>
        $(document).ready(function() {
            $("#myForm").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    category_id: {
                        required: true,
                    },
                    unit_id: {
                        required: true,
                    },
                    supplier_id: {
                        required: true,
                    },
                    image: {
                        required: true,
                    },
                },
                message: {
                    name: {
                        required: "Please Enter Sub Category name",
                    },
                    category_id: {
                        required: "Please Enter category name",
                    },
                    unit_id: {
                        quantity: "Please Enter your quantity",
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
