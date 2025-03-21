<?php echo $__env->make('admin.body.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="page-content">
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card py-5">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="m-auto text-center">
                                        <h6 class="mb-3">Welcome Mr/Mrs. <?php echo e(Auth::user()->name); ?></h6>
                                        <button class="btn btn-info d-block m-auto mb-3">
                                            <a href="/" class="text-dark">Back To Home Page</a>
                                        </button>
                                        <?php if(Auth::user()->role != 'admin'): ?>
                                            <button class="btn btn-info d-block m-auto">
                                                <a href="<?php echo e(route('user.logout')); ?>" class="text-dark">Logout To
                                                    Dashboard</a>
                                            </button>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/u287342192/domains/nebulaitbd.com/public_html/inventory-for-angel-rose/project_files/resources/views/users/index.blade.php ENDPATH**/ ?>