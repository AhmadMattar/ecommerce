<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers{
        logout as protected originalLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    // override the username() method from the (AuthenticatesUsers trait) to login with username
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    // override the redirecTo() method from the (AuthenticatesUsers trait / RedirectsUsers) to redirect the user to the view
    public function redirectTo()
    {
        if(auth()->user()->roles()->first()->allowed_route != ''){
            return $this->redirectTo = auth()->user()->roles()->first()->allowed_route . '/index';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $cart = collect($request->session()->get('cart'));

        //call original logout
        $response = $this->originalLogout($request);

        //repopulate session with cart
        if(!config('cart.destroy_on_logout')){
            $cart->each(function($rows, $identifier) use ($request){
                $request->session()->put('cart.' . $identifier, $rows);
            });
        }

        return $response;
    }

    protected function loggedOut(Request $request)
    {
        Cache::forget('admin_side_menu');
        Cache::forget('role_routes');
        Cache::forget('user_routes');
    }
}
