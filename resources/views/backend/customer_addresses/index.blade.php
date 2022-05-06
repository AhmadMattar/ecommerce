@extends('layouts.admin')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Customer Addresses</h6>
        <div class="ml-auto">
            @ability('admin', 'create_customer_addresses')
            <a href="{{ route('admin.customer_addresses.create') }}" class="btn btn-primary">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add New Address</span>
            </a>
            @endability
        </div>
    </div>

    @include('backend.customer_addresses.filter.filter')

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Title</th>
                    <th>Shipping Info</th>
                    <th>Location</th>
                    <th>Address</th>
                    <th>Zip code</th>
                    <th>POBox</th>
                    <th class="text-center" style="width: 30px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customer_addresses as $customer_address)
                    <tr>
                        <td>
                            <a href="{{route('admin.customers.show', $customer_address->user_id)}}">{{$customer_address->user->full_name}}</a>
                        </td>
                        <td>
                            <a href="{{route('admin.customer_addresses.show', $customer_address->id)}}">{{$customer_address->address_title}}</a>
                            <p class="text-gray-400"><b>{{$customer_address->defaultAddress()}}</b></p>
                        </td>
                        <td>
                            {{$customer_address->first_name. ' ' .$customer_address->last_name}}
                            <p class="text-gray-400">{{$customer_address->email}}<br/>{{$customer_address->mobile}}</p>
                        </td>
                        <td>{{$customer_address->country->name. '-' .$customer_address->state->name. '-' .$customer_address->city->name}}</td>
                        <td>{{$customer_address->address}}</td>
                        <td>{{$customer_address->zip_code}}</td>
                        <td>{{$customer_address->po_box}}</td>
                        <td>
                            <div class="btn-group  btn-group-sm">
                                <a href="{{route('admin.customer_addresses.edit', $customer_address->id)}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0);"
                                   onclick="if(confirm('Are you sure to delete this record?')) { document.getElementById('delete-customer-address-{{$customer_address->id}}').submit(); } else {return false;} "
                                   class="btn btn-danger">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <form action="{{route('admin.customer_addresses.destroy', $customer_address->id)}}" method="POST" id="delete-customer-address-{{$customer_address->id}}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No Addresses Found</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">
                        <div class="float-right">
                            {{$customer_addresses->appends(request()->all())->links()}}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@stop
