<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\TagController;
use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Backend\ProductCategoriesController;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('')->group(function(){
    Route::get('/',[FrontendController::class,'index'])->name('frontend.index');
    Route::get('/cart',[FrontendController::class,'cart'])->name('frontend.cart');
    Route::get('/checkout',[FrontendController::class,'checkout'])->name('frontend.checkout');
    Route::get('/detail',[FrontendController::class,'detail'])->name('frontend.detail');
    Route::get('/shop',[FrontendController::class,'shop'])->name('frontend.shop');
});

Route::prefix('admin')->name('admin.')->group(function(){
    //guest middleware
    Route::middleware(['guest'])->group(function () {
        Route::get('/login',[BackendController::class,'login'])->name('login');
        Route::get('/forget-password',[BackendController::class,'forget_password'])->name('forget_password');
    });

    //admin middleware (roles,role)
    Route::middleware(['roles', 'role:admin|SuperVisor'])->group(function () {
        Route::get('/',[BackendController::class,'index'])->name('index_route');
        Route::get('/index',[BackendController::class,'index'])->name('index');
        Route::post('/product_categories/remove-image', [ProductCategoriesController::class, 'remove_image'])->name('product_categories.remove_image');
        Route::resource('product_categories', ProductCategoriesController::class);
        Route::post('/products/remove-image', [ProductController::class, 'remove_image'])->name('products.remove_image');
        Route::resource('products', ProductController::class);
        Route::resource('tags', TagController::class);
    });

});

// send verify message to email user (user signup new account)
Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
