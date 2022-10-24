<div class="row">
    <div class="col-lg-8">
        <h2 class="h5 text-uppercase mb-4">Shipping addresses</h2>
        <div class="row">
            @forelse ($addresses as $address)
                <div class="col-6 form-group">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="address-{{ $address->id }}" wire:model="customer_address_id"
                            wire:click="updateShippingCompanies()" {{ intval($customer_address_id) == $address->id ? 'checked' : ''}}
                            value="{{ $address->id }}">
                        <Label for="address-{{ $address->id }}" class="custom-control-label text-small">
                            <b>{{ $address->address_title}}</b>
                            <small>
                                {{ $address->address }}<br>
                                {{ $address->country->name}} - {{ $address->city->name }} - {{ $address->state->name }}
                            </small>
                        </Label>
                    </div>
                </div>
            @empty
                <p>No addresses found</p>
                <a href="#">Add an address</a>
            @endforelse
        </div>
        @if ($customer_address_id != '')
            <h2 class="h5 text-uppercase mb-4">Shipping companies</h2>
            <div class="row">
                @forelse ($shipping_companies as $shipping_company)
                    <div class="col-6 form-group">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="shipping-company-{{ $shipping_company->id }}" wire:model="shipping_company_id"
                                wire:click="updateShippingCost()" {{ intval($shipping_company_id) == $shipping_company->id ? 'checked' : ''}}
                                value="{{ $shipping_company->id }}">
                            <Label for="shipping-company-{{ $shipping_company->id }}" class="custom-control-label text-small">
                                <b>{{ $shipping_company->address_title}}</b>
                                <small>
                                    {{ $shipping_company->name }} (${{ $shipping_company->cost }})
                                </small>
                            </Label>
                        </div>
                    </div>
                @empty
                    <p>No shipping companies found</p>
                @endforelse
            </div>
        @endif

        @if ($customer_address_id != '' && $shipping_company_id != '')
            <h2 class="h5 text-uppercase mb-4">Payment way</h2>
            <div class="row">
                @forelse ($payment_methods as $payment_method)
                    <div class="col-6 form-group">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="payment-method-{{ $payment_method->id }}" wire:model="payment_method_id"
                                wire:click="updatePaymentMethod()" {{ intval($payment_method_id) == $payment_method->id ? 'checked' : ''}}
                                value="{{ $payment_method->id }}">
                            <Label for="payment-method-{{ $payment_method->id }}" class="custom-control-label text-small">
                                <b>{{ $payment_method->address_title}}</b>
                                <small>
                                    {{ $payment_method->name }} (${{ $payment_method->cost }})
                                </small>
                            </Label>
                        </div>
                    </div>
                @empty
                    <p>No Payment way found</p>
                @endforelse
            </div>
        @endif

        @if ($customer_address_id != '' && $shipping_company_id != '' && $payment_method_id != '')
            @if (Str::lower($payment_method_code) == 'ppex')
                <form action="{{ route('frontend.checkout.payment') }}" method="post">
                    @csrf
                    <input type="text" name="customer_address_id" value="{{ old('customer_address_id', $customer_address_id) }}">
                    <input type="text" name="shipping_company_id" value="{{ old('shipping_company_id', $shipping_company_id) }}">
                    <input type="text" name="payment_method_id" value="{{ old('payment_method_id', $payment_method_id) }}">

                    <button type="submit"> continue checkout with PayPal</button>
                </form>
            @endif
        @endif

    </div>
    <!-- ORDER SUMMARY-->
    <div class="col-lg-4">
        <div class="card border-0 rounded-0 p-lg-4 bg-light">
            <div class="card-body">
                <h5 class="text-uppercase mb-4">Your order</h5>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-center justify-content-between">
                        <strong class="small font-weight-bold">Subtotal</strong>
                        <span class="text-muted small">${{ $cart_subtotal }}</span></li>
                    <li class="border-bottom my-2">

                    @if (session()->has('coupon'))
                        </li>
                        <li class="d-flex align-items-center justify-content-between">
                            <strong class="small font-weight-bold">Discount <small>({{ session()->get('coupon')['code'] }})</small></strong>
                            <span class="text-muted small">- ${{ $cart_discount }}</span>
                        </li>
                    @endif

                    </li>
                    <li class="d-flex align-items-center justify-content-between">
                        <strong class="small font-weight-bold">Tax</strong>
                        <span class="text-muted small">${{ $cart_tax }}</span>
                    </li>
                    <li class="border-bottom my-2"></li>
                    <li class="d-flex align-items-center justify-content-between">
                        <strong class="text-uppercase small font-weight-bold">Total</strong>
                        <span>${{ $cart_total }}</span>
                    </li>

                    <li class="border-bottom my-2"></li>
                    <li>
                        <form wire:submit.prevent="applyDiscount()">
                            @if (!session()->has('coupon'))
                                <input type="text" wire:model="coupon_code" class="form-control" placeholder="Enter your coupoun">
                            @endif

                            @if (session()->has('coupon'))
                                <a type="button" wire:click.prevent="removeCoupon()" class="btn btn-danger btn-sm btn-block">
                                    <i class="fas fa-gift mr-2"></i> Remove coupoun
                                </a>
                            @else
                                <button type="submit" class="btn btn-dark btn-sm btn-block">
                                    <i class="fas fa-gift mr-2"></i> Apply coupoun
                                </button>
                            @endif
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
