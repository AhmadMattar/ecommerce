<?php

namespace Database\Seeders;

use App\Models\ProductCoupon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EntrustSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(TagSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductsTagsSeeder::class);
        $this->call(ProductsImagesSeeder::class);
        $this->call(ProductCouponSeeder::class);
    }
}
