<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\DashboardController;
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

        // ROUTES FOR ADMINS
        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy'); // DELETE INVOICE
            Route::put('status', 'status')->name('status'); // UPDATE INVOICE STATUS
            Route::post('convert/{id}', 'convertToInvoice')->name('convert'); // CONVERT QUOTATION
        });

        // ROUTES FOR MANAGERS
        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create'); // CREATE INVOICE VIEW
            Route::post('store', 'store')->name('store'); // STORE INVOICE
            Route::get('edit/{id}', 'edit')->name('edit'); // EDIT INVOICE VIEW
            Route::put('update/{id}', 'update')->name('update'); // UPDATE INVOICE
        });

        // ROUTES FOR MEMBERS
        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index'); // LIST INVOICES
            Route::get('view/{id}', 'view')->name('view'); // VIEW INVOICE DETAILS
            Route::get('download/{id}', 'download')->name('download'); // DOWNLOAD INVOICE PDF
            Route::get('stream/{id}', 'stream')->name('stream'); // DOWNLOAD INVOICE PDF
        });
    });

    // INVOICES MANAGEMENT ROUTES
    Route::prefix('invoices')->name('invoices.')->controller(InvoicesController::class)->group(function () {

        // ROUTES FOR ADMINS
        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy'); // DELETE INVOICE
            Route::put('status', 'status')->name('status'); // UPDATE INVOICE STATUS
            Route::get('generatePdf/{invoiceId}', 'generatePdf')->name('generatePdf'); // UPDATE INVOICE STATUS
        });

        // ROUTES FOR MANAGERS
        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create'); // CREATE INVOICE VIEW
            Route::post('store', 'store')->name('store'); // STORE INVOICE
            Route::get('edit/{id}', 'edit')->name('edit'); // EDIT INVOICE VIEW
            Route::put('update/{id}', 'update')->name('update'); // UPDATE INVOICE
        });

        // ROUTES FOR MEMBERS
        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index'); // LIST INVOICES
            Route::get('view/{id}', 'view')->name('view'); // VIEW INVOICE DETAILS
            Route::get('download/{id}', 'download')->name('download'); // DOWNLOAD INVOICE PDF
            Route::get('stream/{id}', 'stream')->name('stream'); // DOWNLOAD INVOICE PDF
        });
    });

    // PRODUCTS MANAGEMENT ROUTES
    Route::prefix('products')->name('products.')->controller(ProductsController::class)->group(function () {

        // ROUTES FOR ADMINS
        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy'); // DELETE PRODUCT
            Route::put('status', 'status')->name('status'); // UPDATE PRODUCT STATUS
        });

        // ROUTES FOR MANAGERS
        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create'); // CREATE PRODUCT VIEW
            Route::post('store', 'store')->name('store'); // STORE PRODUCT
            Route::get('edit/{id}', 'edit')->name('edit'); // EDIT PRODUCT VIEW
            Route::put('update/{id}', 'update')->name('update'); // UPDATE PRODUCT
        });

        // ROUTES FOR MEMBERS
        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index'); // LIST PRODUCTS
            Route::get('view/{id}', 'view')->name('view'); // VIEW PRODUCT DETAILS
        });
    });

    // CUSTOMER MANAGEMENT ROUTES
    Route::prefix('customers')->name('customers.')->controller(CustomersController::class)->group(function () {

        // ROUTES FOR ADMINS
        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy'); // DELETE CUSTOMER
            Route::put('status', 'status')->name('status'); // UPDATE CUSTOMER STATUS
        });

        // ROUTES FOR MANAGERS
        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create'); // CREATE CUSTOMER VIEW
            Route::post('store', 'store')->name('store'); // STORE CUSTOMER
            Route::get('edit/{id}', 'edit')->name('edit'); // EDIT CUSTOMER VIEW
            Route::put('update/{id}', 'update')->name('update'); // UPDATE CUSTOMER
        });

        // ROUTES FOR MEMBERS
        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index'); // LIST CUSTOMERS
            Route::get('view/{id}', 'view')->name('view'); // VIEW CUSTOMER DETAILS
        });
    });

    // USER MANAGEMENT ROUTES
    Route::prefix('users')->name('users.')->controller(UsersController::class)->group(function () {

        // ROUTES FOR ADMINS
        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', 'destroy')->name('destroy'); // DELETE USER
            Route::put('status', 'status')->name('status'); // UPDATE USER STATUS
        });

        // ROUTES FOR MANAGERS
        Route::middleware('manager')->group(function () {
            Route::get('create', 'create')->name('create'); // CREATE USER VIEW
            Route::post('store', 'store')->name('store'); // STORE USER
            Route::get('edit/{id}', 'edit')->name('edit'); // EDIT USER VIEW
            Route::put('update/{id}', 'update')->name('update'); // UPDATE USER
        });

        // ROUTES FOR MEMBERS
        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index'); // LIST USERS
            Route::get('view/{id}', 'view')->name('view'); // VIEW USER DETAILS
        });
    });

    // PROFILE ROUTES
    Route::prefix('profile')->name('profile.')->controller(ProfilesController::class)->group(function () {

        // ROUTES FOR MEMBERS
        Route::middleware('member')->group(function () {
            Route::get('/', 'index')->name('index'); // VIEW PROFILE
            Route::get('view/{id}', 'view')->name('view'); // VIEW PROFILE DETAILS
            Route::post('update', 'updateProfile')->name('update'); // UPDATE PROFILE
            Route::post('update-password', 'updatePassword')->name('update.password'); // UPDATE PASSWORD
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
