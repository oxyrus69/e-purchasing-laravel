<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoodsReceiptNoteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockRequisitionController;
use App\Http\Controllers\StockAdjustmentController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Lindungi Master Data
    Route::resource('suppliers', SupplierController::class)->middleware('can:manage-suppliers');
    Route::resource('products', ProductController::class)->middleware('can:manage-products');

    // Lindungi Transaksi
    Route::resource('purchase-requests', PurchaseRequestController::class); // Logika di dalam controller/view
    Route::patch('/purchase-requests/{purchaseRequest}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve')->middleware('can:approve-pr');
    Route::patch('/purchase-requests/{purchaseRequest}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject')->middleware('can:reject-pr');

    Route::resource('purchase-orders', PurchaseOrderController::class)->middleware('can:view-po');
    Route::resource('goods-receipt-notes', GoodsReceiptNoteController::class)->names('goods-receipt-notes')->middleware('can:view-grn');

    // Lindungi Manajemen User
    Route::resource('users', UserController::class)->except(['show'])->middleware('can:manage-users');

    // Report
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
  
    // Invoice
    Route::resource('invoices', InvoiceController::class)->middleware('can:manage-invoices');
    Route::patch('/invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.markAsPaid')->middleware('can:mark-invoice-paid');
    
    // Notifications
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    
    // Stock In
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::resource('stocks', StockController::class)->only(['index', 'show'])->middleware('can:view-stock');

    // Stock Out
    Route::resource('stock-requisitions', StockRequisitionController::class);
    Route::patch('/stock-requisitions/{stockRequisition}/approve', [StockRequisitionController::class, 'approve'])
    ->name('stock-requisitions.approve')->middleware('can:approve-stock-requisition');

    // Adjust Real Stock
    Route::get('/stock-adjustments/create', [StockAdjustmentController::class, 'create'])->name('stock-adjustments.create');
    Route::post('/stock-adjustments', [StockAdjustmentController::class, 'store'])->name('stock-adjustments.store');


});

require __DIR__.'/auth.php';
