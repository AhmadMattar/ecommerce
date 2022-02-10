@php
    // get the current route name
    $current_route = \Route::currentRouteName();
@endphp
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('admin.index')}}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{config('app.name')}}</div>
    </a>

    <!-- Divider -->

    <hr class="sidebar-divider my-0">
    @role(['admin'])
        @foreach($admin_side_menu as $item)
            {{-- chek if the menu has a child or not if "yes" show it in the second <li> if "no" show it in the first one --}}
            {{-- the second <li> have a dropdown property --}}
            @if (count($item->appeardChildren) == 0)
                <!-- Nav Item - Dashboard -->
                <li class="nav-item {{$item->id == getParentShowOf($current_route) ? 'active' : ''}}">
                    <a class="nav-link" href="{{route('admin.'.$item->as)}}">
                        <i class="{{$item->icon != '' ? $item->icon : 'fas fa-home'}}"></i>
                        <span>{{$item->display_name}}</span>
                    </a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">
            @else
                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item {{in_array($item->parent_show, [getParentShowOf($current_route), getParentOf($current_route)]) ? 'active' : ''}}">
                    <a class="nav-link {{in_array($item->parent_show, [getParentShowOf($current_route), getParentOf($current_route)]) ? 'collapsed' : ''}}" href="#" data-toggle="collapse" data-target="#collapse_{{$item->route}}" aria-expanded="{{$item->parent_show == getParentOf($current_route) && getParentOf($current_route) != '' ? 'false' : 'true'}}" aria-controls="collapse_{{$item->route}}">
                        <i class="{{$item->icon != '' ? $item->icon : 'fas fa-home'}}"></i>
                        <span>{{$item->display_name}}</span>
                    </a>
                    {{-- check if that found apeared children and the count of it > 0 --}}
                    @if ($item->appeardChildren !== null && count($item->appeardChildren) > 0)
                        <div id="collapse_{{$item->route}}" class="collapse {{in_array($item->parent_show, [getParentShowOf($current_route), getParentOf($current_route)]) ? 'show' : ''}}" aria-labelledby="heading_{{$item->route}}" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                {{-- get the appeard children and add it to this menu --}}
                                @foreach($item->appeardChildren as $child)
                                    <a class="collapse-item {{getParentOf($current_route) != null && (int)(getParentIdOf($current_route)+1 == $child->id ? 'active' : '')}}" href="{{route('admin.'.$child->as)}}">{{$child->display_name}}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </li>
            @endif
        @endforeach
    @endrole

    @role(['SuperVisor'])
        @foreach($admin_side_menu as $item)
            @permission($item->name)
            @if (count($item->appeardChildren) == 0)
                <!-- Nav Item - Dashboard -->
                <li class="nav-item {{$item->id == getParentShowOf($current_route) ? 'active' : ''}}">
                    <a class="nav-link" href="{{route('admin.'.$item->as)}}">
                        <i class="{{$item->icon != '' ? $item->icon : 'fas fa-home'}}"></i>
                        <span>{{$item->display_name}}</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">
            @else
                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item {{in_array($item->parent_show, [getParentShowOf($current_route), getParentOf($current_route)]) ? 'active' : ''}}">
                    <a class="nav-link {{in_array($item->parent_show, [getParentShowOf($current_route), getParentOf($current_route)]) ? 'collapsed' : ''}}" href="#" data-toggle="collapse" data-target="#collapse_{{$item->route}}"
                        aria-expanded="{{$item->parent_show == getParentOf($current_route) && getParentOf($current_route) != '' ? 'false' : 'true'}}" aria-controls="collapse_{{$item->route}}">
                        <i class="{{$item->icon != '' ? $item->icon : 'fas fa-home'}}"></i>
                        <span>{{$item->display_name}}</span>
                    </a>
                    @if ($item->appeardChildren !== null && count($item->appeardChildren) > 0)
                        <div id="collapse_{{$item->route}}" class="collapse {{in_array($item->parent_show, [getParentShowOf($current_route), getParentOf($current_route)]) ? 'show' : ''}}" aria-labelledby="heading_{{$item->route}}" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                @foreach($item->appeardChildren as $child)
                                    @permission($child->name)
                                    <a class="collapse-item {{getParentOf($current_route) != null && (int)(getParentIdOf($current_route)+1 == $child->id ? 'active' : '')}}" href="{{route('admin.'.$child->as)}}">
                                        {{$child->display_name}}
                                    </a>
                                    @endpermission
                                @endforeach
                            </div>
                        </div>
                    @endif
                </li>
            @endif
            @endpermission
        @endforeach
    @endrole
</ul>
<!-- End of Sidebar -->
