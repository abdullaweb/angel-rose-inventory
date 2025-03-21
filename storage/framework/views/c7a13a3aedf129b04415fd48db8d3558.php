<!doctype html>
<html lang="en">

<head>


    <link
        href="https://fonts.googleapis.com/css2?family=Lato&family=Montserrat:ital,wght@0,300;0,400;1,200&family=Roboto:ital@0;1&display=swap"
        rel="stylesheet">


    <link href="https://fonts.googleapis.com/css2?family=GFS+Didot&display=swap" rel="stylesheet">

    <meta charset="utf-8" />
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Rupa Printing Press Account Software" name="description" />
    <meta content="" name="author" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('backend/assets/images/favicon.ico')); ?>">

    <!-- jquery.vectormap css -->
    <link href="<?php echo e(asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')); ?>"
        rel="stylesheet" type="text/css" />


    <!-- DataTables -->
    <link href="<?php echo e(asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('backend/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('backend/assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css')); ?>"
        rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="<?php echo e(asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>"
        rel="stylesheet" type="text/css" />


    <!-- Bootstrap Css -->
    <link href="<?php echo e(asset('backend/assets/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo e(asset('backend/assets/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo e(asset('backend/assets/css/app.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

    <!-- date picker-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Handle bar-->
    <script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
    <style>
        @font-face {
            font-family: myCustomFont;
            src: url(<?php echo e(asset('backend/assets/fonts/Euclid-Circular-A-Regular.ttf')); ?>);
        }
        body{
            font-family: 'myCustomFont';
        }
    </style>
</head>
<?php /**PATH D:\xampp\htdocs\developer_mode\ar_distribution\resources\views/admin/body/head.blade.php ENDPATH**/ ?>