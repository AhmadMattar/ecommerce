<?php

namespace App\Http\Livewire\Frontend;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class WishList extends Component
{
    public $item;

    public function moveToCart($rowId)
    {
        $this->emit('moveToCart', $rowId);
    }

    public function removeFromWishList($rowId)
    {
        $this->emit('removeFromWishList', $rowId);
    }

    public function render()
    {
        return view('livewire.frontend.wish-list', [
            'wishListItem' => Cart::instance('wishList')->get($this->item),
        ]);
    }
}
