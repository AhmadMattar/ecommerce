<?php

namespace App\Http\Controllers\Backend;

use App\Models\Tag;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Backend\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // put the permission and role using "ability"
        if(!auth()->user()->ability('admin', 'manage_products, show_products')){
                    return redirect()->route('admin.index');
        }
        $products = Product::with('category', 'tags', 'firstMedia')
            //search in Products
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limit_by ?? 10);
        return view('backend.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->ability('admin', 'create_products')){
                    return redirect()->route('admin.index');
        }
        $categories = ProductCategory::whereStatus(1)->get(['id', 'name']);
        $tags = Tag::whereStatus(1)->get(['id', 'name']);
        return view('backend.products.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        if(!auth()->user()->ability('admin', 'create_products')){
                    return redirect()->route('admin.index');
        }
        $input['name'] = $request->name;
        $input['description'] = $request->description;
        $input['price'] = $request->price;
        $input['quantity'] = $request->quantity;
        $input['product_category_id'] = $request->product_category_id;
        $input['featured'] = $request->featured;
        $input['status'] = $request->status;
        $product = Product::create($input);

        // because the relation is m to m between products and tags, we insert tags by using attach()
        $product->tags()->attach($request->tags);

        if($request->images && count($request->images) > 0){
            $i = 1;
            foreach($request->images as $image){
                $file_name = $product->slug . '_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
                $file_size = $image->getSize();
                $file_type = $image->getMimeType();
                $path = public_path('uploads/products/' . $file_name);
                Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $product->media()->create([
                    'file_name' => $file_name,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_status' => true,
                    'file_sort' => $i
                ]);
                $i++;
            }
        }
        return redirect()->route('admin.products.index')->with([
            'message' => 'Created successfully',
            'alert-type' => 'success'
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
        if(!auth()->user()->ability('admin', 'display_products')){
                    return redirect()->route('admin.index');
        }
        return view('backend.products.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if(!auth()->user()->ability('admin', 'update_products')){
                    return redirect()->route('admin.index');
        }
        $categories = ProductCategory::whereStatus(1)->get(['id', 'name']);
        $tags = Tag::whereStatus(1)->get(['id', 'name']);
        return view('backend.products.edit',  compact('categories', 'tags', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        if(!auth()->user()->ability('admin', 'update_products')){
                    return redirect()->route('admin.index');
        }
        $input['name'] = $request->name;
        $input['description'] = $request->description;
        $input['price'] = $request->price;
        $input['quantity'] = $request->quantity;
        $input['product_category_id'] = $request->product_category_id;
        $input['featured'] = $request->featured;
        $input['status'] = $request->status;
        $product->update($input);
        $product->tags()->sync($request->tags);

        if($request->images && count($request->images) > 0){
            $i = $product->media()->count() + 1;
            foreach($request->images as $image){
                $file_name = $product->slug . '_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
                $file_size = $image->getSize();
                $file_type = $image->getMimeType();
                $path = public_path('uploads/products/' . $file_name);
                Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $product->media()->create([
                    'file_name' => $file_name,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_status' => true,
                    'file_sort' => $i
                ]);
                $i++;
            }
        }
        return redirect()->route('admin.products.index')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if(!auth()->user()->ability('admin', 'delete_products')){
                    return redirect()->route('admin.index');
        }
        //delete images first
        if($product->media()->count() > 0){
            foreach($product->media as $media){
                if(File::exists('uploads/products/'. $media->file_name)){
                    unlink('uploads/products/'. $media->file_name);
                }
                $media->delete();
            }
        }
        
        //then delete the product
        $product->delete();
        return redirect()->route('admin.products.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger'
        ]);
    }

    public function remove_image(Request $request)
    {
        if(!auth()->user()->ability('admin', 'delete_products')){
            return redirect()->route('admin.index');
        }

        $product = Product::findOrFail($request->product_id);
        $image = $product->media()->whereId($request->image_id)->first();
        if(File::exists('uploads/products/'. $image->file_name)){
            unlink('uploads/products/'. $image->file_name);
        }
        $image->delete();
        return true;
    }
}
