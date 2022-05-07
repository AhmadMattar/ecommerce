@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.update_account_settings', auth()->id()) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input type="text" name="first_name" value="{{old('first_name', auth()->user()->first_name)}}" class="form-control">
                        @error('first_name') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" name="last_name" value="{{old('last_name', auth()->user()->last_name)}}" class="form-control">
                        @error('last_name') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" value="{{old('username', auth()->user()->username)}}" class="form-control">
                        @error('username') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" value="{{old('email', auth()->user()->email)}}" class="form-control">
                        @error('email') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="mobile">Mobile</label>
                        <input type="text" name="mobile" value="{{old('mobile', auth()->user()->mobile)}}" class="form-control">
                        @error('mobile') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control">
                        @error('password') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-12">
                    <label for="user_image">User Image</label>
                    <br>
                    <div class="file-loading">
                        <input type="file" name="user_image" id="admin-image" class="file-input-overview">
                        <span class="form-text text-muted">Image width should be 500px x 500px</span>
                        @error('user_image')<span class="text-danger">{{$message}}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="form-group pt-4">
                <button type="submit" name="submit" class="btn btn-primary">Update Info</button>
            </div>
        </form>
    </div>
</div>
@stop
@section('script')
<script>
    $(function(){
       $("#admin-image").fileinput({
           theme: "fas",
           maxFileCount: 1,
           allowedFileTypes: ['image'],
           showCancel: true,
           showRemove: false,
           showUpload: false,
           overwriteInitial: false,
           initialPreview: [
                @if (auth()->user()->user_image != '')
                "{{ asset('uploads/users/'.auth()->user()->user_image) }}"
                @endif
           ],
           initialPreviewAsData: true,
           initialPreviewFileType: 'image',
           initialPreviewConfig: [
                @if (auth()->user()->user_image != '')
                {
                   caption: "{{ auth()->user()->user_image }}",
                    size: '1111',
                    width: "120px",
                    url: "{{ route('admin.remove_image', ['admin_id' => auth()->user()->id, '_token' => csrf_token()]) }}",
                    key: {{ auth()->id() }}
                }
                @endif
           ]
       });
    });
</script>
@stop

