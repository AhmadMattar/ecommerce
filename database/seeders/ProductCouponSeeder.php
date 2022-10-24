<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\ProductCoupon;
use Illuminate\Database\Seeder;

class ProductCouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCoupon::create([
            'code'          => 'AHMED200',
            'type'          => 'fixed',
            'value'         => 200,
            'description'   => 'Discount 200 SAR on your sales on website',
            'use_times'     => 20,
            'start_date'    => Carbon::now(),
            'expire_date'   => Carbon::now()->addMonth(),
            'greater_than'  => 600,
            'status'        => 1,
        ]);

        ProductCoupon::create([
            'code'          => 'FiftyFifty',
            'type'          => 'percentage',
            'value'         => 50,
            'description'   => 'Discount 50% SAR on your sales on website',
            'use_times'     => 5,
            'start_date'    => Carbon::now(),
            'expire_date'   => Carbon::now()->addWeek(),
            'greater_than'  => null,
            'status'        => 1,
        ]);
    }
}
