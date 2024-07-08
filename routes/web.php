<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\RegisterController as AdminRegisterController;
use App\Http\Controllers\Admin\QuoteBackgroundController;
use App\Http\Controllers\User\UserController as UserController;
use App\Http\Controllers\User\QuoteController;
use App\Http\Controllers\Front\FrontController;




// User the login form
Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::get('register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [UserController::class, 'register']);

// Frontend Home Route
Route::get('/', [FrontController::class, 'index'])->name('homepage');



Route::get('/home', function () {
    return view('front.index');
});
// Admin Authentication Routes

Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    // Admin Login Routes
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    // Admin Registration Routes
    Route::get('register', [AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('register', [AdminRegisterController::class, 'register']);

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
        // Add more admin routes here
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
// Default Authentication Routes (for other functionalities)
Auth::routes();

// Home Route for authenticated users
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
