@extends('layouts.app')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row px-4 px-lg-5 py-lg-4 align-items-center">
            <div class="col-lg-6">
                <h1 class="h2 text-uppercase mb-0">{{ __('Reset Password') }}</h1>
            </div>
            <div class="col-lg-6 text-lg-right">

            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="row">
        <div class="col-6 offset-3">
            <h2 class="h5 text-uppercase mb-4">{{ __('Reset Password') }}</h2>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="email" class="text-small text-uppercase">E-Mail Address</label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="E-Mail Address">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="password" class="text-small text-uppercase">Password</label>
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="Enter youre password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="password_confirmation" class="text-small text-uppercase">Confirm Password</label>
                            <input type="password" class="form-control form-control-lg" name="password_confirmation" placeholder="Re type youre password">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
