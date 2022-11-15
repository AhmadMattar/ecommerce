@extends('layouts.admin')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
    </div>

    @include('backend.orders.filter.filter')

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>User</th>
                    <th>Payment method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created date</th>
                    <th class="text-center" style="width: 30px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->ref_id}}</td>
                        <td>{{ $order->user->full_name}}</td>
                        <td>{{ $order->payment_method->code}}</td>
                        <td>{{ $order->total}}</td>
                        <td>{!! $order->statusWithLabel() !!}</td>
                        <td>{{ $order->created_at->format('y-m-d') }}</td>
                        <td>
                            <div class="btn-group  btn-group-sm">
                                <a href="{{route('admin.orders.show', $order->id)}}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                <a href="javascript:void(0);"
                                   onclick="if(confirm('Are you sure to delete this record?')) { document.getElementById('delete-order-{{$order->id}}').submit(); } else {return false;} "
                                   class="btn btn-danger">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <form action="{{route('admin.countries.destroy', $order->id)}}" method="POST" id="delete-order-{{$order->id}}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No Orders Found</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="float-right">
                            {{$orders->appends(request()->all())->links()}}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@stop
