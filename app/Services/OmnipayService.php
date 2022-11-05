<?php
namespace App\Services;

use Omnipay\Omnipay;

class OmnipayService {

    protected $gateway = '';

    public function __construct($payment_method = 'PayPal_Express')
    {
        if( is_null($payment_method) || $payment_method == 'PayPal_Express' ) {
            $this->gateway = Omnipay::create('PayPal_Express');
            $this->gateway->setUsername(config('services.paypal.username'));
            $this->gateway->setPassword(config('services.paypal.password'));
            $this->gateway->setSignature(config('services.paypal.signature'));
            $this->gateway->setTestMode(config('services.paypal.sandbox'));
        }
        return $this->gateway;
    }

    // this method to send the order before the purchase
    public function purchase(array $param)
    {
        $response = $this->gateway->purchase($param)->send();
        return $response;
    }

    //this method for refund
    public function refund(array $param)
    {
        $response = $this->gateway->refund($param)->send();
        return $response;
    }

    //this method to confirm the purchase or refund
    public function complete(array $param)
    {
        $response = $this->gateway->completePurchase($param)->send();
        return $response;
    }

    //these three methods to get the response from paypal website
    
    public function getCancelUrl($order_id)
    {
        return route('frontend.checkout.cancel', $order_id);
    }

    public function getReturnUrl($order_id)
    {
        return route('frontend.checkout.complete', $order_id);
    }

    public function getNotifyUrl($order_id)
    {
        $envi = config('services.paypal.sandbox') ? 'sandbox' : 'live';
        return route('frontend.checkout.webhook.ipn', [$order_id, $envi]);
    }
}
