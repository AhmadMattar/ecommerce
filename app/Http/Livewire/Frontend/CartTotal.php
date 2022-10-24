<?php

namespace App\Http\Livewire\Frontend;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CartTotal extends Component
{
    public $cart_subtotal;
    public $cart_tax;
    public $cart_total;

    protected $listeners = [
        'updateCart' => 'mount'
    ];

    public function mount()
    {
        $this->cart_subtotal = Cart::instance('default')->subtotal();
        $this->cart_tax = Cart::instance('default')->tax();
        $this->cart_total = Cart::instance('default')->total();
    }

    public function render()
    {
        return view('livewire.frontend.cart-total');
    }
}
