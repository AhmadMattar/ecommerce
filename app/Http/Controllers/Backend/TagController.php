<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // put the permission and role using "ability"
        if(!auth()->user()->ability('admin', 'manage_tags, show_tags')){
            return redirect()->route('admin.index');
        }
        
        $tags = Tag::with('products')
            //search in Tags
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limit_by ?? 10);
        return view('backend.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->ability('admin', 'create_tags')){
            return redirect()->route('admin.index');
        }

        return view('backend.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        if(!auth()->user()->ability('admin', 'create_tags')){
            return redirect()->route('admin.index');
        }

        /*
            $request->validate() this code mean that:
                    insert all fields that i validate it at the validation time
        */
        // Tag::create([$request->validate()]);
        Tag::create([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect()->route('admin.tags.index')->with([
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
        if(!auth()->user()->ability('admin', 'display_tags')){
            return redirect()->route('admin.index');
        }

        return view('backend.tags.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        if(!auth()->user()->ability('admin', 'update_tags')){
            return redirect()->route('admin.index');
        }


        return view('backend.tags.edit',compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, Tag $tag)
    {
        if(!auth()->user()->ability('admin', 'update_tags')){
            return redirect()->route('admin.index');
        }

        $input['name'] = $request->name;
        $input['slug'] = null;
        $input['status'] = $request->status;

        $tag->update($input);
        return redirect()->route('admin.tags.index')->with([
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
    public function destroy(Tag $tag)
    {

        $tag->delete();
        return redirect()->route('admin.tags.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger'
        ]);
    }
}
