<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $main_categories = ProductCategory::whereParentId(null)->whereStatus(1)->get();
        return view('frontend.index', compact('main_categories'));
    }

    public function cart()
    {
        return view('frontend.cart');
    }

    public function wishlist()
    {
        return view('frontend.wishlist');
    }

    public function checkout()
    {
        return view('frontend.checkout');
    }

    public function product($slug)
    {
        $product = Product::with('category', 'tags', 'media')->withAvg('reviews', 'rating')
                            ->whereSlug($slug)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();
        $relatedProducts = Product::with('firstMedia')->whereHas('category', function($query) use ($product){
            $query->whereId($product->product_category_id);
            $query->whereStatus(true);
        })->inRandomOrder()->Active()->HasQuantity()->take(4)->get();
        return view('frontend.detail', compact('product', 'relatedProducts'));
    }

    public function shop($slug = null)
    {
        return view('frontend.shop', compact('slug'));
    }

    public function shop_tag($slug = null)
    {
        return view('frontend.shop_tags', compact('slug'));
    }
}
