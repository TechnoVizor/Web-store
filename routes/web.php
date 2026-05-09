<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\WishlistController;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Orders;
use App\Livewire\Admin\Products;
use App\Livewire\Admin\Users;
use App\Livewire\Checkout;
use App\Livewire\Labs;
use App\Livewire\StoreIndex;
use Illuminate\Support\Facades\Route;

// --- PUBLIC_ACCESS (Доступно всем) ---
Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, config('app.supported_locales', []), true), 404);

    session(['locale' => $locale]);
    app()->setLocale($locale);

    return back();
})->name('language.switch');

Route::get('/', StoreIndex::class)->name('home'); // Оставляем только Livewire версию главной
Route::get('/product/{slug}', [StoreController::class, 'show'])->name('product.show');
Route::view('/about', 'pages.about')->name('about');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::get('/labs', Labs::class)->name('labs');

// --- AUTH_REQUIRED (Только для залогиненных юзеров) ---
Route::middleware('auth')->group(function () {

    // Профиль и настройки (Volt)
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Корзина
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Оформление заказа
    Route::get('/checkout', Checkout::class)->name('checkout.index');

    // История заказов
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// --- AUTH_SYSTEM (Логин / Разлогин) ---
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'callback']);
Route::post('logout', [GoogleController::class, 'logout'])->name('logout');

// --- ADMIN_PROTOCOL (Только для тех, у кого is_admin = 1) ---
// Мы добавили 'admin' в middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', Dashboard::class)->name('admin.dashboard');
    Route::get('/products', Products::class)->name('admin.products');
    Route::get('/categories', Categories::class)->name('admin.categories');
    Route::get('/orders', Orders::class)->name('admin.orders');

    // НАШ НОВЫЙ РОУТ ДЛЯ УПРАВЛЕНИЯ ЮЗЕРАМИ
    Route::get('/users', Users::class)->name('admin.users');
});
