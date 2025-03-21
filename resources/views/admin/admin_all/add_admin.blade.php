@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-muted">Add Admin</h4>
                            <form class="custom-validation" action="{{ route('store.admin') }}" method="POST" novalidate=""
                                enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <input type="text" id="name" name="name" class="form-control"
                                                required="" placeholder="Full Name"
                                                data-parsley-required-message="Full Name is required">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    placeholder="Employee Email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div>
                                                <input type="tel" id="phone" name="phone" class="form-control"
                                                    required="" placeholder="Phone Number"
                                                    data-parsley-required-message="Phone Number is required.">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Enter Password">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <input type="text" name="address" class="form-control"
                                                placeholder="Enter Address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <select name="role" id="role" class="form-control">
                                                <option value="" disabled selected>Select User Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                            Add Admin
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
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
