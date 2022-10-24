<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Carts extends Component
{
    use LivewireAlert;

    public $cartCount;
    public $wishlistCount;

    protected $optionsAlert = [
        'timer' => 5000,
        'timerProgressBar' => true,
    ];
    protected $listeners = [
        'updateCart',
        'deleteItem',
        'removeFromWishList',
        'moveToCart',
    ];


    function mount()
    {
        $this->cartCount = Cart::instance('default')->count();
        $this->wishlistCount = Cart::instance('wishList')->count();
    }
    public function updateCart()
    {
        $this->cartCount = Cart::instance('default')->count();
        $this->wishlistCount = Cart::instance('wishList')->count();
    }
    public function deleteItem($rowId)
    {
        Cart::instance('default')->remove($rowId);
        $this->emit('updateCart');
        $this->alert('success', 'Product removed from your cart successfully.', $this->optionsAlert);

        if(Cart::instance('default')->count() == 0) {
            return redirect()->route('frontend.cart');
        }
    }

    public function removeFromWishList($rowId)
    {
        Cart::instance('wishList')->remove($rowId);
        $this->emit('updateCart');
        $this->alert('success', 'Product removed from your wish list successfully.', $this->optionsAlert);

        if(Cart::instance('wishList')->count() == 0) {
            return redirect()->route('frontend.wishlist');
        }
    }

    public function moveToCart($rowId)
    {
        $item = Cart::instance('wishList')->get($rowId);

        $duplicate = Cart::instance('default')->search(function($cartItem, $rId) use ($rowId){
            return $rId === $rowId;
        });

        if($duplicate->isNotEmpty()) {
            Cart::instance('wishList')->remove($rowId);
            $this->alert('error', 'This product already exists!', $this->optionsAlert);

        } else {
            Cart::instance('default')->add($item->id, $item->name, 1, $item->price)->associate(Product::class);
            Cart::instance('wishList')->remove($rowId);
            $this->alert('success', 'added in your cart successfully', $this->optionsAlert);

        }

        $this->emit('updateCart');

        if(Cart::instance('wishList')->count() == 0) {
            return redirect()->route('frontend.wishlist');
        }
    }

    public function render()
    {
        return view('livewire.frontend.carts');
    }
}
