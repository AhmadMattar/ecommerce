<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Backend\ProductCategoryRequest;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ProductCategory::withCount('products')
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'asc')
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
        $input['name'] = $request->name;
        $input['status'] = $request->status;
        $input['parent_id'] = $request->parent_id;

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

        return view('backend.product_categories.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $main_categories = ProductCategory::whereNull('parent_id')->get(['id','name']);
        $category = ProductCategory::Find($id);
        return view('backend.product_categories.edit',compact('main_categories','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCategoryRequest $request, $id)
    {
        

        // dd($request->except('_token'));
        // $category = ProductCategory::Find($id);
        // $name = $category->cover;

        // ProductCategory::Find($id)->update([
        //     'name' => $request->name,
        //     'status' => $request->status,
        //     'parent_id' => $request->parent_id,
        // ]);

        // return redirect()->route('admin.product_categories.index')->with([
        //     'message' => 'Updated successfully',
        //     'alert-type' => 'success'
        // ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductCategory::Find($id)->delete();
        return redirect()->route('admin.product_categories.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger'
        ]);
    }
}
