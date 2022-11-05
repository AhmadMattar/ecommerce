<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProductCategory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ShopProducts extends Component
{
    use WithPagination, LivewireAlert;

    protected $paginationTheme = 'bootstrap';
    public $paginationLimit = 12;
    public $slug;
    public $sortingBy = 'default';
    protected $optionsAlert = [
        'timer' => 2000,
        'timerProgressBar' => true,
    ];

    public function addToCart($id)
    {
        $product = Product::whereId($id)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();
        $duplicate = Cart::instance('default')->search(function($cartItem) use ($product){
            return $cartItem->id === $product->id;
        });

        if($duplicate->isNotEmpty()) {
            $this->alert('error', 'This product already exists!', $this->optionsAlert);
        } else {
            Cart::instance('default')->add($product->id, $product->name, 1, $product->price)->associate(Product::class);
            $this->emit('updateCart');
            $this->alert('success', 'Product added to your cart successfully.', $this->optionsAlert);
        }
    }

    public function addToWishList($id)
    {
        $product = Product::whereId($id)->Active()->HasQuantity()->ActiveCategory()->firstOrFail();
        $duplicate = Cart::instance('wishList')->search(function($cartItem) use ($product){
            return $cartItem->id === $product->id;
        });

        if($duplicate->isNotEmpty()) {
            $this->alert('error', 'This product already exists!', $this->optionsAlert);
        } else {
            Cart::instance('wishList')->add($product->id, $product->name, 1, $product->price)->associate(Product::class);
            $this->emit('updateCart');
            $this->alert('success', 'Product added to your wishlist cart successfully.', $this->optionsAlert);
        }
    }


    public function render()
    {
        switch ($this->sortingBy) {
            case 'popular':
                $sort_field = 'id';
                $sort_type = 'asc';
                break;
            case 'low-high':
                $sort_field = 'price';
                $sort_type = 'asc';
                break;
            case 'high-low':
                $sort_field = 'price';
                $sort_type = 'desc';
                break;

            default:
                $sort_field = 'id';
                $sort_type = 'asc';
                break;
        }
        $products = Product::with('firstMedia');
        if($this->slug == '') {
            $products = $products->ActiveCategory();

        } else {
            $product_category = ProductCategory::whereSlug($this->slug)->whereStatus(true)->first();
            if(is_null($product_category->parent_id)) {
                $categoriesIds = ProductCategory::whereParentId($product_category->id)
                            ->whereStatus(true)->pluck('id')->toArray();
                $products = $products->whereHas('category', function($query) use($categoriesIds){
                    $query->whereIn('id', $categoriesIds);
                });

            } else {
                $products = $products->whereHas('category', function($query) {
                    $query->where([
                        'slug' => $this->slug,
                        'status' => true,
                    ]);
                });

            }
        }

        $products = $products->Active()->HasQuantity()->orderBy($sort_field, $sort_type)->paginate($this->paginationLimit);
        return view('livewire.frontend.shop-products', [
            'products' => $products,
        ]);
    }
}
