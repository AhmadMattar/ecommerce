<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCoupon;
use App\Services\OrderService;
use App\Services\OmnipayService;
use App\Http\Controllers\Controller;
use App\Models\OrderTransaction;
use Gloudemans\Shoppingcart\Facades\Cart;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentController extends Controller
{
    public function checkout_now(Request $request)
    {
        $order = (new OrderService)->createOrder($request->except(['_token', 'submit']));

        $omniPay = new OmnipayService('PayPal_Express');

        $response = $omniPay->purchase([
            'amount'        => $order->total,
            'transactionId' => $order->id,
            'currency'      => $order->currency,
            'cancelUrl'     => $omniPay->getCancelUrl($order->id),
            'returnUrl'     => $omniPay->getReturnUrl($order->id),
        ]);

        if($response->isRedirect()) {
            $response->redirect();
        }

        Alert::toast($response->getMessage(),'success');
        return redirect()->route('frontend.index');

    }

    public function canceled($order_id)
    {
        $order = Order::find($order_id);

        $order->update([
            'order_status' => Order::CANCELED,
        ]);

        $order->products()->each( function($order_product) {
            $product = Product::find($order_product->pivot->product_id);
            $product->update([
                'quantity' => $product->quantity + $order_product->pivot->quantity,
            ]);
        });

        Alert::toast('You have canceld your order payment!', 'error');
        return redirect()->route('frontend.index');
    }

    public function completed($order_id)
    {
        $order = Order::find($order_id);

        $omniPay = new OmnipayService('PayPal_Express');

        $response = $omniPay->complete([
            'amount'        => $order->total,
            'transactionId' => $order->id,
            'currency'      => $order->currency,
            'cancelUrl'     => $omniPay->getCancelUrl($order->id),
            'returnUrl'     => $omniPay->getReturnUrl($order->id),
            'notifyUrl'     => $omniPay->getNotifyUrl($order->id),
        ]);

        if($response->isSuccessful()) {
            $order->update(['order_status' => Order::PAYMNET_COMPLETE]);
            $order->transactions()->create([
                'transaction' => OrderTransaction::PAYMNET_COMPLETE,
                'transaction_number' => $response->getTransactionReference(),
                'payment_result' => 'success',
            ]);

            if(session()->has('coupon')) {
                $coupon = ProductCoupon::whereCode(session()->get('coupon'))->first();
                $coupon->used_times++;
            }

            //delete cart content and sessions
            Cart::instance('default')->destroy();
            session()->forget([
                'coupon',
                'saved_customer_address_id',
                'saved_shipping_company_id',
                'saved_payment_method_id',
                'shipping',
            ]);

            Alert::toast('Your payment is successfull with refrence code: ' . $response->getTransactionReference(), 'success');
            return redirect()->route('frontend.index');
        }
    }

    public function webhook($order_id, $envi)
    {
        //
    }

}
