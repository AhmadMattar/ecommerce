<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //check if the request is admin/...
        if (request()->is('admin/*')) {
            //cache the permissions
            view()->composer('*',function($view){
                //check if the permissions are cached
                if (!Cache::has('admin_side_menu')){
                    Cache::forever('admin_side_menu', Permission::tree());
                }

                $admin_side_menu = Cache::get('admin_side_menu');

                //pass the permissions to the view
                $view->with([
                    'admin_side_menu' => $admin_side_menu
                ]);

            });
        }
    }
}
