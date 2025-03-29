<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\QuoteBackgroundController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\QuoteController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\NotificationController;

// -----------------------
// Frontend Routes
// -----------------------

Route::get('/', [FrontController::class, 'index'])->name('homepage');

Route::get('/home', function () {
    return view('front.index');
});

// -----------------------
// User Authentication Routes
// -----------------------

Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('user/login', [UserController::class, 'login'])->name('user.login');
Route::post('logout', [UserController::class, 'logout'])->name('logout');

Route::get('user/register/form', [UserController::class, 'showRegistrationForm'])->name('user.register');
Route::post('user/register', [UserController::class, 'register'])->name('user-register');

// -----------------------
// User Functionalities
// -----------------------

Route::middleware(['auth'])->group(function () {
    Route::get('create-quote', [QuoteController::class, 'create'])->name('create.quote');
    Route::post('store-quote-text', [QuoteController::class, 'storeQuoteText'])->name('store.quote.text');
    Route::get('select-background', [QuoteController::class, 'selectBackground'])->name('select.background');
    Route::post('store-quote', [QuoteController::class, 'storeQuote'])->name('quotes.store');
    Route::get('/quotes/delete/{id}', [QuoteController::class, 'delete'])->name('quote.delete');
    Route::get('/quotes/{id}/download', [QuoteController::class, 'download'])->name('quotes.download');
    Route::post('/quotes/{id}/like', [QuoteController::class, 'like'])->name('quotes.like');
    
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{id}/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/view/{id}', [UserProfileController::class, 'profileview'])->name('profile.view');
    Route::post('/profile/{id}/follow', [UserProfileController::class, 'follow'])->name('profile.follow');
    Route::post('profile/{id}/unfollow', [UserProfileController::class, 'unfollow'])->name('profile.unfollow');
});

// -----------------------
// Frontend Quote Routes
// -----------------------

Route::get('quotes/{tag}', [FrontController::class, 'searchquotes'])->name('searchquotes');
Route::get('quote/{id}', [FrontController::class, 'quote'])->name('quote');
Route::get('/tags/suggest', [FrontController::class, 'suggest'])->name('tags.suggest');

Route::post('/upload-captured-image', [FrontController::class, 'uploadCapturedImage']);
Route::post('/download-captured-image', [FrontController::class, 'downloadCapturedImage'])->name('download.captured.image');

Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

// -----------------------
// Admin Authentication Routes
// -----------------------

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth' ,'admin'])->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // -----------------------
        // Admin User Management
        // -----------------------

        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/datatables', [AdminUserController::class, 'datatables'])->name('admin.users.datatables');
        Route::get('/user/block', [AdminUserController::class, 'block'])->name('admin.user.block');
        Route::get('/user/edit', [AdminUserController::class, 'edit'])->name('admin.user.edit');

        // -----------------------
        // Quote Background Management
        // -----------------------

        Route::prefix('quote-backgrounds')->name('admin.quote.background.')->group(function () {
            Route::get('/', [QuoteBackgroundController::class, 'index'])->name('index');
            Route::get('/create', [QuoteBackgroundController::class, 'create'])->name('create');
            Route::post('/', [QuoteBackgroundController::class, 'store'])->name('store');
            Route::get('/{id}', [QuoteBackgroundController::class, 'show'])->name('show');
            Route::delete('/{id}', [QuoteBackgroundController::class, 'destroy'])->name('destroy');
        });

        // -----------------------
        // Tags Management
        // -----------------------

        Route::prefix('tags')->name('admin.tags.')->group(function () {
            Route::get('/', [TagsController::class, 'index'])->name('index');
            Route::get('/create', [TagsController::class, 'create'])->name('create');
            Route::post('/store', [TagsController::class, 'store'])->name('store');
            Route::delete('/{id}', [TagsController::class, 'destroy'])->name('destroy');
            Route::put('/{id}', [TagsController::class, 'update'])->name('update');
        });

        // -----------------------
        // Settings & Media
        // -----------------------

        Route::get('/media', [SettingController::class, 'media'])->name('admin.media');
        Route::post('/media/update', [SettingController::class, 'updatemedia'])->name('admin.media.update');

        // -----------------------
        // Category Management
        // -----------------------

        Route::prefix('categories')->name('admin.categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });
    });
});

// -----------------------
// Authentication Routes
// -----------------------

Auth::routes();
