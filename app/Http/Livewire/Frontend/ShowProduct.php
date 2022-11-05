<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use phpDocumentor\Reflection\Types\This;

class ShowProduct extends Component
{
    use LivewireAlert;

    protected $optionsAlert = [
        'timer' => 2000,
        'timerProgressBar' => true,
    ];
    public $product;
    public $quantity = 1;

    public function decreaseQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }
    public function increaseQuantity()
    {
        if ($this->product->quantity > $this->quantity) {
            $this->quantity++;
        }else {
            $this->alert('warning', 'This is the maximum quantity you can add!', $this->optionsAlert);
        }
    }
    public function addToCart()
    {
        $duplicate = Cart::instance('default')->search(function($cartItem) {
            return $cartItem->id === $this->product->id;
        });

        if($duplicate->isNotEmpty()) {
            $this->alert('error', 'This product already exists!', $this->optionsAlert);
        } else {
            Cart::instance('default')->add($this->product->id, $this->product->name, $this->quantity, $this->product->price)->associate(Product::class);
            $this->quantity = 1;
            $this->emit('updateCart');
            $this->alert('success', 'Product added to your cart successfully.', $this->optionsAlert);
        }
    }

    public function addToWishList()
    {
        $duplicate = Cart::instance('wishList')->search(function($cartItem) {
            return $cartItem->id === $this->product->id;
        });

        if($duplicate->isNotEmpty()) {
            $this->alert('error', 'This product already exists!', $this->optionsAlert);
        } else {
            Cart::instance('wishList')->add($this->product->id, $this->product->name, 1, $this->product->price)->associate(Product::class);
            $this->emit('updateCart');
            $this->alert('success', 'Product added to your wishlist cart successfully.', $this->optionsAlert);
        }
    }

    public function mount($product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.frontend.show-product');
    }
}
