<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\ProductCategory;
use App\Models\Tag;
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
        if (!request()->is('admin/*')) {
            view()->composer('*',function($view){
                if (!Cache::has('shop_categories_menu')){
                    Cache::forever('shop_categories_menu', ProductCategory::tree());
                }
                if (!Cache::has('shop_tags_menu')){
                    Cache::forever('shop_tags_menu', Tag::whereStatus(true)->get());
                }

                $shop_categories_menu = Cache::get('shop_categories_menu');
                $shop_tags_menu = Cache::get('shop_tags_menu');

                $view->with([
                    'shop_categories_menu' => $shop_categories_menu,
                    'shop_tags_menu' => $shop_tags_menu,
                ]);

            });
        }

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
