@extends('layouts.admin')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Customers</h6>
        <div class="ml-auto">
            @ability('admin', 'create_customers')
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add New Customer</span>
            </a>
            @endability
        </div>
    </div>

    @include('backend.customers.filter.filter')

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email & Mobile</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-center" style="width: 30px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>
                            @if ($customer->user_image != '')
                                <img src="{{ asset('uploads/users/'. $customer->user_image) }}" width="60" height="60" alt="{{ $customer->full_name }}">
                            @else
                                <img src="{{ asset('uploads/users/avatar.png') }}" width="60" height="60" alt="{{ $customer->full_name }}">
                            @endif
                        </td>
                        <td>
                            {{$customer->full_name}}<br>
                            <strong>{{$customer->username}}</strong>
                        </td>
                        <td>
                            {{$customer->email}}<br>
                            {{$customer->mobile}}
                        </td>
                        <td>{{$customer->status()}}</td>
                        <td>{{$customer->created_at->format('Y-m-d')}}</td>
                        <td>
                            <div class="btn-group  btn-group-sm">
                                <a href="{{route('admin.customers.edit', $customer->id)}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0);"
                                   onclick="if(confirm('Are you sure to delete this record?')) { document.getElementById('delete-product-customer-{{$customer->id}}').submit(); } else {return false;} "
                                   class="btn btn-danger">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <form action="{{route('admin.customers.destroy', $customer->id)}}" method="POST" id="delete-product-customer-{{$customer->id}}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No Customers Found</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="float-right">
                            {{$customers->appends(request()->all())->links()}}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@stop
