<?php

namespace App\Http\Livewire\Frontend;

use App\Models\PaymentMethod;
use App\Models\ProductCoupon;
use App\Models\ShippingCompany;
use App\Models\UserAddress;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Checkout extends Component
{
    use LivewireAlert;

    public $cart_subtotal;
    public $cart_tax;
    public $cart_total;
    public $cart_coupon;
    public $coupon_code;
    public $cart_discount;
    public $addresses;
    public $customer_address_id;
    public $shipping_companies;
    public $shipping_company_id;
    public $cart_shipping;
    public $payment_methods;
    public $payment_method_id;
    public $payment_method_code;

    protected $listeners = [
        'updateCart' => 'mount'
    ];
    protected $optionsAlert = [
        'timer' => 2000,
        'timerProgressBar' => true,
    ];

    public function mount()
    {
        $this->customer_address_id = session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        $this->shipping_company_id = session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
        $this->payment_method_id = session()->has('saved_payment_method_id') ? session()->get('saved_payment_method_id') : '';

        $this->addresses = auth()->user()->addresses;
        $this->cart_subtotal = getNumbers()->get('subtotal');
        $this->cart_tax = getNumbers()->get('productTaxes');
        $this->cart_discount = getNumbers()->get('discount');
        $this->cart_shipping = getNumbers()->get('shipping');
        $this->cart_total = getNumbers()->get('total');

        if($this->customer_address_id == '') {
            $this->shipping_companies = collect([]);
        } else {
            $this->updateShippingCompanies();
        }
        $this->payment_methods = PaymentMethod::whereStatus(true)->get();
    }

    public function applyDiscount()
    {
        if (getNumbers()->get('subtotal') > 0) {
            $coupon = ProductCoupon::whereCode($this->coupon_code)->whereStatus(true)->first();
            if($coupon) {
                $couponValue = $coupon->discount($this->cart_subtotal);
                if($couponValue > 0) {
                    session()->put('coupon', [
                        'code' => $coupon->code,
                        'value' => $coupon->value,
                        'discount' => $couponValue,
                    ]);
                    $this->coupon_code = session()->get('coupon')['code'];
                    $this->emit('updateCart');
                    $this->alert('success', 'Coupon applied successfully.', $this->optionsAlert);
                } else {
                    $this->alert('error', 'Coupon is invalid!', $this->optionsAlert);
                }

            } else {
                $this->cart_coupon = '';
                $this->alert('error', 'Coupon is invalid!', $this->optionsAlert);
            }
        } else {
            $this->coupon_code = '';
            $this->alert('error', 'Your Cart is empty!', $this->optionsAlert);
        }
    }

    public function removeCoupon()
    {
        session()->remove('coupon');
        $this->coupon_code = '';
        $this->emit('updateCart');
        $this->alert('success', 'Coupon removed successfully.', $this->optionsAlert);
    }

    public function updateShippingCompanies()
    {
        $address = UserAddress::find($this->customer_address_id);
        $this->shipping_companies = ShippingCompany::whereHas('countries', function($query) use ($address){
            $query->where('country_id', $address->country_id);
        })->get();

    }

    public function updatingCustomerAddressId()
    {
        session()->forget('saved_customer_address_id');
        session()->forget('saved_shipping_company_id');
        session()->forget('shipping');

        session()->put('saved_customer_address_id', $this->customer_address_id);

        session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
        session()->has('saved_payment_method_id') ? session()->get('saved_payment_method_id') : '';
        $this->emit('updateCart');
    }

    public function updatedCustomerAddressId()
    {
        session()->forget('saved_customer_address_id');
        session()->forget('saved_shipping_company_id');
        session()->forget('shipping');

        session()->put('saved_customer_address_id', $this->customer_address_id);

        session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
        session()->has('saved_payment_method_id') ? session()->get('saved_payment_method_id') : '';
        $this->emit('updateCart');
    }

    public function updateShippingCost()
    {
        $shipping_comapny = ShippingCompany::find($this->shipping_company_id);
        session()->put('shipping', [
            'code' => $shipping_comapny->code,
            'cost' => $shipping_comapny->cost
        ]);
        $this->emit('updateCart');
        $this->alert('success', 'Shipping cost is applied successfully.');
    }

    public function updatingShippingCompanyId()
    {
        session()->forget('saved_shipping_company_id');
        session()->put('saved_shipping_company_id', $this->shipping_company_id);

        session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
        $this->emit('updateCart');
    }

    public function updatedShippingCompanyId()
    {
        session()->forget('saved_shipping_company_id');
        session()->put('saved_shipping_company_id', $this->shipping_company_id);

        session()->has('saved_customer_address_id') ? session()->get('saved_customer_address_id') : '';
        session()->has('saved_shipping_company_id') ? session()->get('saved_shipping_company_id') : '';
        $this->emit('updateCart');
    }

    public function updatePaymentMethod()
    {
        $payment_method = PaymentMethod::find($this->payment_method_id);
        $this->payment_method_code = $payment_method->code;
    }


    public function render()
    {
        return view('livewire.frontend.checkout');
    }
}
