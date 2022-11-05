<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CartItems extends Component
{
    use LivewireAlert;

    public $item;
    public $item_quantity = 1;
    protected $optionsAlert = [
        'timer' => 2000,
        'timerProgressBar' => true,
    ];

    public function mount() {
        $this->item_quantity = Cart::instance('default')->get($this->item)->qty ?? 1 ;
    }
    public function decreaseQuantity($rowId)
    {
        if ($this->item_quantity > 1) {
            $this->item_quantity--;
            Cart::instance('default')->update($rowId, $this->item_quantity);
            $this->emit('updateCart');
        }
    }

    public function increaseQuantity($rowId)
    {
        $productItem = Cart::instance('default')->get($this->item);
        $product = Product::find($productItem->id);

        if ($product->quantity > $this->item_quantity) {
            $this->item_quantity++;
            Cart::instance('default')->update($rowId, $this->item_quantity);
            $this->emit('updateCart');
        }else {
            $this->alert('warning', 'This is the maximum quantity you can add!', $this->optionsAlert);
        }
    }

    public function deleteItem($rowId)
    {
        $this->emit('deleteItem', $rowId);
    }

    public function render()
    {
        return view('livewire.frontend.cart-items',[
            'cartItem' => Cart::instance('default')->get($this->item),
        ]);
    }
}
