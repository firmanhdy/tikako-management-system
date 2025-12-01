<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

// ====================================================
// Public Routes (Customers)
// ====================================================

// Home & Menu Pages
Route::get('/', [CustomerMenuController::class, 'index'])->name('beranda');
Route::get('/menu', [CustomerMenuController::class, 'menuPage'])->name('menu.indexPage');
Route::get('/menu/{menu}', [CustomerMenuController::class, 'show'])->name('menu.show');

// Static Pages
Route::get('/tentang', [CustomerMenuController::class, 'about'])->name('tentang');
Route::get('/kontak', [CustomerMenuController::class, 'contact'])->name('kontak');

// Authentication: Login & Register
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [LoginController::class, 'login']); 
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/register', [RegisterController::class, 'register']); 
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// ====================================================
// Customer Routes (Authenticated)
// ====================================================
Route::middleware('auth')->group(function () {
    
    // Profile & Orders
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.myOrders');
    Route::post('/orders/repeat/{order}', [OrderController::class, 'repeatOrder'])->name('orders.repeat');
    Route::get('/profile', [OrderController::class, 'profile'])->name('profile');
    Route::post('/profile', [OrderController::class, 'updateProfile'])->name('profile.update');
    Route::post('/feedback', [CustomerMenuController::class, 'storeFeedback'])->name('feedback.store');
    
    // Direct Checkout
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Shopping Cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::delete('/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::patch('/update-note/{cartItem}', [CartController::class, 'updateNote'])->name('cart.updateNote');
        
        // Checkout process route alias
        Route::post('/process', [CartController::class, 'checkout'])->name('cart.checkout');
    });

    // Success Page
    Route::get('/order/success', [OrderController::class, 'success'])->name('order.success');
});


// ====================================================
// Admin Routes
// ====================================================

// Admin Login
Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin']);
Route::post('/admin/logout', [LoginController::class, 'adminLogout'])->name('admin.logout');

// Dashboard & Management (Requires Admin Role)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminOrderController::class, 'dashboard'])->name('admin.dashboard');
    
    // Menu Management
    Route::resource('menu', MenuController::class); 
    Route::post('menu/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menu.toggle-status');
    
    // Order Management
    Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::post('/pesanan/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/orders/{order}/print/{type}', [AdminOrderController::class, 'printStruk'])->name('admin.orders.print');
    
    // Reports
    Route::get('/reports', [AdminOrderController::class, 'reportsIndex'])->name('admin.reports.index');
    Route::get('/reports/print', [AdminOrderController::class, 'printReport'])->name('admin.reports.print');
    
    // Customer Data
    Route::get('/customers', [AdminOrderController::class, 'customersIndex'])->name('admin.customers.index');
    Route::delete('/customers/{user}', [AdminOrderController::class, 'destroyCustomer'])->name('admin.customers.destroy');
    
    // Feedback
    Route::get('/feedback', [AdminOrderController::class, 'feedbackIndex'])->name('admin.feedback.index');
    Route::delete('/feedback/{feedback}', [AdminOrderController::class, 'feedbackDestroy'])->name('admin.feedback.destroy');
    
    // Account Settings
    Route::get('/password', [AdminOrderController::class, 'showChangePasswordForm'])->name('admin.password');
    Route::post('/password', [AdminOrderController::class, 'updatePassword'])->name('admin.password.update');
    
    // QR Code Generator
    Route::get('/qrcode', [AdminOrderController::class, 'qrCodeIndex'])->name('admin.qrcode.index');
    Route::post('/qrcode/print', [AdminOrderController::class, 'qrCodePrint'])->name('admin.qrcode.print');
});