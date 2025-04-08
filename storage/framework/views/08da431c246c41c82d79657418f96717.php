 <div class="vertical-menu">

     <div data-simplebar class="h-100">

         <!-- User details -->


         <!--- Sidemenu -->
         <div id="sidebar-menu">
             <!-- Left Menu Start -->
             <ul class="metismenu list-unstyled" id="side-menu">
                 <li class="menu-title">Menu</li>

                 <li>
                     <a href="<?php echo e(route('admin.dashboard')); ?>" class="waves-effect">
                         <i class="ri-dashboard-line"></i>
                         <span>Dashboard</span>
                     </a>
                 </li>
                 <hr>

                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-file-user-line"></i>
                         <span>Customer Manage</span>
                     </a>

                     <ul class="sub-menu" aria-expanded="true">
                         <li>
                             <a href="<?php echo e(route('all.customer')); ?>" class="waves-effect">
                                 <i class="ri-file-user-line"></i>
                                 <span>All Customer</span>
                             </a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('all.wholesaler')); ?>" class="waves-effect">
                                 <i class="ri-file-user-line"></i>
                                 <span>All WholeSaler</span>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <hr>

                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-file-user-line"></i>
                         <span>Due Manage</span>
                     </a>

                     <ul class="sub-menu" aria-expanded="true">
                         <li>
                             <a href="<?php echo e(route('all.due.payment')); ?>" class="waves-effect">
                                 <i class="ri-file-user-line"></i>
                                 <span>All Due</span>
                             </a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('add.due.payment')); ?>" class="waves-effect">
                                 <i class="ri-file-user-line"></i>
                                 <span>Add Due</span>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <hr>

                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-layout-3-line"></i>
                         <span>Supplier Manage</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">
                         <li>
                            <a href="<?php echo e(route('supplier.all')); ?>">
                                <i class="ri-arrow-right-line"></i>All Supplier
                            </a>
                         </li>
                     </ul>
                 </li>
                 <hr>

                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-product-hunt-line"></i>
                         <span>Product Manage</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">
                         <li>
                             <a href="<?php echo e(route('unit.all')); ?>" class="waves-effect">
                                 <i class="ri-mail-send-line"></i>
                                 <span>Unit Setup</span>
                             </a>
                         </li>

                         <li>
                             <a href="<?php echo e(route('category.all')); ?>" class="waves-effect">
                                 <i class="ri-mail-send-line"></i>
                                 <span>Product Category</span>
                             </a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('product.all')); ?>" class="waves-effect">
                                 <i class="ri-mail-send-line"></i>
                                 <span>All Product</span>
                             </a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('product.adjustment.all')); ?>" class="waves-effect">
                                 <i class="ri-mail-send-line"></i>
                                 <span>Product Adjustment</span>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <hr>
                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-product-hunt-line"></i>
                         <span>Purchase Manage</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">
                         <li>
                             <a href="<?php echo e(route('all.purchase')); ?>" class="waves-effect">
                                 <i class="ri-product-hunt-line"></i>
                                 <span>Purchase Product</span>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <hr>

                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-product-hunt-line"></i>
                         <span>Sotck Manage</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">
                         <li>
                             <a href="<?php echo e(route('product.stock')); ?>" class="waves-effect">
                                 <i class="ri-mail-send-line"></i>
                                 <span>Product Stock</span>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <hr>


                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-layout-3-line"></i>
                         <span>Sales Manage</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">

                         <li>
                             <a href="<?php echo e(route('invoice.all')); ?>">
                                 <i class="ri-mail-send-line"></i>
                                 <span>All Invoice</span>
                             </a>
                         </li>
                    </ul>
                 </li>
                <hr>
                <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-layout-3-line"></i>
                         <span>Return Manage</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">

                         <li>
                             <a href="<?php echo e(route('all.return.product')); ?>">
                                 <i class="ri-mail-send-line"></i>
                                 <span>All Return</span>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <hr>
                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-layout-3-line"></i>
                         <span>Employee Manage</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">
                         <li><a href="<?php echo e(route('all.employee')); ?>"><i class="ri-arrow-right-line"></i>All
                                 Employee</a>
                         </li>
                     </ul>
                 </li>
                 <hr>
                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-layout-3-line"></i>
                         <span>Salary Manage</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">
                         <li>
                             <a href="<?php echo e(route('all.advanced.salary')); ?>">
                                 <i class="metismenu-icon"></i>All Advanced
                             </a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('pay.salary')); ?>">
                                 <i class="metismenu-icon"></i>Pay Salary
                             </a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('add.salary')); ?>">
                                 <i class="metismenu-icon"></i>Add Salary
                             </a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('all.overtime')); ?>">
                                 <i class="metismenu-icon"></i>All Overtime
                             </a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('all.bonus')); ?>">
                                 <i class="metismenu-icon"></i>All Bonus
                             </a>
                         </li>
                     </ul>
                 </li>
                 <hr>
                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-layout-3-line"></i>
                         <span>Account Setup</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">
                         <li>
                             <a href="javascript: void(0);" class="has-arrow">Bank</a>
                             <ul class="sub-menu" aria-expanded="true">
                                 <li><a href="<?php echo e(route('all.bank')); ?>"><i class="ri-arrow-right-line"></i> All
                                         Bank</a></li>
                                 <li><a href="<?php echo e(route('add.bank')); ?>"><i class="ri-arrow-right-line"></i> Add
                                         Bank</a></li>
                             </ul>
                         </li>
                         <li>
                             <a href="javascript: void(0);" class="has-arrow">Expense</a>
                             <ul class="sub-menu" aria-expanded="true">
                                 <li><a href="<?php echo e(route('all.expense')); ?>"><i class="ri-arrow-right-line"></i> All
                                         Expense</a></li>
                                 <li><a href="<?php echo e(route('add.expense')); ?>"><i class="ri-arrow-right-line"></i> Add
                                         Expense</a></li>
                             </ul>
                         </li>
    
                         <li>
                             <a href="javascript: void(0);" class="has-arrow waves-effect">
                                 <span>Due Payment</span>
                             </a>
                             <ul class="sub-menu" aria-expanded="false">
                                 <li><a href="<?php echo e(route('credit.customer')); ?>"><i class="ri-arrow-right-line"></i>Credit
                                         Customer</a>
                             </ul>
                         </li>
    
                         <li>
                             <a href="javascript: void(0);" class="has-arrow">Opening Balance</a>
                             <ul class="sub-menu" aria-expanded="true">
                                 <li>
                                     <a href="<?php echo e(route('all.opening.balance')); ?>"><i class="ri-arrow-right-line"></i>
                                         Wholesaler</a>
                                 </li>
                                 <li>
                                     <a href="<?php echo e(route('all.opening.supplier')); ?>"><i class="ri-arrow-right-line"></i>
                                         Supplier</a>
                                 </li>
                             </ul>
                         </li>
                     </ul>
                 </li>
                 <hr>
                 <li>
                     <a href="javascript: void(0);" class="has-arrow waves-effect">
                         <i class="ri-layout-3-line"></i>
                         <span>Report Manage</span>
                     </a>
                     <ul class="sub-menu" aria-expanded="true">
                         <li>
                             <a href="<?php echo e(route('category.report')); ?>" class="waves-effect">
                                 <i class="ri-arrow-right-line"></i>
                                 <span>Category Report</span>
                             </a>
                         </li>
    
                         <li>
                             <a href="<?php echo e(route('get.cat.report.summary')); ?>">
                                 <i class="ri-arrow-right-line"></i>Category Summery</a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('customer.ledger.index')); ?>">
                                 <i class="ri-arrow-right-line"></i>Customer Ledger</a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('daily.invoice.report')); ?>">
                                 <i class="ri-arrow-right-line"></i>Sales Purchase Report</a>
                         </li>
                         <li>
                             <a href="<?php echo e(route('profit.report')); ?>">
                                 <i class="ri-arrow-right-line"></i>Profit Report</a>
                         </li>
                     </ul>
                 </li>
                 <hr>
    
                 <?php if(Auth::user()->can('role.permission.menu')): ?>
                     <li>
                         <a href="javascript: void(0);" class="has-arrow waves-effect">
                             <i class="ri-layout-3-line"></i>
                             <span>Role & Permission</span>
                         </a>
                         <ul class="sub-menu" aria-expanded="true">
                             <?php if(Auth::user()->can('all.permission')): ?>
                                 <li>
                                     <a href="<?php echo e(route('all.permission')); ?>" class="waves-effect">
                                         <i class="ri-layout-3-line"></i>
                                         <span>All Permission</span>
                                     </a>
                                 </li>
                             <?php endif; ?>
                             <?php if(Auth::user()->can('all.role')): ?>
                                 <li>
                                     <a href="<?php echo e(route('all.role')); ?>">
                                         <i class="ri-arrow-right-line"></i>All Role</a>
                                 </li>
                             <?php endif; ?>
                             <?php if(Auth::user()->can('all.role.permission')): ?>
                                 <li>
                                     <a href="<?php echo e(route('all.role.permission')); ?>">
                                         <i class="ri-arrow-right-line"></i>All Role Permission</a>
                                 </li>
                             <?php endif; ?>
                         </ul>
                     </li>
                     <hr>
                 <?php endif; ?>
                 <?php if(Auth::user()->can('admin.menu')): ?>
                     <li>
                         <a href="javascript: void(0);" class="has-arrow waves-effect">
                             <i class="ri-layout-3-line"></i>
                             <span>Admin Manage</span>
                         </a>
                         <ul>
                             <?php if(Auth::user()->can('admin.list')): ?>
                                 <li>
                                     <a href="<?php echo e(route('all.admin')); ?>">
                                         <i class="ri-arrow-right-line"></i>All Admin
                                     </a>
                                 </li>
                             <?php endif; ?>
                         </ul>
                     </li>
                 <?php endif; ?>
             </ul>
         </div>
         <!-- Sidebar -->
     </div>
 </div>
<?php /**PATH D:\laragon\www\angelrose-software\resources\views/admin/body/sidebar.blade.php ENDPATH**/ ?>