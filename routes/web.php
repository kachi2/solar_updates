<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubcategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
| URLs and route names are kept from the old sofars app so the views work.
*/

Route::prefix('admin')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'create'])->name('admin-login');
        Route::post('/manage/user/login', [AdminLoginController::class, 'store'])->middleware('throttle:login')->name('admin.login.submit');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('admin.logout');

        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/index', [AdminController::class, 'index']);

        // Catalogue
        Route::resource('category', CategoryController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::post('/category/status/{category}', [CategoryController::class, 'status'])->name('category.status');
        Route::get('/subcategory/index/{id}', [SubcategoryController::class, 'index'])->name('index.subcat');
        Route::get('/subcategory/create/{id}', [SubcategoryController::class, 'create'])->name('create.subcat');
        Route::post('/subcategory/store/{id}', [SubcategoryController::class, 'store'])->name('store.subcat');
        Route::get('/subcategory/edit/{id}', [SubcategoryController::class, 'edit'])->name('edit.subcat');
        Route::post('/subcategory/update/{id}', [SubcategoryController::class, 'update'])->name('update.subcat');

        Route::resource('product', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::post('/product/status/{id}', [ProductController::class, 'status'])->name('product.status');
        Route::get('/product/variation/{id}', [ProductController::class, 'variation'])->name('product.variation');
        Route::get('/variation/edit/{id}', [ProductController::class, 'variationEdit'])->name('variation.edit');
        Route::post('/variation/update/{id}', [ProductController::class, 'variationUpdate'])->name('variation.update');
        Route::post('/update/rates', [AdminController::class, 'updateExchangeRate'])->name('updateDollarRate');

        // Sales
        Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::get('/orders/details/{id}', [AdminController::class, 'orderDetails'])->name('admin.order-details');
        Route::get('/order/shipping/{id}', [AdminController::class, 'shipping'])->name('admin.shipping');
        Route::get('/order/status/{id}', [AdminController::class, 'status'])->name('order.status');
        Route::post('/status/update/{id}', [AdminController::class, 'updateStatus'])->name('order.status.update');
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transctions');
        Route::get('/transaction/details/{id}', [AdminController::class, 'transactions'])->name('admin.transactions-details');
        Route::get('/designs/download/{id}', [AdminController::class, 'download'])->name('design.download');

        // Customers & notifications
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/order/{id}', [AdminController::class, 'userOrders'])->name('admin.user-orders');
        Route::get('/admin/users/transaction/{id}', [AdminController::class, 'userTransactions'])->name('admin.user-transactions');
        Route::get('/users/notify', [AdminController::class, 'notify'])->name('admin.notify');
        Route::post('/users/notification', [AdminController::class, 'pushNotify'])->name('admin.notify.send');
        Route::get('/notify/{id}', [AdminController::class, 'markNotificationRead'])->name('update.admin-notify');
        Route::post('/notification/clear', [AdminController::class, 'clearNotifications'])->name('admin.notify.clear');
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytical');

        // Admin profile
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

        // Site content
        Route::get('/menu/index', [PagesController::class, 'menuIndex'])->name('admin.MenuIndex');
        Route::get('/add/menu', [PagesController::class, 'addMenu'])->name('admin.addMenu');
        Route::post('/add/create', [PagesController::class, 'createMenu'])->name('admin.menu.store');
        Route::get('/menu/edit/{id}', [PagesController::class, 'editMenu'])->name('menuEdit');
        Route::post('/menu/update/{id}', [PagesController::class, 'updateMenu'])->name('admin.menu.update');

        Route::get('/slider/index', [PagesController::class, 'sliderIndex'])->name('slider.index');
        Route::get('/slider/create', [PagesController::class, 'createSlider'])->name('slider.create');
        Route::post('/slider/store', [PagesController::class, 'storeSlider'])->name('slider.store');
        Route::get('/slider/delete/{id}', [PagesController::class, 'deleteSlider'])->name('slider.delete');

        Route::get('/news/index', [NewsController::class, 'index'])->name('admin.news.index');
        Route::get('/news/create', [NewsController::class, 'create'])->name('admin.news.create');
        Route::post('/news/store', [NewsController::class, 'store'])->name('admin.news.store');
        Route::get('/news/edit/{id}', [NewsController::class, 'edit'])->name('admin.edit.news');
        Route::post('/news/update/{id}', [NewsController::class, 'update'])->name('admin.news.update');
        Route::post('/news/status/{id}', [NewsController::class, 'status'])->name('news.status');

        Route::get('/projects', [AdminController::class, 'projects'])->name('admin.project.index');
        Route::get('/project/create', [AdminController::class, 'createProject'])->name('admin.project.create');
        Route::post('/project/store', [AdminController::class, 'storeProject'])->name('admin.project.store');
        Route::get('/project/edit/{id}', [AdminController::class, 'editProject'])->name('admin.project.edit');
        Route::post('/project/update/{id}', [AdminController::class, 'updateProject'])->name('admin.project.Update');
        Route::get('/project/delete/{id}', [AdminController::class, 'deleteProject'])->name('admin.project.delete');
    });
});

