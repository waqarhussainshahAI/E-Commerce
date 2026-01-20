<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QueryController;
use App\Models\User;
use App\Notifications\SimpleNotification;
use Illuminate\Support\Facades\Route;

// dashboard pages

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');

// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::fallback(function () {
    echo 'not found';
});

Route::prefix('admin')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
        Route::get('/register', [AuthController::class, 'showRegister'])->name('admin.register');
        Route::post('/register', [AuthController::class, 'register'])->name('admin.register.submit');
        Route::post('/sendResetLink', [AuthController::class, 'sendResetLink'])->name('admin.password.resetLink');
        Route::get('/forgotPassword', [AuthController::class, 'showForgotPasswordForm'])->name('admin.password.forgot');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('admin.password.update');
    });

    Route::middleware('auth:admin')->group(function () {

        // Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
        Route::post('/categories/store', [CategoryController::class, 'addCategory'])->name('categories.store');
        Route::get('/category', [CategoryController::class, 'allCategory'])->name('category');
        Route::patch('/categories/{id}',
            [CategoryController::class, 'updateCategory']
        )->name('categories.update');
        Route::delete('/categories/{id}/delete', [CategoryController::class, 'delete'])->name('categories.delete');

        Route::get('/product', [ProductController::class, 'getAll'])->name('product');
        Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
        Route::patch('/product/{id}',
            [ProductController::class, 'updateProduct']
        )->name('product.update');
        Route::delete('/product/{id}',
            [ProductController::class, 'delete']
        )->name('product.delete');
        Route::post('/password/update', [AuthController::class, 'passwordUpdate'])->name('admin.updatePassword');
        Route::get('/orders', [OrderController::class, 'allOrder'])->name('order');
        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        Route::get('/query/page', [QueryController::class, 'index']);
        Route::post('/query', [QueryController::class, 'query'])->name('query');

        Route::get('/order/pdf/{order}', [OrderController::class, 'generateOrderPdf'])->name('order.pdf');

        // discount route
        Route::get('/create-discount-offer', [DiscountController::class, 'showCreateDiscountForm'])->name('create-discount-offer');
        Route::post('/create-discount-offer', [DiscountController::class, 'storeDiscountOffer'])->name('create-discount-offer.store');
        Route::get('/discounts', [DiscountController::class, 'getDiscountedProducts'])->name('discounts');
        Route::post('/discounts/{discount}/toggle-status', [DiscountController::class, 'toggleDiscountStatus'])->name('discounts.toggleStatus');
        Route::get('/discounts/{discount}/delete', [DiscountController::class, 'deleteDiscountOffer'])->name('discounts.delete');
        Route::get('/discounts/deactivate-all', [DiscountController::class, 'allDiscountsDeactivated'])->name('discounts.deactivateAll');
        Route::get('/discounts/edit/{discount}', [DiscountController::class, 'editDiscountOffer'])->name('discounts.edit');
        Route::post('/discounts/update/{discount}', [DiscountController::class, 'updateDiscountOffer'])->name('discounts.update');
    });
});

Route::get('/admin/dashboard', [DashboardController::class, 'stats'])->name('dashboard');

// testing stats
Route::get('/stats', [DashboardController::class, 'stats']);

// testing notifcations
Route::get('/test-notification', function () {
    $user = User::find(2); // first user in DB
    $user->notify(new SimpleNotification);

    return 'Notification sent';
});
