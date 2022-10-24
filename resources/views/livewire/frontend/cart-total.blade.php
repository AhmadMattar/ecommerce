<div class="col-lg-4">
    <div class="card border-0 rounded-0 p-lg-4 bg-light">
      <div class="card-body">
        <h5 class="text-uppercase mb-4">Cart total</h5>
        <ul class="list-unstyled mb-0">
          <li class="d-flex align-items-center justify-content-between">
              <strong class="text-uppercase small font-weight-bold">Subtotal</strong>
              <span class="text-muted small">${{$cart_subtotal}}</span>
          </li>
          <li class="d-flex align-items-center justify-content-between">
              <strong class="text-uppercase small font-weight-bold">Tax</strong>
              <span class="text-muted small">${{$cart_tax}}</span>
          </li>
          <li class="border-bottom my-2"></li>
          <li class="d-flex align-items-center justify-content-between mb-4">
              <strong class="text-uppercase small font-weight-bold">Total</strong>
              <span>${{$cart_total}}</span>
          </li>
          {{-- <li>
            <form action="#">
              <div class="form-group mb-0">
                <input class="form-control" type="text" placeholder="Enter your coupon">
                <button class="btn btn-dark btn-sm btn-block" type="submit"> <i class="fas fa-gift mr-2"></i>Apply coupon</button>
              </div>
            </form>
          </li> --}}
        </ul>
      </div>
    </div>
  </div>
