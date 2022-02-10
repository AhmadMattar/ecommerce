<?php

namespace App\Models;

use Mindscms\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $guarded = [];

    public function parent()
    {
        return $this->hasOne(Permission::class, 'id', 'parent');
    }

    //get all children
    public function children()
    {
        return $this->hasMany(Permission::class, 'parent', 'id');
    }

    //get the children that appeard in the menu
    public function appeardChildren()
    {
        return $this->hasMany(Permission::class, 'parent', 'id')->where('appear', 1);
    }

    public static function tree( $level = 1 )
    {
        return static::with(implode('.', array_fill(0, $level, 'children')))
            ->whereParent(0)
            ->whereAppear(1)
            ->whereSidebarLink(1)
            ->orderBy('ordering', 'asc')
            ->get();
    }

    //get the permission other than the one that was get it from the role
    public static function assignedChildren( $level = 1 )
    {
        return static::with(implode('.', array_fill(0, $level, 'assignedChildren')))
            ->whereParentOriginal(0)
            ->whereAppear(1)
            ->orderBy('ordering', 'asc')
            ->get();
    }

}
