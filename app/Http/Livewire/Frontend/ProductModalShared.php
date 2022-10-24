<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ProductModalShared extends Component
{
    use LivewireAlert;

    public $productModalCount = false;
    public $productModal;
    public $quantity = 1;

    protected $optionsAlert = [
        'timer' => 5000,
        'timerProgressBar' => true,
    ];

    protected $listeners = ['showProductModalAction'];

    public function showProductModalAction($slug)
    {
        $this->productModalCount = true;
        $this->productModal = Product::withAvg('reviews', 'rating')->whereSlug($slug)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();
    }

    public function decreaseQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function increaseQuantity()
    {
        if ($this->productModal->quantity > $this->quantity) {
            $this->quantity++;
        }else {
            $this->alert('warning', 'This is the maximum quantity you can add!', $this->optionsAlert);
        }
    }

    public function addToCart()
    {
        $duplicate = Cart::instance('default')->search(function($cartItem) {
            return $cartItem->id === $this->productModal->id;
        });

        if($duplicate->isNotEmpty()) {
            $this->alert('error', 'This product already exists!', $this->optionsAlert);
        } else {
            Cart::instance('default')->add($this->productModal->id, $this->productModal->name, $this->quantity, $this->productModal->price)->associate(Product::class);
            $this->quantity = 1;
            $this->emit('updateCart');
            $this->alert('success', 'Product added to your cart successfully.', $this->optionsAlert);
        }
    }

    public function addToWishList()
    {
        $duplicate = Cart::instance('wishList')->search(function($cartItem) {
            return $cartItem->id === $this->productModal->id;
        });

        if($duplicate->isNotEmpty()) {
            $this->alert('error', 'This product already exists!', $this->optionsAlert);
        } else {
            Cart::instance('wishList')->add($this->productModal->id, $this->productModal->name, 1, $this->productModal->price)->associate(Product::class);
            $this->emit('updateCart');
            $this->alert('success', 'Product added to your wishlist cart successfully.', $this->optionsAlert);
        }
    }

    public function render()
    {
        return view('livewire.frontend.product-modal-shared');
    }
}
