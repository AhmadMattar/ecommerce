<?php

namespace App\Http\Livewire\Frontend\Customer;

use App\Models\Order;
use App\Models\OrderTransaction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Orders extends Component
{
    use LivewireAlert;

    public $showOrder;
    public $order;

    protected $optionsAlert = [
        'timer' => 2000,
        'timerProgressBar' => true,
    ];

    public function displayOrder($id)
    {
        $this->order = Order::with('products')->find($id);
        $this->showOrder = true;
    }

    public function requestRefundedOrder($id)
    {
        $order = Order::find($id);
        $order->update([
            'order_status' => Order::REFUNDED_REQUEST,
        ]);

        $order->transactions()->create([
            'transaction' => OrderTransaction::REFUNDED_REQUEST,
            'transaction_number' => $order->transactions()->whereTransaction(OrderTransaction::PAYMNET_COMPLETE)
                                    ->first()->transaction_number,
        ]);
        $this->alert('success', 'Your request send successfully', $this->optionsAlert);
    }

    public function render()
    {
        return view('livewire.frontend.customer.orders', [
            'orders'    => auth()->user()->orders,
        ]);
    }
}