/*
|--------------------------------------------------------------------------
| Shop (public)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/product/{product:slug}', [HomeController::class, 'productDetails'])->middleware('throttle:product')->name('product-details');
// Old encrypted-id links (already shared/bookmarked) redirect permanently to the slug URL.
Route::get('/products/details/{id}', [HomeController::class, 'legacyProductDetails'])->name('product-details.legacy');
// /category/{slug}; old encrypted-id links on the same path redirect to the slug (see HomeController::category).
Route::get('/category/{category}', [HomeController::class, 'category'])->name('user.category');
Route::get('/page/search', [HomeController::class, 'search'])->name('search');
// At most 5 reviews per visitor per hour (anti-spam).
Route::post('/add-review/{product}', [HomeController::class, 'addReview'])->middleware('throttle:5,60')->name('review.store');

Route::get('/pages/{slug}', [PagesController::class, 'show'])->name('pages');
// Old encrypted-id links redirect permanently to the slug URL (declared first: more specific path).
Route::get('/news/details/{id}', [PagesController::class, 'legacyNewsDetails'])->name('news.details.legacy');
Route::get('/news/{news:slug}', [PagesController::class, 'newsDetails'])->name('news.details');
Route::get('/project/{project:slug}', [PagesController::class, 'projectsDetails'])->name('projects.details');
// Old encrypted-id links redirect permanently to the slug URL.
Route::get('/projects/details/{id}', [PagesController::class, 'legacyProjectDetails'])->name('projects.details.legacy');

Route::get('/cart/{id}', [CartController::class, 'add'])->name('carts.add');
Route::get('/delete/{id}', [CartController::class, 'remove'])->name('carts.delete');
Route::resource('carts', CartController::class)->only(['index', 'update', 'destroy']);

/*
|--------------------------------------------------------------------------
| Customer account & checkout
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::resource('checkout', CheckoutController::class)->only(['index', 'store', 'update']);
    Route::post('/newaddress', [CheckoutController::class, 'storeAddress'])->name('checkout.address');
    Route::get('/address/checkout', [CheckoutController::class, 'addNew'])->name('checkout.addNew');
    Route::post('/checkouts', [CheckoutController::class, 'add'])->name('checkout.add');
    Route::get('/payment/{id}', [CheckoutController::class, 'verify'])->name('verify.pay');
    Route::get('/confirm/payment/{id}', [CheckoutController::class, 'verify'])->name('confirm.payment');

    Route::get('/user/account', [HomeController::class, 'account'])->name('users.account');
    Route::get('/user/details', [HomeController::class, 'userDetails'])->name('user.account.details');
    Route::post('/users/account/details', [HomeController::class, 'updateDetails'])->name('update.user-details');
    Route::get('/user/orders', [HomeController::class, 'myOrders'])->name('users.orders');
    Route::get('/users/order/details/{id}', [HomeController::class, 'orderDetails'])->name('users.order-details');
    Route::get('/user/transactions', [HomeController::class, 'myTransactions'])->name('user.transactions');
    Route::get('/user/recent/views', [HomeController::class, 'recentViews'])->name('users.recentViews');
    Route::get('/user/notify', [HomeController::class, 'notifications'])->name('user.notification');
    Route::get('/user/notify/delete/{id}', [HomeController::class, 'deleteNotification'])->name('notify.delete');

    Route::get('/user/addresses', [HomeController::class, 'addresses'])->name('users.address');
    Route::get('/user-address', [HomeController::class, 'addresses'])->name('users.user-address');
    Route::get('/add-address', [HomeController::class, 'createAddress'])->name('users.add-address');
    Route::get('/update-address/{id}', [HomeController::class, 'editAddress'])->name('users.update-address');
    Route::post('/address/store/{id}', [HomeController::class, 'saveAddress'])->name('address.store');
    Route::get('/address/delete/{id}', [HomeController::class, 'deleteAddress'])->name('address.delete');
    Route::post('/address/default/{id}', [HomeController::class, 'setDefaultAddress'])->name('address.default');
});

// Customer login, registration, password reset and email verification (Breeze).
require __DIR__.'/auth.php';
