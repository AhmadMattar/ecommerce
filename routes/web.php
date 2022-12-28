<?php

use App\Http\Controllers\Backend\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\TagController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Backend\SupervisorController;
use App\Http\Controllers\Backend\ProductCouponController;
use App\Http\Controllers\Backend\ProductReviewController;
use App\Http\Controllers\Backend\CustomerAddressController;
use App\Http\Controllers\Backend\PaymentMethodController;
use App\Http\Controllers\Backend\ShippingCompanyController;
use App\Http\Controllers\Backend\ProductCategoriesController;
use App\Http\Controllers\Frontend\CustomerController as FrontendCustomerController;
use App\Http\Controllers\Frontend\PaymentController;

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

//Route::get('/test-pdf', function (){
//
//    $order = Order::with('products', 'user', 'payment_method')->first();
//    $order['currency_symbol'] = $order->currency == 'USD' ? '$' : $order->currency;
//    $data = $order->toArray();
//    $pdf = PDF::loadView('layouts.invoice', $data);
//
//    $saved = storage_path('app/pdf/files/'. $data['ref_id'] . '.pdf');
//    $pdf->save($saved);
//
//    $customer = User::find($order->user_id);
//    $customer->notify(new OrderThanksNotification($order, $saved));
//
//    return 'Email sent';
//});
Route::prefix('')->group(function(){
    Route::get('/',[FrontendController::class,'index'])->name('frontend.index');
    Route::get('/cart',[FrontendController::class,'cart'])->name('frontend.cart');
    Route::get('/wishlist',[FrontendController::class,'wishlist'])->name('frontend.wishlist');
    Route::get('/product/{slug?}',[FrontendController::class,'product'])->name('frontend.product');
    Route::get('/shop/{slug?}',[FrontendController::class,'shop'])->name('frontend.shop');
    Route::get('/shop/tags/{slug}',[FrontendController::class,'shop_tag'])->name('frontend.shop_tag');
});

Route::middleware(['roles', 'role:customer'])->group(function(){
    Route::get('profile', [FrontendCustomerController::class, 'profile'])->name('customer.profile');
    Route::put('profile', [FrontendCustomerController::class, 'update_profile'])->name('customer.update_profile');
    Route::get('profile/remove-image', [FrontendCustomerController::class, 'remove_profile_image'])->name('customer.remove_profile_image');
    Route::get('profile/dashboard', [FrontendCustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('profile/addresses', [FrontendCustomerController::class, 'addresses'])->name('customer.addresses');
    Route::get('profile/orders', [FrontendCustomerController::class, 'orders'])->name('customer.orders');

    Route::middleware('cart_check')->group(function (){
        Route::get('/checkout',[FrontendController::class, 'checkout'])->name('frontend.checkout');
        Route::post('/checkout',[PaymentController::class, 'checkout_now'])->name('frontend.checkout.payment');
        Route::get('/checkout/{order_id}/canceled',[PaymentController::class, 'canceled'])->name('frontend.checkout.cancel');
        Route::get('/checkout/{order_id}/completed',[PaymentController::class, 'completed'])->name('frontend.checkout.complete');
        Route::get('/checkout/webhook/{order?}/{envi?}',[PaymentController::class, 'webhook'])->name('frontend.checkout.webhook.ipn');
    });
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
        Route::get('/account_settings',[BackendController::class,'account_settings'])->name('account_settings');
        Route::post('/remove-image', [BackendController::class, 'remove_image'])->name('remove_image');
        Route::put('/account_settings',[BackendController::class,'update_account_settings'])->name('update_account_settings');



        Route::post('/product_categories/remove-image', [ProductCategoriesController::class, 'remove_image'])->name('product_categories.remove_image');
        Route::resource('product_categories', ProductCategoriesController::class);
        Route::post('/products/remove-image', [ProductController::class, 'remove_image'])->name('products.remove_image');
        Route::resource('products', ProductController::class);
        Route::resource('tags', TagController::class);
        Route::resource('product_coupons', ProductCouponController::class);
        Route::resource('product_reviews', ProductReviewController::class);

        Route::post('/customers/remove-image', [CustomerController::class, 'remove_image'])->name('customers.remove_image');
        Route::get('/customers/get-customers', [CustomerController::class, 'get_customers'])->name('customers.get_customers');
        Route::resource('customers', CustomerController::class);

        Route::resource('customer_addresses', CustomerAddressController::class);
        Route::resource('orders', OrderController::class);
        Route::post('/supervisors/remove-image', [SupervisorController::class, 'remove_image'])->name('supervisors.remove_image');
        Route::resource('supervisors', SupervisorController::class);

        Route::resource('countries', CountryController::class);
        Route::get('/states/get-states', [StateController::class, 'get_states'])->name('states.get_states');
        Route::resource('states', StateController::class);
        Route::get('/cities/get-cities', [CityController::class, 'get_cities'])->name('cities.get_cities');
        Route::resource('cities', CityController::class);

        Route::resource('shipping_companies', ShippingCompanyController::class);

        Route::resource('payment_methods', PaymentMethodController::class);

    });

});

// send verify message to email user (user signup new account)
Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
