<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ShopProductsTag extends Component
{
    use WithPagination, LivewireAlert;

    protected $paginationTheme = 'bootstrap';
    public $paginationLimit = 12;
    public $slug;
    public $sortingBy = 'default';
    protected $optionsAlert = [
        'timer' => 5000,
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
        $products = $products->with('tags')->whereHas('tags', function($query) {
            $query->where([
                'slug' => $this->slug,
                'status' => true,
            ]);
        });

        $products = $products->Active()->HasQuantity()->orderBy($sort_field, $sort_type)->paginate($this->paginationLimit);
        return view('livewire.frontend.shop-products-tag', [
            'products' => $products,
        ]);
    }
}
