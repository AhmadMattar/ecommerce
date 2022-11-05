<?php
namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductCoupon;
use App\Models\OrderTransaction;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderService {

    public function createOrder($request)
    {
        $order = Order::create([
            'ref_id'               => 'ORD-' . Str::random(15),
            'user_id'              => auth()->id(),
            'user_address_id'      => $request['customer_address_id'],
            'shipping_company_id'  => $request['shipping_company_id'],
            'payment_method_id'    => $request['payment_method_id'],
            'subtotal'             => getNumbers()->get('subtotal'),
            'discount_code'        => session()->has('coupon') ? session()->get('coupon')['code'] : null,
            'discount'             => getNumbers()->get('discount'),
            'shipping'             => getNumbers()->get('shipping'),
            'tax'                  => getNumbers()->get('productTaxes'),
            'total'                => getNumbers()->get('total'),
            'currency'             => 'USD',
            'order_status'         => 0,
        ]);

        foreach (Cart::content() as $item) {
            DB::table('order_product')->insert([
                'order_id'   => $order->id,
                'product_id' => $item->model->id,
                'quantity'   => $item->qty,
            ]);

            $product = Product::find($item->model->id);
            $product->update([
                'quantity' => $product->quantity - $item->qty,
            ]);
        }

        $order->transactions()->create([
            'transaction'   => OrderTransaction::NEW_ORDER,
        ]);

        return $order;
    }
}

