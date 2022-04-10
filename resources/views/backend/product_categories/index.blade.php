@extends('layouts.admin')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Product Categories</h6>
        <div class="ml-auto">
            @ability('admin', 'create_product_categories')
            <a href="{{ route('admin.product_categories.create') }}" class="btn btn-primary">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add New Category</span>
            </a>
            @endability
        </div>
    </div>

    @include('backend.product_categories.filter.filter')

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Product count</th>
                    <th>Parent</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-center" style="width: 30px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{$category->name}}</td>
                        <td>{{$category->products_count}}</td>
                        {{-- get the parent for the categories has a parent if has't print (-) --}}
                        <td>{{$category->parent != null ? $category->parent->name : '-' }}</td>
                        <td>{{$category->status()}}</td>
                        <td>{{$category->created_at}}</td>
                        <td>
                            <div class="btn-group  btn-group-sm">
                                <a href="{{route('admin.product_categories.edit', $category->id)}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0);"
                                   onclick="if(confirm('Are you sure to delete this record?')) { document.getElementById('delete-product-category-{{$category->id}}').submit(); } else {return false;} "
                                   class="btn btn-danger">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <form action="{{route('admin.product_categories.destroy', $category->id)}}" method="POST" id="delete-product-category-{{$category->id}}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No Categories Found</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="float-right">
                            {{-- appends(request()->all()): save the search results in the pagination --}}
                            {{$categories->appends(request()->all())->links()}}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@stop
