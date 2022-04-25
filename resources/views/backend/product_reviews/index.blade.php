@extends('layouts.admin')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">Product Reviews</h6>
        {{-- <div class="ml-auto">
            @ability('admin', 'create_product_reviews')
            <a href="{{ route('admin.product_reviews.create') }}" class="btn btn-primary">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add New Coupon</span>
            </a>
            @endability
        </div> --}}
    </div>
    
    @include('backend.product_reviews.filter.filter')

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Message</th>
                    <th>Rating</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-center" style="width: 30px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr>
                        <td>
                            {{$review->name }}<br>
                            {{$review->email }}<br>
                            <small>{!! $review->user_id != '' ? $review->user->full_name : '' !!}</small>
                        </td>
                        <td>
                            {{$review->title}}<br>
                            @php
                                echo substr($review->message, 0, 50).' ...';
                            @endphp
                        </td>
                        <td>
                            <span class="badge badge-success">{{$review->rating}}</span>
                        </td>
                        <td>{{$review->product->name}}</td>
                        <td>{{$review->status()}}</td>
                        <td>{{$review->created_at->format('y-m-d h:i a')}}</td>
                        <td>
                            <div class="btn-group  btn-group-sm">
                                <a href="{{route('admin.product_reviews.edit', $review->id)}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0);"
                                   onclick="if(confirm('Are you sure to delete this record?')) { document.getElementById('delete-product-review-{{$review->id}}').submit(); } else {return false;} "
                                   class="btn btn-danger">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <form action="{{route('admin.product_reviews.destroy', $review->id)}}" method="POST" id="delete-product-review-{{$review->id}}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No reviews found</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="float-right">
                            {!! $reviews->appends(request()->all())->links() !!}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@stop
