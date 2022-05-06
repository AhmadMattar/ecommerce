<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CityRequest;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->ability('admin', 'manage_cities, show_cities')){
            return redirect()->route('admin.index');
        }

        $cities = City::query()
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limit_by ?? 10);
        return view('backend.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->ability('admin', 'create_cities')){
            return redirect()->route('admin.index');
        }

        $states = State::get(['id', 'name']);
        return view('backend.cities.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {
        if(!auth()->user()->ability('admin', 'create_cities')){
            return redirect()->route('admin.index');
        }

        City::create($request->validated());

        return redirect()->route('admin.cities.index')->with([
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
    public function show(City $city)
    {
        if(!auth()->user()->ability('admin', 'display_cities')){
            return redirect()->route('admin.index');
        }
        return view('backend.cities.show', compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        if(!auth()->user()->ability('admin', 'update_cities')){
            return redirect()->route('admin.index');
        }

        $states = State::get(['id', 'name']);
        return view('backend.cities.edit', compact('city', 'states'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request, City $city)
    {
        if(!auth()->user()->ability('admin', 'update_cities')){
            return redirect()->route('admin.index');
        }

        $city->update($request->validated());
        return redirect()->route('admin.cities.index')->with([
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
    public function destroy(City $city)
    {
        if(!auth()->user()->ability('admin', 'delete_cities')){
            return redirect()->route('admin.index');
        }

        $city->delete();
        return redirect()->route('admin.cities.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger'
        ]);
    }

    public function get_cities(Request $request)
    {
        $cities = City::whereStateId($request->state_id)->whereStatus(true)->get(['id', 'name'])->toArray();
        return response()->json($cities);
    }
}
