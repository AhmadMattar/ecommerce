<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductCouponRequest;
use App\Models\ProductCoupon;
use Illuminate\Http\Request;

class ProductCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->ability('admin', 'manage_product_coupons, show_product_coupons')){
            return redirect()->route('admin.index');
        }

        $coupons = ProductCoupon::query()
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limit_by ?? 10);
        return view('backend.product_coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->ability('admin', 'create_product_coupons')){
            return redirect()->route('admin.index');
        }

        return view('backend.product_coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCouponRequest $request)
    {
        if(!auth()->user()->ability('admin', 'create_product_coupons')){
            return redirect()->route('admin.index');
        }
        ProductCoupon::create($request->validated());

        return redirect()->route('admin.product_coupons.index')->with([
            'message' => 'Created successfully',
            'alert-type' => 'success',
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!auth()->user()->ability('admin', 'display_product_coupons')){
            return redirect()->route('admin.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCoupon $productCoupon)
    {
        if(!auth()->user()->ability('admin', 'update_product_coupons')){
            return redirect()->route('admin.index');
        }
        return view('backend.product_coupons.edit', compact('productCoupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCouponRequest $request, ProductCoupon $productCoupon)
    {
        if(!auth()->user()->ability('admin', 'update_product_coupons')){
            return redirect()->route('admin.index');
        }

        $productCoupon->update($request->validated());

        return redirect()->route('admin.product_coupons.index')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCoupon $productCoupon)
    {
        if(!auth()->user()->ability('admin', 'delete_product_coupons')){
            return redirect()->route('admin.index');
        }
        
        $productCoupon->delete();
        return redirect()->route('admin.product_coupons.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger',
        ]);
    }
}
