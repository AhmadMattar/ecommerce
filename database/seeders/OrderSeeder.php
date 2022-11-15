<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $ahmedUser = User::find(3);
        $products = Product::active()->hasQuantity()->activeCategory()->inRandomOrder()->take(3)->get();
        $subTotalValue = $products->sum('price');
        $discountValue = $subTotalValue / 2;
        $shippingValue = 15.00;
        $taxValue = ($subTotalValue - $discountValue) * 0.15;
        $totalValue = $subTotalValue - $discountValue + $shippingValue + $taxValue;

        // Create Order
        $order = $ahmedUser->orders()->create([
            'ref_id' => Str::random(15),
            'user_address_id' => 1,
            'shipping_company_id' => 1,
            'payment_method_id' => 1,
            'subtotal' => $subTotalValue,
            'discount_code' => 'fiftyfifty',
            'discount' => $discountValue,
            'shipping' => $shippingValue,
            'tax' => $taxValue,
            'total' => $totalValue,
            'currency' => 'USD',
            'order_status' => Order::PAYMNET_COMPLETE,
        ]);

        // Create Order Products
        $order->products()->attach($products->pluck('id')->toArray());

        // Create Order Transactions
        $order->transactions()->createMany([
            [
                'transaction' => Order::NEW_ORDER,
                'transaction_number' => null,
                'payment_result' => null,
            ],
            [
                'transaction' => Order::PAYMNET_COMPLETE,
                'transaction_number' => '9NW10162ME419262L',
                'payment_result' => 'success',
            ],
        ]);

        /*
         * Create fake order for each user
         */
        //
    }
}
