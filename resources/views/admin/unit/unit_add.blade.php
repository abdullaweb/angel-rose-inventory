@extends('admin.admin_master')
@section('admin')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Unit Add Page </h4><br><br>


                            @if (count($errors))
                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger alert-dismissible fade show"> {{ $error }} </p>
                                @endforeach
                            @endif


                            <form method="post" action="{{ route('unit.store') }}" id="myForm">
                                @csrf

                                <div class="row mb-3">
                                    <label for="name" class="col-sm-2 col-form-label">Unit Name</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="name" class="form-control" type="text" id="name">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="short_form" class="col-sm-2 col-form-label">Unit Short Form</label>
                                    <div class="col-sm-10 form-group">
                                        <input name="short_form" class="form-control" type="text" id="short_form">
                                    </div>
                                </div>
                                <!-- end row -->

                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Add Unit">
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
                    short_form: {
                        required: true,
                    },
                },
                message: {
                    name: {
                        required: "Please Enter your unit name",
                    },
                    short_form: {
                        required: "Please Enter your unit short form",
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


@endsection
