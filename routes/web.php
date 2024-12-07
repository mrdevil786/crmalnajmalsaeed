<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpendituresController;
use App\Http\Controllers\Admin\InvoicesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ProfilesController;
use App\Http\Controllers\Admin\QuotationsController;

// GUEST ROUTES
Route::prefix('admin')->name('admin.')->middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('view.login');
    Route::post('/login', [AuthController::class, 'login'])->name('submit.login');
});

// AUTHENTICATED ADMIN ROUTES
Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum', 'web', 'checkAdminStatus'])->group(function () {

    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('logout', [AuthController::class, 'logout'])->name('user.logout');

    // QUOTATIONS MANAGEMENT ROUTES
    Route::prefix('quotations')->name('quotations.')->controller(QuotationsController::class)->group(function () {

        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::put('status', 'status')->name('status');
            Route::post('convert/{id}', 'convertToInvoice')->name('convert');
        });

        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::put('update/{id}', 'update')->name('update');
        });

        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('view/{id}', 'view')->name('view');
            Route::get('download/{id}', 'download')->name('download');
            Route::get('stream/{id}', 'stream')->name('stream');
        });
    });

    // INVOICES MANAGEMENT ROUTES
    Route::prefix('invoices')->name('invoices.')->controller(InvoicesController::class)->group(function () {

        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::put('status', 'status')->name('status');
            Route::get('generatePdf/{invoiceId}', 'generatePdf')->name('generatePdf');
        });

        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::put('update/{id}', 'update')->name('update');
        });

        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('view/{id}', 'view')->name('view');
            Route::get('download/{id}', 'download')->name('download');
            Route::get('stream/{id}', 'stream')->name('stream');
        });
    });

    // PRODUCTS MANAGEMENT ROUTES
    Route::prefix('products')->name('products.')->controller(ProductsController::class)->group(function () {

        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::put('status', 'status')->name('status');
        });

        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::put('update/{id}', 'update')->name('update');
        });

        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('view/{id}', 'view')->name('view');
        });
    });

    // CUSTOMER MANAGEMENT ROUTES
    Route::prefix('customers')->name('customers.')->controller(CustomersController::class)->group(function () {

        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::put('status', 'status')->name('status');
        });

        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::put('update/{id}', 'update')->name('update');
        });

        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('view/{id}', 'view')->name('view');
        });
    });

    // EXPENDITURE MANAGEMENT ROUTES
    Route::prefix('expenditures')->name('expenditures.')->controller(ExpendituresController::class)->group(function () {

        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::put('update/{id}', 'update')->name('update');
        });

        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('view/{id}', 'view')->name('view');
        });
    });

    // USER MANAGEMENT ROUTES
    Route::prefix('users')->name('users.')->controller(UsersController::class)->group(function () {

        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::put('status', 'status')->name('status');
        });

        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::put('update/{id}', 'update')->name('update');
        });

        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('view/{id}', 'view')->name('view');
        });
    });

    // PROFILE ROUTES
    Route::prefix('profile')->name('profile.')->controller(ProfilesController::class)->group(function () {

        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('view/{id}', 'view')->name('view');
            Route::post('update', 'updateProfile')->name('update');
            Route::post('update-password', 'updatePassword')->name('update.password');
        });
    });
});


// Route::name('users.')
//     ->prefix('users')
//     ->controller(UsersController::class)->group(function () {
//         Route::get('/', 'index')->name('index');
//         Route::get('blocked', 'index')->name('blocked');
//         Route::get('deleted', 'index')->name('deleted');
//         Route::post('store', 'store')->name('store');
//         Route::get('edit/{id}', "edit")->name('edit');
//         Route::delete('/{id}', 'destroy')->name('destroy');
//         Route::post('update', 'update')->name('update');
//         Route::put('status', 'status')->name('status');
//         Route::get('view/{id}', 'showUser')->name('show');
//     });
