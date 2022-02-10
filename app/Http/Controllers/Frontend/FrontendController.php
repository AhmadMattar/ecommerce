<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        return view('front-end.index');
    }

    public function cart()
    {
        return view('front-end.cart');
    }

    public function checkout()
    {
        return view('front-end.checkout');
    }

    public function detail()
    {
        return view('front-end.detail');
    }

    public function shop()
    {
        return view('front-end.shop');
    }
}
