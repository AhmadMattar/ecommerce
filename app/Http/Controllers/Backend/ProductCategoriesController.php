<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Backend\ProductCategoryRequest;
use Illuminate\Support\Facades\File;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // put the permission and role using "ability"
        if(!auth()->user()->ability('admin', 'manage_product_categories, show_product_categories')){
            return redirect()->route('admin.index');
        }

        $categories = ProductCategory::withCount('products')
            //search in ProductCategory
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limit_by ?? 10);
        return view('backend.product_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->ability('admin', 'create_product_categories')){
            return redirect()->route('admin.index');
        }

        //send the parent categories to the view with theirs (name and id)
        $main_categories = ProductCategory::whereNull('parent_id')->get(['name', 'id']);
        return view('backend.product_categories.create',compact('main_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCategoryRequest $request)
    {
        if(!auth()->user()->ability('admin', 'create_product_categories')){
            return redirect()->route('admin.index');
        }

        $input['name'] = $request->name;
        $input['status'] = $request->status;
        $input['parent_id'] = $request->parent_id;

        //file upload
        if ($image = $request->file('cover')) {
            $image_name = Str::slug($request->name).".".$image->getClientOriginalExtension();
            $path = public_path('/uploads/product_categories/' . $image_name);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['cover'] = $image_name;
        }

        ProductCategory::create($input);

        return redirect()->route('admin.product_categories.index')->with([
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
        if(!auth()->user()->ability('admin', 'display_product_categories')){
            return redirect()->route('admin.index');
        }

        return view('backend.product_categories.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategory $productCategory)
    {
        if(!auth()->user()->ability('admin', 'update_product_categories')){
            return redirect()->route('admin.index');
        }

        $main_categories = ProductCategory::whereNull('parent_id')->get(['id','name']);
        return view('backend.product_categories.edit',compact('main_categories','productCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCategoryRequest $request, ProductCategory $productCategory)
    {
        if(!auth()->user()->ability('admin', 'update_product_categories')){
            return redirect()->route('admin.index');
        }

        $input['name'] = $request->name;
        $input['slug'] = null;
        $input['status'] = $request->status;
        $input['parent_id'] = $request->parent_id;

        if($image = $request->file('cover')){
            if($productCategory->cover != null && File::exists('uploads/product_categories/'. $productCategory->cover)){
                unlink('uploads/product_categories/'. $productCategory->cover);
            }
            $image_name = Str::slug($request->name).".".$image->getClientOriginalExtension();
            $path = public_path('/uploads/product_categories/' . $image_name);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['cover'] = $image_name;
        }

        $productCategory->update($input);
        return redirect()->route('admin.product_categories.index')->with([
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
    public function destroy(ProductCategory $productCategory)
    {
        if(!auth()->user()->ability('admin', 'delete_product_categories')){
            return redirect()->route('admin.index');
        }

        if(File::exists('uploads/product_categories/'. $productCategory->cover)){
            unlink('uploads/product_categories/'. $productCategory->cover);
        }
        $productCategory->delete();

        return redirect()->route('admin.product_categories.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger'
        ]);
    }

    //delete the image from the file
    public function remove_image(Request $request)
    {
        if(!auth()->user()->ability('admin', 'delete_product_categories')){
            return redirect()->route('admin.index');
        }

        $category = ProductCategory::findOrFail($request->product_category_id);
        if(File::exists('uploads/product_categories/'. $category->cover)){
            unlink('uploads/product_categories/'. $category->cover);
            $category->cover = null;
            $category->save();
        }
        return true;
    }
}
