<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\RegisterController as AdminRegisterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\QuoteBackgroundController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\SettingController;


use App\Http\Controllers\User\UserController as UserController;
use App\Http\Controllers\User\QuoteController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\Auth;




Route::get('/', [FrontController::class, 'index'])->name('homepage');
Route::get('/home', function () {
    return view('front.index');
});

// User the login form
Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::get('user/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('user/register', [UserController::class, 'register'])->name('user-register');

// Frontend Home Route

Route::get('quotes/{tag}', [FrontController::class, 'searchquotes'])->name('searchquotes');
Route::get('/tags/suggest', [FrontController::class, 'suggest'])->name('tags.suggest');
Route::post('/upload-captured-image', [FrontController::class, 'uploadCapturedImage']);





// Admin Authentication Routes

Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    // Admin Login Routes
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    // Admin Registration Routes
    // Route::get('register', [AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register');
    // Route::post('register', [AdminRegisterController::class, 'register']);

    // Admin Dashboard Routes
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Quote Background Routes
        Route::get('quote/background', [QuoteBackgroundController::class, 'index'])->name('admin.quote.background.index');
        Route::get('quote-backgrounds/create', [QuoteBackgroundController::class, 'create'])->name('admin.quote.background.create');
        Route::post('quote-backgrounds', [QuoteBackgroundController::class, 'store'])->name('quote_backgrounds.store');
        Route::get('quote/background/{id}', [QuoteBackgroundController::class, 'show'])->name('admin.quote.background.show');
        Route::delete('quote/background/{id}', [QuoteBackgroundController::class, 'destroy'])->name('admin.quote.background.destroy');
        // User in admin
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin-user-index');
        Route::get('/users/datatables', [AdminUserController::class, 'datatables'])->name('admin-user-datatables');
        Route::get('/user/block', [AdminUserController::class, 'block'])->name('admin-user-block');
        Route::get('/user/edit', [AdminUserController::class, 'edit'])->name('admin-user-edit');

        // Tags are here in admin 

        Route::get('/tags', [TagsController::class, 'index'])->name('admin-tags-index');
        Route::get('/tag/create', [TagsController::class, 'create'])->name('admin-tags-create');
        Route::post('/store', [TagsController::class, 'store'])->name('admin.tags.store');
//  Setting Controller

Route::get('/media', [SettingController::class, 'media'])->name('admin-media');
Route::post('/media/update', [SettingController::class, 'updatemedia'])->name('admin-media-update');



    });
});

Route::get('create-quote', [QuoteController::class, 'create'])->name('create.quote');
Route::post('store-quote-text', [QuoteController::class, 'storeQuoteText'])->name('store.quote.text');
Route::get('select-background', [QuoteController::class, 'selectBackground'])->name('select.background');
Route::post('store-quote', [QuoteController::class, 'storeQuote'])->name('quotes.store');
Route::get('backgrounds', function () {
    return response()->json([
        'backgrounds' => App\Models\QuoteBackground::all()
    ]);
})->name('backgrounds.index');

// In web.php
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::get('/quotes/{id}/download', [QuoteController::class, 'download'])->name('quotes.download');
Route::post('/quotes/{id}/like', [QuoteController::class, 'like'])->name('quotes.like');
Route::get('liked-quotes', [FrontController::class, 'likedquotes'])->name('liked-quotes');
Route::get('your-quotes', [FrontController::class, 'yourquotes'])->name('your-quotes');


// User Profile Controller

Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/{id}/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/{id}', [UserProfileController::class, 'update'])->name('profile.update');
Route::get('/profile/view/{id}', [UserProfileController::class, 'profileview'])->name('profile.view');
Route::post('/profile/{id}/follow', [UserProfileController::class, 'follow'])->name('profile.follow');
Route::post('profile/{id}/unfollow', [UserProfileController::class, 'unfollow'])->name('profile.unfollow');

// Default Authentication Routes (for other functionalities)
Auth::routes();

// Home Route for authenticated users
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
