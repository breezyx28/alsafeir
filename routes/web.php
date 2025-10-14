<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\BranchStockController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\CustomOrderController;
use App\Models\Product;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderWizardController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\WorkshopController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\TailorController;
use App\Http\Controllers\PieceRateDefinitionController;
use App\Http\Controllers\TailorAdvanceController;
use App\Http\Controllers\TailorPaymentController;
use App\Http\Controllers\QualityReportController;
use App\Http\Controllers\TailorProductionLogController;
use App\Http\Controllers\TailorAccountingController;
use App\Http\Controllers\ReadySaleController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\DebtInstallmentController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\IncomeStatementController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\OrderPrintController;
use App\Http\Controllers\OrderDeliveryController;

// صفحة الطلبات اليوم
Route::get('/custom-orders/delivery/today', [OrderDeliveryController::class, 'today'])->name('custom-orders.delivery.today');

// صفحة الطلبات غدًا
Route::get('/custom-orders/delivery/tomorrow', [OrderDeliveryController::class, 'tomorrow'])->name('custom-orders.delivery.tomorrow');

Route::get('/custom-orders/print/operator', [OrderDeliveryController::class, 'operatorPrint'])
     ->name('custom-orders.print.operator');


Route::get('/custom-orders/print', [OrderPrintController::class, 'filter'])->name('custom-orders.print.filter');
Route::get('/custom-orders/print/generate', [OrderPrintController::class, 'generate'])->name('custom-orders.print.generate');


Route::prefix('reports')->group(function() {
    Route::get('employee-orders', [ReportsController::class, 'employeeOrders'])->name('reports.employee-orders');
    Route::get('employee-orders/print', [ReportsController::class, 'employeeOrdersPrint'])->name('reports.employee-orders.print');
});


Route::resource('shifts', ShiftController::class);
Route::prefix('shifts')->name('shifts.')->group(function () {
Route::get('/', [ShiftController::class, 'index'])->name('index');
Route::post('/start', [ShiftController::class, 'start'])->name('start');
Route::patch('/{shift}/end', [ShiftController::class, 'end'])->name('end');
});


Route::get('/reports/shifts/{shift}', [ShiftController::class, 'print'])->name('reports.shifts.print');

    Route::get('ledger', [\App\Http\Controllers\LedgerController::class, 'index'])->name('ledger.index');
    Route::get('trial_balance', [\App\Http\Controllers\TrialBalanceController::class, 'index'])->name('trial_balance.index');
    Route::get('income_statement', [IncomeStatementController::class, 'index'])->name('income_statement.index');
    Route::get('/balance_sheet', [App\Http\Controllers\BalanceSheetController::class, 'index'])
    ->name('balance_sheet.index');
    Route::get('/cash_flow', [CashFlowController::class, 'index'])->name('cash_flow.index');



Route::post('/debts/installments/{installment}/pay', [DebtController::class, 'payInstallment'])
    ->name('debts.installments.pay');
Route::resource('debts', DebtController::class);

// قائمة الاستبدالات وعرضها
Route::prefix('exchanges')->group(function() {
    Route::get('/', [ExchangeController::class, 'index'])->name('exchanges.index');
    Route::get('/create/{return}', [ExchangeController::class, 'create'])->name('exchanges.create');
    Route::post('/store', [ExchangeController::class, 'store'])->name('exchanges.store');
    Route::get('/{exchange}', [ExchangeController::class, 'show'])->name('exchanges.show');
});


Route::resource('returns', ReturnController::class);
Route::get('returns/create/{saleId}', [ReturnController::class, 'create'])->name('returns.create');


Route::resource('journal_entries', JournalEntryController::class);

Route::resource('expenses', ExpensesController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/ready_sales', [ReadySaleController::class, 'index'])->name('ready_sales.index');
    Route::get('/ready_sales/create', [ReadySaleController::class, 'create'])->name('ready_sales.create');
    Route::post('/ready_sales', [ReadySaleController::class, 'store'])->name('ready_sales.store');
    Route::get('/ready_sales/{id}', [ReadySaleController::class, 'show'])->name('ready_sales.show');
    Route::get('ready_sales/{id}/print', [App\Http\Controllers\ReadySaleController::class, 'print'])->name('ready_sales.print');

});


Route::get("tailor-accounting", [TailorAccountingController::class, "index"])->name("tailor_accounting.index");
Route::get("tailor-accounting/{tailor}", [TailorAccountingController::class, "show"])->name("tailor_accounting.show");
Route::post("tailor-accounting/{tailor}/process-payment", [TailorAccountingController::class, "processPayment"])->name("tailor_accounting.process_payment");

Route::resource("tailor_production_logs", TailorProductionLogController::class);

Route::resource('quality_reports', QualityReportController::class);

Route::resource('tailor_payments', TailorPaymentController::class);

Route::resource('tailor_advances', TailorAdvanceController::class);

Route::resource('piece_rate_definitions', PieceRateDefinitionController::class);

Route::resource('tailors', TailorController::class);



// --- Customer Portal Routes ---
Route::get('/my-orders', [CustomerPortalController::class, 'showPhoneEntryForm'])->name('portal.phone.form');
Route::post('/my-orders', [CustomerPortalController::class, 'showOrders'])->name('portal.orders.show');
Route::get('/offers', [CustomerPortalController::class, 'showOffersPage'])->name('portal.offers.page');



// ...

Route::get('/tracking', [OrderTrackingController::class, 'index'])->name('orders.tracking');



Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

