@extends('admin.admin_master')
<style>
    .page-link svg,
    .page-link i,
    .page-link::before {
        width: 1em !important;
        height: 1em !important;
        font-size: 1em !important;
        vertical-align: middle;
    }
</style>
@section('admin')
    <div class="page-content">
        <!-- breadcrumb -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Product Stock</h6>
        </div>
        <!-- end breadcrumb -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <livewire:product-stock-table />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
