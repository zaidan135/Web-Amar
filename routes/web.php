<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BookOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\PrintSettingController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('landing-page');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {

    Route::get('/dashboard-auth', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('categories', KategoriController::class);

    Route::resource('products', ProdukController::class);

    Route::get('/report-auth', [AdminReportController::class, 'index'])->name('admin.report');
    Route::get('/admin/reports/print', [AdminReportController::class, 'print'])->name('admin.report.print');

    Route::get('/account',[RegisteredUserController::class, 'create'])->name('account');
    Route::post('/account', [RegisteredUserController::class, 'store']);
    Route::put ('/account/{user}', [RegisteredUserController::class,'update'])->name('account.update');
    Route::delete('/account/{user}', [RegisteredUserController::class,'destroy'])->name('account.destroy');
    
});

    Route::get('/settings/print', function () {
        return view('pages.settings.index');
    })->name('print.setting');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    Route::get('/book-order', [BookOrderController::class, 'index'])->name('book-order.index');


    Route::patch('/book-order/{transaksi}/pay', [BookOrderController::class, 'pay'])->name('book-order.pay');
    
    // UBAH {trx} menjadi {transaksi} agar konsisten
    Route::get('/receipt/{transaksi}.pdf', [BookOrderController::class, 'receiptPdf'])->name('receipt.pdf');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/settings/print', [PrintSettingController::class, 'save'])
          ->name('print.setting.save');
    
});

require __DIR__.'/auth.php';
