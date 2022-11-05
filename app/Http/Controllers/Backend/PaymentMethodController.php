<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PaymentMethodRequest;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->ability('admin', 'manage_payment_methods, show_payment_methods')){
            return redirect()->route('admin.index');
        }

        $payment_methods = PaymentMethod::query()
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limit_by ?? 10);
        return view('backend.payment_methods.index', compact('payment_methods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->ability('admin', 'create_payment_methods')){
            return redirect()->route('admin.index');
        }
        return view('backend.payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentMethodRequest $request)
    {
        if(!auth()->user()->ability('admin', 'create_payment_methods')){
            return redirect()->route('admin.index');
        }

        PaymentMethod::create($request->validated());

        return redirect()->route('admin.payment_methods.index')->with([
            'message' => 'Something wrong!',
            'alert-type' => 'danger',
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentMethod $payment_method)
    {
        if(!auth()->user()->ability('admin', 'display_payment_methods')){
            return redirect()->route('admin.index');
        }

        return view('backend.payment_methods.show', compact('payment_method'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMethod $payment_method)
    {
        if(!auth()->user()->ability('admin', 'update_payment_methods')){
            return redirect()->route('admin.index');
        }
        return view('backend.payment_methods.edit', compact('payment_method'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentMethodRequest $request, PaymentMethod $payment_method)
    {
        if(!auth()->user()->ability('admin', 'update_payment_methods')){
            return redirect()->route('admin.index');
        }
        
        $payment_method->update([
            'name'              => $request->name,
            'code'              => $request->code,
            'driver_name'       => $request->driver_name,
            'merchant_email'    => $request->merchant_email,
            'username'          => $request->username,
            'password'          => $request->password,
            'secret'            => $request->secret,
            'sandbox_username'  => $request->sandbox_merchant_email,
            'sandbox_password'  => $request->sandbox_client_id,
            'sandbox_secret'    => $request->sandbox_client_secret,
            'sandbox'           => $request->sandbox,
            'status'            => $request->status,
        ]);

        return redirect()->route('admin.payment_methods.index')->with([
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
    public function destroy(PaymentMethod $payment_method)
    {
        if(!auth()->user()->ability('admin', 'delete_payment_methods')){
            return redirect()->route('admin.index');
        }

        $payment_method->delete();

        return redirect()->route('admin.payment_methods.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'danger',
        ]);
    }
}
