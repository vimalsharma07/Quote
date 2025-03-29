<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\RegisterController as AdminRegisterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\QuoteBackgroundController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\User\UserController as UserController;
use App\Http\Controllers\User\QuoteController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\UserProfileController;

use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\NotificationController;

// Frontend Home Route
Route::get('/', [FrontController::class, 'index'])->name('homepage');
Route::get('/home', fn() => view('front.index'));

// User Authentication Routes
Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('user/login', [UserController::class, 'login'])->name('user.login');
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::get('user/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('user/register', [UserController::class, 'register'])->name('user-register');

// Quote Routes
Route::get('quotes/{tag}', [FrontController::class, 'searchquotes'])->name('searchquotes');
Route::get('quote/{id}', [FrontController::class, 'quote'])->name('quote');
Route::get('/tags/suggest', [FrontController::class, 'suggest'])->name('tags.suggest');
Route::post('/upload-captured-image', [FrontController::class, 'uploadCapturedImage']);
Route::post('/download-captured-image', [FrontController::class, 'downloadCapturedImage'])->name('download.captured.image');

// Notifications
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    // Admin Login Routes
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    // Admin Dashboard Routes (Protected by auth & admin middleware)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

        // Quote Background Management
        Route::prefix('quote-backgrounds')->group(function () {
            Route::get('/', [QuoteBackgroundController::class, 'index'])->name('admin.quote.background.index');
            Route::get('create', [QuoteBackgroundController::class, 'create'])->name('admin.quote.background.create');
            Route::post('/', [QuoteBackgroundController::class, 'store'])->name('quote_backgrounds.store');
            Route::get('{id}', [QuoteBackgroundController::class, 'show'])->name('admin.quote.background.show');
            Route::delete('{id}', [QuoteBackgroundController::class, 'destroy'])->name('admin.quote.background.destroy');
        });

        // User Management
        Route::prefix('users')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('admin-user-index');
            Route::get('/datatables', [AdminUserController::class, 'datatables'])->name('admin-user-datatables');
            Route::get('/block', [AdminUserController::class, 'block'])->name('admin-user-block');
            Route::get('/edit', [AdminUserController::class, 'edit'])->name('admin-user-edit');
        });

        // Tag Management
        Route::prefix('tags')->group(function () {
            Route::get('/', [TagsController::class, 'index'])->name('admin-tags-index');
            Route::get('create', [TagsController::class, 'create'])->name('admin-tags-create');
            Route::post('store', [TagsController::class, 'store'])->name('admin.tags.store');
            Route::delete('tag/{id}', [TagsController::class, 'destroy'])->name('admin.tags.destroy');
            Route::put('tag/{id}', [TagsController::class, 'update'])->name('admin.tags.update');
        });

        // Setting Controller
        Route::prefix('media')->group(function () {
            Route::get('/', [SettingController::class, 'media'])->name('admin-media');
            Route::post('update', [SettingController::class, 'updatemedia'])->name('admin-media-update');
        });

        // Category Management
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

// Quote Creation Routes
Route::get('create-quote', [QuoteController::class, 'create'])->name('create.quote');
Route::post('store-quote-text', [QuoteController::class, 'storeQuoteText'])->name('store.quote.text');
Route::get('select-background', [QuoteController::class, 'selectBackground'])->name('select.background');
Route::post('store-quote', [QuoteController::class, 'storeQuote'])->name('quotes.store');

// Fetch Quote Backgrounds
Route::get('backgrounds', fn() => response()->json(['backgrounds' => App\Models\QuoteBackground::all()]))->name('backgrounds.index');

// Quote Actions
Route::get('quotes/delete/{id}', [QuoteController::class, 'delete'])->name('quote.delete');
Route::get('/quotes/{id}/download', [QuoteController::class, 'download'])->name('quotes.download');
Route::post('/quotes/{id}/like', [QuoteController::class, 'like'])->name('quotes.like');

// Frontend User Quotes
Route::get('liked-quotes', [FrontController::class, 'likedquotes'])->name('liked-quotes');
Route::get('your-quotes', [FrontController::class, 'yourquotes'])->name('your-quotes');

// Comments
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

// User Profile Routes
Route::prefix('profile')->group(function () {
    Route::get('/', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('{id}/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('{id}', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('view/{id}', [UserProfileController::class, 'profileview'])->name('profile.view');
    Route::post('{id}/follow', [UserProfileController::class, 'follow'])->name('profile.follow');
    Route::post('{id}/unfollow', [UserProfileController::class, 'unfollow'])->name('profile.unfollow');
});

// Default Authentication Routes
Auth::routes();
