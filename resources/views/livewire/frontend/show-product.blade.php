<div>
    <div class="row align-items-stretch mb-4">
        <div class="col-sm-5 pr-sm-0">
            <div class="border d-flex align-items-center justify-content-between py-1 px-3 bg-white border-white"><span
                    class="small text-uppercase text-gray mr-4 no-select">Quantity</span>
                <div class="quantity">
                    <button wire:click.prevent="decreaseQuantity()" class="p-0"><i class="fas fa-caret-left"></i></button>
                    <input wire:model="quantity" class="form-control border-0 shadow-0 p-0" type="text">
                    <button wire:click.prevent="increaseQuantity()" class="p-0"><i class="fas fa-caret-right"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-3 pl-sm-0">
            <a wire:click.prevent="addToCart()" class="btn btn-dark btn-sm btn-block h-100 d-flex align-items-center justify-content-center px-0">
                Add to cart
            </a>
        </div>
    </div>
    <a wire:click.prevent="addToWishList()" class="btn btn-link text-dark p-0 mb-4">
        <i class="far fa-heart mr-2"></i>Add to wish list
    </a>
    <br>
</div>
