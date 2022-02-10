<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;

class ProductsTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = Tag::whereStatus(true)->pluck('id')->toArray();

        Product::all()->each(function ($product) use ($tags) {
            $product->tags()->attach(Arr::random($tags, rand(2, 3)));
        });
    }
}
