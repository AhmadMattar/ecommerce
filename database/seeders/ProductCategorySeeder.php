<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clothes = ProductCategory::create([
            'name' => 'Cloths',
            'cover' => 'clothes.jpg',
            'status' => true,
            'parent_id' => null
        ]);
        ProductCategory::create(['name' => 'Women\'s T-Shirts', 'cover' => 'clothes.jpg', 'status' => true, 'parent_id' => $clothes->id]);
        ProductCategory::create(['name' => 'Men\'s T-Shirts', 'cover' => 'clothes.jpg', 'status' => true, 'parent_id' => $clothes->id]);
        ProductCategory::create(['name' => 'Dresses', 'cover' => 'clothes.jpg', 'status' => true, 'parent_id' => $clothes->id]);
        ProductCategory::create(['name' => 'Movelty socks', 'cover' => 'clothes.jpg', 'status' => true, 'parent_id' => $clothes->id]);
        ProductCategory::create(['name' => 'Women\'s sunglasses', 'cover' => 'clothes.jpg', 'status' => true, 'parent_id' => $clothes->id]);
        ProductCategory::create(['name' => 'Men\'s sunglasses', 'cover' => 'clothes.jpg', 'status' => true, 'parent_id' => $clothes->id]);

        $shoes = ProductCategory::create([
            'name' => 'Shoes',
            'cover' => 'shoes.jpg',
            'status' => true
        ]);
        ProductCategory::create(['name' => 'Women\'s Shoes', 'cover' => 'shoes.jpg', 'status' => true, 'parent_id' => $shoes->id]);
        ProductCategory::create(['name' => 'Men\'s Shoes', 'cover' => 'shoes.jpg', 'status' => true, 'parent_id' => $shoes->id]);
        ProductCategory::create(['name' => 'Boy\'s Shoes', 'cover' => 'shoes.jpg', 'status' => true, 'parent_id' => $shoes->id]);
        ProductCategory::create(['name' => 'Girls\'s Shoes', 'cover' => 'shoes.jpg', 'status' => true, 'parent_id' => $shoes->id]);

        $watches = ProductCategory::create([
            'name' => 'Watches',
            'cover' => 'watches.jpg',
            'status' => true
        ]);
        ProductCategory::create(['name' => 'Women\'s Watches', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $watches->id]);
        ProductCategory::create(['name' => 'Men\'s Watches', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $watches->id]);
        ProductCategory::create(['name' => 'Boy\'s Watches', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $watches->id]);
        ProductCategory::create(['name' => 'Girls\'s Watches', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $watches->id]);

        $electronics = ProductCategory::create([
            'name' => 'Electronics',
            'cover' => 'electronics.jpg',
            'status' => true
        ]);
        ProductCategory::create(['name' => 'Electronics', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $electronics->id]);
        ProductCategory::create(['name' => 'USB Flash drivers', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $electronics->id]);
        ProductCategory::create(['name' => 'Headphones', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $electronics->id]);
        ProductCategory::create(['name' => 'Portable speakers', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $electronics->id]);
        ProductCategory::create(['name' => 'Cell Phone bluetooth heaadsest', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $electronics->id]);
        ProductCategory::create(['name' => 'keyboards', 'cover' => 'electronics.jpg', 'status' => true, 'parent_id' => $electronics->id]);
    }
}
