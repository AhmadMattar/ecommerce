@extends('layouts.admin')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Supervisors</h6>
        <div class="ml-auto">
            @ability('admin', 'create_supervisors')
            <a href="{{ route('admin.supervisors.create') }}" class="btn btn-primary">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add New Supervisor</span>
            </a>
            @endability
        </div>
    </div>

    @include('backend.supervisors.filter.filter')

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
                @forelse ($supervisors as $supervisor)
                    <tr>
                        <td>
                            @if ($supervisor->user_image != '')
                                <img src="{{ asset('uploads/users/'. $supervisor->user_image) }}" width="60" height="60" alt="{{ $supervisor->full_name }}">
                            @else
                                <img src="{{ asset('uploads/users/avatar.png') }}" width="60" height="60" alt="{{ $supervisor->full_name }}">
                            @endif
                        </td>
                        <td>
                            {{$supervisor->full_name}}<br>
                            <strong>{{$supervisor->username}}</strong>
                        </td>
                        <td>
                            {{$supervisor->email}}<br>
                            {{$supervisor->mobile}}
                        </td>
                        <td>{{$supervisor->status()}}</td>
                        <td>{{$supervisor->created_at->format('Y-m-d')}}</td>
                        <td>
                            <div class="btn-group  btn-group-sm">
                                <a href="{{route('admin.supervisors.edit', $supervisor->id)}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0);"
                                   onclick="if(confirm('Are you sure to delete this record?')) { document.getElementById('delete-product-supervisor-{{$supervisor->id}}').submit(); } else {return false;} "
                                   class="btn btn-danger">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <form action="{{route('admin.supervisors.destroy', $supervisor->id)}}" method="POST" id="delete-product-supervisor-{{$supervisor->id}}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No Supervisors Found</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="float-right">
                            {{$supervisors->appends(request()->all())->links()}}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@stop
