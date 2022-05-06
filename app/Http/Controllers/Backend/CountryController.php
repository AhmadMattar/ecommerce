<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // put the permission and role using "ability"
        if(!auth()->user()->ability('admin', 'manage_countries, show_countries')){
            return redirect()->route('admin.index');
        }

        $countries = Country::with('states')
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limit_by ?? 10);
        return view('backend.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->ability('admin', 'create_countries')){
            return redirect()->route('admin.index');
        }

        return view('backend.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryRequest $request)
    {
        if(!auth()->user()->ability('admin', 'create_countries')){
            return redirect()->route('admin.index');
        }

        Country::create($request->validated());

        return redirect()->route('admin.countries.index')->with([
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
    public function show(Country $country)
    {
        if(!auth()->user()->ability('admin', 'dispaly_countries')){
            return redirect()->route('admin.index');
        }
        return view('backend.countries.show', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        if(!auth()->user()->ability('admin', 'update_countries')){
            return redirect()->route('admin.index');
        }

        return view('backend.countries.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CountryRequest $request, Country $country)
    {
        if(!auth()->user()->ability('admin', 'update_countries')){
            return redirect()->route('admin.index');
        }

        $country->update($request->validated());

        return redirect()->route('admin.countries.index')->with([
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
    public function destroy(Country $country)
    {
        if(!auth()->user()->ability('admin', 'delete_countries')){
            return redirect()->route('admin.index');
        }
        $country->delete();

        return redirect()->route('admin.countries.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger',
        ]);
    }
}
