<?php
// General Helper

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Cache;

// get the parent show page
function getParentShowOf($parameter){
    // delete "admin." from the route name ($parameter)
    $route = str_replace('admin.', '', $parameter);
    /*
        get the permission for the "admin_side_menu" from the cache or model:
        we use here the cache becuse we caching it in the ViewServiceProvider
    */
    $permission = collect(Cache::get('admin_side_menu')->pluck('children')->flatten())
        ->where('as', $route)->flatten()->first();

    return $permission ? $permission['parent_show'] : $route;
}
// get the secondary permission
function getParentOf($parameter){
    $route = str_replace('admin.', '', $parameter);
    $permission = collect(Cache::get('admin_side_menu')->pluck('children')->flatten())
        ->where('as', $route)->flatten()->first();
    // if the permission is found return the parent of it
    return $permission ? $permission['parent'] : $route;
}

function getParentIdOf($parameter){
    $route = str_replace('admin.', '', $parameter);
    $permission = collect(Cache::get('admin_side_menu')->pluck('children')->flatten())
        ->where('as', $route)->flatten()->first();
    return $permission ? $permission['id'] : null;
}

function getNumbers()
{
    $subtotal = Cart::instance('default')->subtotal();
    $discount = session()->has('coupon') ? session()->get('coupon')['discount'] : 0.00;
    $coupon_code = session()->has('coupon') ? session()->get('coupon')['code'] : null;
    $subtotalAfterDicount = $subtotal - $discount;

    $tax = config('cart.tax') / 100;
    $taxText = config('cart.tax') . '%';

    $productTaxes = round($subtotalAfterDicount * $tax, 2);
    $newSubtotal = $subtotalAfterDicount + $productTaxes;

    $shipping = session()->has('shipping') ? session()->get('shipping')['cost'] : 0.00;
    $shipping_code = session()->has('shipping') ? session()->get('shipping')['code'] : null;
    $total = ($newSubtotal + $shipping) > 0 ? round($newSubtotal + $shipping, 2) : 0.00;

    return collect([
        'subtotal' => $subtotal,
        'discount' => (float) $discount,
        'coupon_code' => $coupon_code,
        'tax' => $tax,
        'taxText' => $taxText,
        'productTaxes' => (float) $productTaxes,
        'newSubtotal' => (float) $newSubtotal,
        'shipping' => (float) $shipping,
        'shipping_code' => $shipping_code,
        'total' => (float) $total,
    ]);
}
