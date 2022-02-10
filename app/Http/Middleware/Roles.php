<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //get the full route name
        $routeName = Route::getFacadeRoot()->current()->uri();
        //divide the route name by '/' and put it in an array
        $route = explode('/', $routeName);

        //if the user has logged in do...
        if (auth()->check()) {
            if (!in_array($route[0], $this->roleRoutes())) {
                return $next($request);
            } else {
                if ($route[0] != $this->userRoutes()) {
                    $path = $route[0] == $this->userRoutes() ? $route[0].'.login' : '' . $this->userRoutes().'.index';
                    return redirect()->route($path);
                } else {
                    return $next($request);
                }
            }
        }
        //else(not logged in) do...
        else {
            
            $routeDestination = in_array($route[0], $this->roleRoutes()) ? $route[0].'.login' : 'login';
            $path = $route[0] != '' ? $routeDestination : $this->userRoutes().'.index';
            return redirect()->route($path);
        }
    }

    //get the allowed routes from the DB roles table if it (Null or NOT Null) and return it as an array
    protected function roleRoutes()
    {
        if (!Cache::has('role_routes')) {
            Cache::forever('role_routes', Role::distinct()->whereNotNull('allowed_route')->pluck('allowed_route')->toArray());
        }
        return Cache::get('role_routes');
    }

    protected function userRoutes()
    {
        if (!Cache::has('user_routes')) {
            Cache::forever('user_routes', auth()->user()->roles[0]->allowed_route);
        }
        return Cache::get('user_routes');
    }
}
