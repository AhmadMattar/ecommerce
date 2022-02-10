<?php
// General Helper
use Illuminate\Support\Facades\Cache;

// get the parent show page
function getParentShowOf($parameter){
    // delete "admin." from the route name ($parameter)
    $route = str_replace('admin.', '', $parameter);
    /*
        get the permission for the "admin_side_menu" from the cache or model:
        we use here the cache becuse we caching it in the ViewServiceProvider
    */
    $permission = Cache::get('admin_side_menu')->where('as',$route)->first();

    return $permission ? $permission->parent_show : $route;
}
// get the secondary permission
function getParentOf($parameter){
    $route = str_replace('admin.', '', $parameter);
    $permission = Cache::get('admin_side_menu')->where('as',$route)->first();
    // if the permission is found return the parent of it
    return $permission ? $permission->parent : $route;
}

function getParentIdOf($parameter){
    $route = str_replace('admin.', '', $parameter);
    $permission = Cache::get('admin_side_menu')->where('as',$route)->first();
    return $permission ? $permission->id : null;
}
