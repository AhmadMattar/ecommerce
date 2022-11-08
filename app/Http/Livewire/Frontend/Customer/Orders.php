<?php

namespace App\Http\Livewire\Frontend\Customer;

use App\Models\Order;
use Livewire\Component;

class Orders extends Component
{
    public $showOrder;
    public $order;

    public function displayOrder($id)
    {
        $this->order = Order::with('products')->find($id);
        $this->showOrder = true;
    }

    public function render()
    {
        return view('livewire.frontend.customer.orders', [
            'orders'    => auth()->user()->orders,
        ]);
    }
}
