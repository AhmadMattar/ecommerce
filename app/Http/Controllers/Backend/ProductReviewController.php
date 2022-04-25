<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductReviewRequest;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(!auth()->user()->ability('admin', 'manage_product_reviews, show_product_reviews')){
        //     return redirect()->route('admin.index');
        // }

        $reviews = ProductReview::query()
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limit_by ?? 10);
        return view('backend.product_reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->ability('admin', 'create_product_reviews')){
            return redirect()->route('admin.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->ability('admin', 'create_product_reviews')){
            return redirect()->route('admin.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductReview $productReview)
    {
        if(!auth()->user()->ability('admin', 'display_product_reviews')){
            return redirect()->route('admin.index');
        }

        return view('backend.product_reviews.show', compact('productReview'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductReview $productReview)
    {
        if(!auth()->user()->ability('admin', 'update_product_reviews')){
            return redirect()->route('admin.index');
        }

        return view('backend.product_reviews.edit', compact('productReview'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductReviewRequest $request, ProductReview $productReview)
    {
        if(!auth()->user()->ability('admin', 'update_product_reviews')){
            return redirect()->route('admin.index');
        }

        $productReview->update($request->validated());

        return redirect()->route('admin.product_reviews.index')->with([
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
    public function destroy(ProductReview $productReview)
    {
        if(!auth()->user()->ability('admin', 'delete_product_reviews')){
            return redirect()->route('admin.index');
        }

        $productReview->delete();

        return redirect()->route('admin.product_reviews.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger',
        ]);
    }
}