// راوتات الـ Wizard
Route::prefix('order-wizard')->name('order.wizard.')->middleware(['auth', 'check.employee'])->group(function () {
    Route::get('/step-1', [OrderWizardController::class, 'showStep1'])->name('step1');
    Route::post('/step-1', [OrderWizardController::class, 'processStep1'])->name('processStep1');
    
    Route::get('/{order}/step-2', [OrderWizardController::class, 'showStep2'])->name('step2');
    Route::post('/{order}/step-2', [OrderWizardController::class, 'processStep2'])->name('processStep2');

    Route::get('/{order}/step-3', [OrderWizardController::class, 'showStep3'])->name('step3');
    Route::post('/{order}/step-3', [OrderWizardController::class, 'processStep3'])->name('processStep3');

    Route::get('/{order}/step-4', [OrderWizardController::class, 'showStep4'])->name('step4');
    Route::post('/{order}/step-4', [OrderWizardController::class, 'processStep4'])->name('processStep4');

   
});

    // --- راوتات جديدة لتحديث الطلب ---
    Route::post('/order-products/{orderProduct}/update-status', [OrderProductController::class, 'updateStatus'])->name('order-products.updateStatus');


    // *** أضف هذا الراوت الجديد ***
    Route::get('/customers/{customer}/measurements/{type}', [CustomerController::class, 'getMeasurementsForType'])
        ->name('customers.measurements.getForType');


    Route::middleware(['auth', 'check.employee'])->group(function () {
        
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    // Route::get('/customers/{customer}/measurements/{type}', [CustomerController::class, 'getMeasurements'])->name('customers.measurements');

    // قسم الطلبات المخصصة
    Route::prefix('custom-orders')->name('custom-orders.')->group(function () {
        Route::get('/', [CustomOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [CustomOrderController::class, 'show'])->name('show');
        Route::delete('/{order}', [CustomOrderController::class, 'destroy'])->name('destroy');

        Route::post('/{order}/update-status', [CustomOrderController::class, 'updateStatus'])->name('updateStatus');

        Route::post('/{order}/record-payment', [CustomOrderController::class, 'recordPayment'])->name('recordPayment');

        // --- راوتات الطباعة ---
        Route::get('/{order}/print/measurements', [CustomOrderController::class, 'printMeasurements'])->name('print.measurements');
        Route::get('/{order}/print/invoice', [CustomOrderController::class, 'printInvoice'])->name('print.invoice');
        
             
       
    });

});

Route::get('custom-orders/{order}/print-receipt', [CustomOrderController::class, 'printReceipt'])
            ->name('custom-orders.print.receipt');

Route::get('delivered-orders', [CustomOrderController::class, 'deliveredIndex'])->name('custom-orders.delivered');


Route::get('workshop/board', [WorkshopController::class, 'index'])->name('workshop.board');




/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('/', function () {
    return view('welcome');
});




/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('customers', CustomerController::class);
    // ضع هذا الكود بعد Route::resource('customers', ...);
    Route::post('customers/{customer}/measurements', [MeasurementController::class, 'store'])->name('measurements.store');
    Route::put('measurements/{measurement}', [MeasurementController::class, 'update'])->name('measurements.update');
    Route::delete('measurements/{measurement}', [MeasurementController::class, 'destroy'])->name('measurements.destroy');




    /*
    |----------------------------------------------------------------------
    | Admin Routes
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        // الموارد البشرية
        Route::resource('rewards', RewardController::class);
        Route::resource('deductions', DeductionController::class);
        Route::resource('salaries', SalaryController::class);
        Route::resource('attendance', AttendanceController::class)->only(['index']);
        Route::resource('attendance', AdminAttendanceController::class);

        // المنتجات و المشتريات

        Route::resource('products', ProductController::class);
        Route::resource('product-categories', ProductCategoryController::class);
        // استبدل السطر القديم بهذا الكود
        Route::get('/products/{product}/variants', function (Product $product) {
            return response()->json($product->variants()->with('product')->get());
        });

        Route::resource('purchases', PurchaseOrderController::class)->only(['index', 'create', 'store']);
        // تعرف edit و update بشكل منفصل (لو عايز تعديل مختلف)
        Route::get('purchases/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchases.edit');
        Route::get('purchases/{purchaseOrder}/show', [PurchaseOrderController::class, 'show'])->name('purchases.show');
        Route::put('purchases/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchases.update');
        Route::delete('purchases/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchases.destroy');
        Route::resource('distributions', DistributionController::class)->names('distributions');
        Route::resource('stock_transfers', StockTransferController::class)->except(['edit', 'update']);
        Route::get('/branch-stocks', [BranchStockController::class, 'index'])->name('branch_stocks.index');
        Route::resource('purchase_returns', PurchaseReturnController::class);
        Route::get('/variants-by-product/{productId}', [\App\Http\Controllers\ProductVariantController::class, 'getByProduct']);
        Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
        Route::resource('production_orders', \App\Http\Controllers\ProductionOrderController::class)->only([
        'index', 'create', 'store', 'show'  ]);
        // سجل النشاطات ✅
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');

        // routes/web.php -> inside your admin group
        Route::resource('offers', \App\Http\Controllers\OfferController::class);

    });

         Route::resource('branches', BranchController::class);
        Route::resource('employees', EmployeeController::class);
        // شاشة تقرير الطلبات للموظف
        Route::get('employees/{employee}/orders-report', [EmployeeController::class, 'ordersReport'])
            ->name('employees.orders-report');

        // نسخة للطباعة
        Route::get('employees/{employee}/orders-report/print', [EmployeeController::class, 'ordersReportPrint'])
            ->name('employees.orders-report.print');


        
        // ربط المستخدمين بالموظفين
        Route::get('/users/assign', [UserController::class, 'assignEmployeeForm'])->name('users.assign.form');
        Route::post('/users/assign', [UserController::class, 'assignEmployee'])->name('users.assign');
});



require __DIR__.'/auth.php';
