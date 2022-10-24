<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RelatedProducts extends Component
{
    use LivewireAlert;

    public $relatedProducts;
    protected $optionsAlert = [
        'timer' => 5000,
        'timerProgressBar' => true,
    ];

    public function mount($relatedProducts)
    {
        $this->relatedProducts = $relatedProducts;
    }

    public function addToCart($id)
    {
        $product = Product::whereId($id)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();
        $duplicate = Cart::instance('default')->search(function($cartItem) use ($product){
            return $cartItem->id === $product->id;
        });

        if($duplicate->isNotEmpty()) {
            $this->alert('error', 'This product already exists!', $this->optionsAlert);
        } else {
            Cart::instance('default')->add($product->id, $product->name, 1, $product->price)->associate(Product::class);
            $this->emit('updateCart');
            $this->alert('success', 'Product added to your cart successfully.', $this->optionsAlert);
        }
    }

    public function addToWishList($id)
    {
        $product = Product::whereId($id)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();
        $duplicate = Cart::instance('wishList')->search(function($cartItem) use ($product){
            return $cartItem->id === $product->id;
        });

        if($duplicate->isNotEmpty()) {
            $this->alert('error', 'This product already exists!', $this->optionsAlert);
        } else {
            Cart::instance('wishList')->add($product->id, $product->name, 1, $product->price)->associate(Product::class);
            $this->emit('updateCart');
            $this->alert('success', 'Product added to your wishlist cart successfully.', $this->optionsAlert);
        }
    }
    public function render()
    {
        return view('livewire.frontend.related-products');
    }
}
