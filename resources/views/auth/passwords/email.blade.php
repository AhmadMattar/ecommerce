@extends('layouts.app')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row px-4 px-lg-5 py-lg-4 align-items-center">
            <div class="col-lg-6">
                <h1 class="h2 text-uppercase mb-0">{{__('Reset Password')}}</h1>
            </div>
            <div class="col-lg-6 text-lg-right">

            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="row">
        <div class="col-6 offset-3">
            <h2 class="h5 text-uppercase mb-4">{{__('Reset Password')}}</h2>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class=" row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="email" class="text-small text-uppercase">E-mail Address</label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="E-mail Address">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-dark">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
