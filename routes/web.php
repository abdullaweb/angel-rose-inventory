<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\SalaryController;
use App\Http\Controllers\Backend\AccountController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\EmployeeSalaryController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\DuePaymentController;
use App\Http\Controllers\Backend\ProductAdjustmentController;
use App\Http\Controllers\Backend\UnitController;
use App\Http\Controllers\Backend\AdvancedController;
use App\Http\Controllers\Backend\DefaultController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ReturnProductController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WastesSaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/dashboard', function () {
    return view('users.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/logout', [AdminController::class, 'UserLogout'])->name('user.logout');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/', [AdminController::class, 'RedirectDashboard']);
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'ChangeAdminPassword'])->name('change.admin.password');
    Route::post('/update/change/password', [AdminController::class, 'UpdateAdminPassword'])->name('update.admin.password');



    Route::controller(CustomerController::class)->group(function () {
        // compnay all route
        Route::get('/customer/all', 'CustomerAll')->name('all.customer');
        Route::get('/wholesaler/all', 'WholesalerAll')->name('all.wholesaler');
        Route::get('/customer/add', 'CustomerAdd')->name('add.customer');
        Route::post('/customer/store', 'CustomerStore')->name('store.customer');
        Route::get('/customer/edit/{id}', 'CustomerEdit')->name('edit.customer');
        Route::post('/customer/update', 'CustomerUpdate')->name('update.customer');
        Route::get('/customer/delete/{id}', 'CustomerDelete')->name('delete.customer');
        Route::get('/customer/get/{id}', 'GetCustomer')->name('get.customer');

        Route::get('/customer/bill/{id}', 'CustomerBill')->name('customer.bill');
        Route::get('/customer/bill/delete/{id}', 'CustomerBillDelete')->name('customer.bill.delete');
        //credit company
        Route::get('/credit/customer/all', 'CreditCustomer')->name('credit.customer');
        Route::get('/credit/customer/invoice/{invoice_id}', 'EditCreditCustomerInvoice')->name('edit.credit.customer');
        Route::post('/credit/customer/invoice-update/{invoice_id}', 'UpdateCustomerInvoice')->name('customer.update.invoice');
        Route::get('customer/invoice/details/{invoice_id}', 'CustomerInvoiceDetails')->name('customer.invoice.details');

        Route::get('/customer/account/{id}', 'CustomerAccountDetails')->name('customer.account.details');
        Route::post('/customer/account-report', 'CustomerAccountDetailReport')->name('get.customer.account.detail');

        //dynamic query
        Route::get('/dynamic/query/customer', 'DynamicQueryCustomer')->name('dynamic.query.customer');
    });

    // Product Adjustment All Route
    Route::controller(ProductAdjustmentController::class)->group(function () {
        Route::get('/product/adjustment/all', 'ProductAdjustmentAll')->name('product.adjustment.all');
        Route::get('/product/adjustment/add', 'ProductAdjustmentAdd')->name('product.adjustment.add');
        Route::post('/product/adjustment/store', 'ProductAdjustmentStore')->name('product.adjustment.store');
        Route::get('/product/adjustment/edit/{id}', 'ProductAdjustmentEdit')->name('product.adjustment.edit');
        Route::post('/product/adjustment/update', 'ProductAdjustmentUpdate')->name('product.adjustment.update');
        Route::get('/product/adjustment/delete/{id}', 'ProductAdjustmentDelete')->name('product.adjustment.delete');
        // view adjustment
        Route::get('/product/adjustment/view/{id}', 'ProductAdjustmentView')->name('product.adjustment.view');

        Route::get('/get/products/{id}', 'GetProduct')->name('get.products');
    });


    Route::controller(DuePaymentController::class)->group(function () {
        Route::get('/all/due-payment', 'AllDuePayment')->name('all.due.payment');
        Route::get('/add/due-payment', 'AddDuePayment')->name('add.due.payment');
        Route::post('/submit/due-payment', 'StoreDuePayment')->name('submit.due.payment');
        Route::get('/edit/due-payment/{id}', 'EditDuePayment')->name('edit.due.payment');
        Route::post('/update/due-payment', 'UpdateDuePayment')->name('update.due.payment');
        Route::get('/delete/due-payment/{id}', 'DeleteDuePayment')->name('delete.due.payment');

        Route::post('/get/due-payment', 'GetDuePayment')->name('get.due.amount');
        Route::get('/due-payment/approval', 'DuePaymentApproval')->name('due.payment.approval');
        Route::get('/due-payment/approval/{id}', 'DuePaymentApprovalNow')->name('due.payment.approval.now');
    });


    // employee all route
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/emplopyee/all', 'EmployeeAll')->name('all.employee');
        Route::get('/emplopyee/add', 'EmployeeAdd')->name('add.employee');
        Route::post('/emplopyee/store', 'EmployeeStore')->name('store.employee');
        Route::get('/emplopyee/edit/{id}', 'EmployeeEdit')->name('edit.employee');
        Route::post('/emplopyee/update', 'EmployeeUpdate')->name('update.employee');
        Route::get('/emplopyee/delete/{id}', 'EmployeeDelete')->name('delete.employee');
        Route::get('/emplopyee/view/{id}', 'EmployeeView')->name('employee.view');

        Route::get('/emplopyee/salary/increment/{id}', 'SalaryIncrement')->name('employee.salary.increment');
        Route::post('/emplopyee/salary/store/{id}', 'SalaryIncrementUpdate')->name('update.employee.salary');
        Route::get('/emplopyee/salary/details/{id}', 'SalaryDetails')->name('employee.salary.details');
    });


    // Category All Route
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category/all', 'categoryAll')->name('category.all');
        Route::get('/category/add', 'categoryAdd')->name('category.add');
        Route::post('/category/store', 'categoryStore')->name('category.store');
        Route::get('/category/edit/{id}', 'categoryEdit')->name('category.edit');
        Route::post('/category/update', 'categoryUpdate')->name('category.update');
        Route::get('/category/delete/{id}', 'categoryDelete')->name('category.delete');
    });

    // Ledger Controller All Route

    Route::controller(LedgerController::class)->group(function () {
        Route::get('/customer-ledger', 'CustomerLedger')->name('customer.ledger.index');
        Route::post('/customer-ledger/fetch',  'CustomerfetchLedger')->name('customer.ledger.fetch');
        Route::post('/customer-ledger/download',  'CustomerdownloadLedger')->name('customer.ledger.download');
        Route::post('/customer-ledger/download-excel', 'CustomerdownloadLedgerExcel')->name('customer.ledger.download.excel');
        Route::get('/pdf/view', 'PdfView');
    });

    // Supplier All Route
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/supplier/all', 'SupplierAll')->name('supplier.all');
        Route::get('/supplier/add', 'SupplierAdd')->name('supplier.add');
        Route::post('/supplier/store', 'SupplierStore')->name('supplier.store');
        Route::get('/supplier/edit/{id}', 'SupplierEdit')->name('supplier.edit');
        Route::post('/supplier/update', 'SupplierUpdate')->name('supplier.update');
        Route::get('/supplier/delete/{id}', 'SupplierDelete')->name('supplier.delete');

        Route::get('/supplier/purcahse/{id}', 'SupplierBill')->name('supplier.all.purchase');
        Route::get('/supplier/account/{id}', 'SupplierAccountDetails')->name('supplier.account.details');
        Route::post('/supplier/account-report', 'SupplierAccountDetailReport')->name('get.supplier.account.detail');
        Route::get('/emplopyee/salary/view/', 'SalaryView')->name('employee.salary.view');

        // opening balance
        Route::get('supplier/all/opening', 'AllOpeningBalance')->name('all.opening.supplier');
        Route::get('supplier/add/opening', 'AddOpeningBalance')->name('add.opening.supplier');
        Route::post('supplier/store/opening', 'StoreOpeningBalance')->name('store.opening.supplier');
        Route::get('supplier/edit/opening/{id}', 'EditOpeningBalance')->name('edit.opening.supplier');
        Route::get('supplier/delete/opening/{id}', 'DeleteOpeningBalance')->name('delete.opening.supplier');
        Route::post('supplier/update/opening', 'UpdateOpeningBalance')->name('update.opening.supplier');
    });

    // Category All Route
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product/all', 'ProductAll')->name('product.all');
        Route::get('/product/add', 'ProductAdd')->name('product.add');
        Route::post('/product/store', 'ProductStore')->name('product.store');
        Route::get('/product/edit/{id}', 'ProductEdit')->name('product.edit');
        Route::post('/product/update', 'ProductUpdate')->name('product.update');
        Route::get('/product/delete/{id}', 'ProductDelete')->name('product.delete');
        Route::get('/product/stock/{id}', 'GetProductStock')->name('get.product.stock');
        Route::get('/product/stock', 'ProductStockAll')->name('product.stock');


        Route::get('/product/sales', 'ProductSales')->name('product.sales');
        Route::get('/get/product/{id}', 'GetProduct')->name('get.product');
    });


    // Unit All Route
    Route::controller(UnitController::class)->group(function () {
        Route::get('/unit/all', 'unitAll')->name('unit.all');
        Route::get('/unit/add', 'unitAdd')->name('unit.add');
        Route::post('/unit/store', 'unitStore')->name('unit.store');
        Route::get('/unit/edit/{id}', 'unitEdit')->name('unit.edit');
        Route::post('/unit/update', 'unitUpdate')->name('unit.update');
        Route::get('/unit/delete/{id}', 'unitDelete')->name('unit.delete');
    });


    // Default All Route
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice/all', 'InvoiceAll')->name('invoice.all');
        Route::get('/invoice/add', 'InvoiceAdd')->name('invoice.add');
        Route::post('/invoice/store', 'InvoiceStore')->name('invoice.store');
        Route::get('/invoice/edit/{id}', 'InvoiceEdit')->name('invoice.edit');
        Route::post('/invoice/update', 'InvoiceUpdate')->name('invoice.update');
        Route::get('/invoice/print/{id}', 'InvoicePrint')->name('invoice.print');
        Route::get('/invoice/view/{id}', 'InvoiceView')->name('invoice.view');
        Route::get('/invoice/delete/{id}', 'InvoiceDelete')->name('invoice.delete');

        Route::get('/dynamic/query', 'DynamicQuery')->name('dynamic.query');
    });


    // purchase all route
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('/all/purchase', 'AllPurchase')->name('all.purchase');
        Route::get('/add/purchase', 'AddPurchase')->name('add.purchase');
        Route::post('/store/purchase', 'StorePurchase')->name('store.purchase');
        Route::get('/edit/purchase/{id}', 'EditPurchase')->name('edit.purchase');
        Route::get('/view/purchase/{id}', 'ViewPurchase')->name('view.purchase');
        Route::post('/update/purchase', 'UpdatePurchase')->name('update.purchase');
        Route::get('/delete/purchase/{id}', 'DeletePurchase')->name('delete.purchase');
        Route::post('/get/purchase', 'GetPurchase')->name('get.purchase');
        Route::post('/get/product', 'GetProduct');
        Route::get('/purchase/history/{id}', 'PurchaseHistory')->name('purchase.history');

        // due payment by purchase id
        Route::get('/due-payment/purchase/{id}', 'PurchaseDuePayment')->name('purchase.due.payment');
        Route::post('/store/due-purchase', 'PurchaseDuePaymentStore')->name('store.purchase.due');
        Route::get('/print/purchase/{id}', 'PurchasePrint')->name('print.purchase');
    });


    
     // return product all route
    Route::controller(ReturnProductController::class)->group(function () {
        Route::get('/all/return/product', 'AllReturnProduct')->name('all.return.product');
        Route::get('/add/return', 'AddReturnProduct')->name('add.return');
        Route::post('/store/return', 'StoreReturnProduct')->name('store.return');
        Route::get('/edit/return/{id}', 'EditReturnProduct')->name('edit.return');
        Route::post('/update/return', 'UpdateReturnProduct')->name('update.return');
        Route::get('/delete/return/{id}', 'DeleteReturnProduct')->name('delete.return');
    });



    // Bank all route
    Route::controller(BankController::class)->group(function () {
        Route::get('/all/bank', 'AllBank')->name('all.bank');
        Route::get('/add/bank', 'AddBank')->name('add.bank');
        Route::post('/store/bank', 'StoreBank')->name('store.bank');
        Route::get('/edit/bank/{id}', 'EditBank')->name('edit.bank');
        Route::post('/update/bank', 'UpdateBank')->name('update.bank');
        Route::get('delete/bank/{id}', 'DeleteBank')->name('delete.bank');
        Route::get('details/bank/{id}', 'DetailsBank')->name('bank.details');
    });


    // account all route
    Route::controller(AccountController::class)->group(function () {
        Route::get('/all/expense', 'AllExpense')->name('all.expense');
        Route::get('/add/expense', 'AddExpense')->name('add.expense');
        Route::post('/store/expense', 'StoreExpense')->name('store.expense');
        Route::get('/edit/expense/{id}', 'EditExpense')->name('edit.expense');
        Route::post('/update/expense}', 'UpdateExpense')->name('update.expense');
        Route::get('/delete/expense/{id}', 'DeleteExpense')->name('delete.expense');
        Route::get('/daily/expense', 'DailyExpense')->name('daily.expense');
        Route::get('/monthly/expense', 'MonthlyExpense')->name('monthly.expense');
        Route::get('/yearly/expense', 'YearlyExpense')->name('yearly.expense');
        Route::post('/get/expense', 'GetExpense')->name('get.expense');

        // account details filtering method
        Route::post('/get/account/details', 'GetAccountDetails')->name('get.account.detail');

        //profit calculation
        Route::get('/calculate/profit', 'AddProfit')->name('add.profit');
        Route::post('/get/profit', 'GetProfit')->name('get.profit');


        // opening balance
        Route::get('all/opening/balance', 'AllOpeningBalance')->name('all.opening.balance');
        Route::get('add/opening/balance', 'AddOpeningBalance')->name('add.opening.balance');
        Route::post('store/opening/balance', 'StoreOpeningBalance')->name('store.opening.balance');
        Route::get('edit/opening/balance/{id}', 'EditOpeningBalance')->name('edit.opening.balance');
        Route::get('delete/opening/balance/{id}', 'DeleteOpeningBalance')->name('delete.opening.balance');
        Route::post('update/opening/balance', 'UpdateOpeningBalance')->name('update.opening.balance');
    });



    // advance all route
    Route::controller(AdvancedController::class)->group(function () {
        Route::get('/all/advanced/salary', 'AllAdvancedSalary')->name('all.advanced.salary');
        Route::get('/add/advanced/salary', 'AddAdvancedSalary')->name('add.advanced.salary');
        Route::post('/store/advanced/salary', 'StoreAdvancedSalary')->name('store.advanced.salary');
        Route::get('/advanced/salary/{id}', 'EditAdvancedSalary')->name('edit.advanced.salary');
        Route::post('/update/salary', 'UpdateAdvancedSalary')->name('update.advanced.salary');
        Route::get('delete/advanced-salary/{id}', 'DeleteAdvancedSalary')->name('delete.advanced.salary');
    });

    // pay salary all route
    Route::controller(SalaryController::class)->group(function () {
        Route::get('/pay/salary', 'PaySalary')->name('pay.salary');
        Route::get('/pay/salary/{id}', 'PaySalaryNow')->name('pay.salary.now');
        Route::post('/store/salary', 'StorePaySalary')->name('pay.salary.store');

        // add slary
        Route::get('/add/salary', 'AddSalary')->name('add.salary');
        // Route::get('/store/salary', 'StoreSalary')->name('store.salary');

        // overtimes all routes
        Route::get('/all/overtime', 'AllOvertime')->name('all.overtime');
        Route::get('/add/overtime', 'AddOvertime')->name('add.overtime');
        Route::post('/store/overtime', 'StoreOvertime')->name('store.overtime');
        Route::get('/edit/overtime/{id}', 'EditOvertime')->name('edit.overtime');
        Route::post('/update/overtime', 'UpdateOvertime')->name('update.overtime');
        Route::get('/delete/overtime/{id}', 'DeleteOvertime')->name('delete.overtime');


        // overtimes all routes
        Route::get('/all/bonus', 'AllBonus')->name('all.bonus');
        Route::get('/add/bonus', 'AddBonus')->name('add.bonus');
        Route::post('/store/bonus', 'StoreBonus')->name('store.bonus');
        Route::get('/edit/bonus/{id}', 'EditBonus')->name('edit.bonus');
        Route::post('/update/bonus', 'UpdateBonus')->name('update.bonus');
        Route::get('/delete/bonus/{id}', 'DeleteBonus')->name('delete.bonus');


        // payment details
        Route::get('/payment/details/{id}', 'EmployeePaymentDetails')->name('employee.payment.details');
    });

    // category report method
    Route::controller(ReportController::class)->group(function () {
        Route::get('/category/report', 'CategoryReport')->name('category.report');
        Route::post('/get/category-report', 'GetCategoryReport')->name('get.cat.report');
        Route::get('/category-report/summary', 'GetCategoryReportSummary')->name('get.cat.report.summary');
        Route::post('/print/category/summary', 'PrintCategorySummary')->name('get.cat.report.summary.print');

        // invoice report
        Route::get('daily/invoice/report', 'DailyInvoiceReport')->name('daily.invoice.report');
        Route::get('daily/invoice/pdf', 'DailyInvoiceReportPdf')->name('daily.invoice.pdf');
        
        //profit
        Route::get('profit/report', 'ProfitReport')->name('profit.report');
        Route::post('/profit/filter', 'ProfitResult')->name('profit.filter');
    });


    // category report method
    Route::controller(DefaultController::class)->group(function () {
        Route::get('get/salary', 'GetEmployeeSalary')->name('get.employee.salary');
        Route::get('get/advance', 'GetEmployeeAdvance')->name('get.employee.advance');
        // Route::get('/get-product', 'GetProduct')->name('get.product');
    });


    // permission all route
    Route::controller(RoleController::class)->group(function () {
        Route::get('/all/permission', 'AllPermission')->name('all.permission');
        Route::get('/add/permission', 'AddPermission')->name('add.permission');
        Route::post('/store/permission', 'StorePermission')->name('store.permission');
        Route::post('/update/permission', 'UpdatePermission')->name('update.permission');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission');
    });

    // role  all route
    Route::controller(RoleController::class)->group(function () {
        Route::get('/all/role', 'AllRole')->name('all.role');
        Route::get('/add/role', 'AddRole')->name('add.role');
        Route::post('/store/role', 'StoreRole')->name('store.role');
        Route::post('/update/role', 'UpdateRole')->name('update.role');
        Route::get('/edit/role/{id}', 'EditRole')->name('edit.role');
        Route::get('/delete/role/{id}', 'DeleteRole')->name('delete.role');

        // role in permission
        Route::get('/add/role/permission', 'AddRolepermission')->name('add.role.permission');
        Route::get('/all/role/permission', 'AllRolepermission')->name('all.role.permission');
        Route::post('/store/role/permission', 'StoreRolepermission')->name('role.permission.store');
        Route::get('admin/edit/role/{id}', 'AdminEditRole')->name('admin.edit.role');
        Route::post('/admin/role/update/{id}', 'AdminUpdateRole')->name('admin.role.update');
        Route::get('admin/delete/role/{id}', 'AdminDeleteRole')->name('admin.delete.role');
    });
    // end role all route

    // admin all route
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/all', 'AdminAll')->name('all.admin');
        Route::get('/admin/add', 'AdminAdd')->name('add.admin');
        Route::post('/admin/store', 'AdminStore')->name('store.admin');
        Route::get('/edit/admin/role/{id}', 'EditAdminRole')->name('edit.admin.role');
        Route::post('/update/admin/role', 'UpdateAdminRole')->name('update.admin.role');
        Route::get('/delete/admin/role/{id}', 'DeleteAdminRole')->name('delete.admin.role');
    });
});


require __DIR__ . '/auth.php';
