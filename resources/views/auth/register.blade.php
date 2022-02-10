@extends('layouts.app')

@section('content')

<section class="py-5 bg-light">
    <div class="container">
        <div class="row px-4 px-lg-5 py-lg-4 align-items-center">
            <div class="col-lg-6">
                <h1 class="h2 text-uppercase mb-0">{{__('Register')}}</h1>
            </div>
            <div class="col-lg-6 text-lg-right">

            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="row">
        <div class="col-6 offset-3">
            <h2 class="h5 text-uppercase mb-4">{{__('Register')}}</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first_name" class="text-small text-uppercase">First Name</label>
                                <input type="text" class="form-control form-control-lg @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" placeholder="First Name">
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="last_name" class="text-small text-uppercase">Last Name</label>
                                <input type="text" class="form-control form-control-lg @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="username" class="text-small text-uppercase">Username</label>
                                <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Username">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
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
                                <label for="mobile" class="text-small text-uppercase">Mobile Number</label>
                                <input type="mobile" class="form-control form-control-lg @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile Number">
                                @error('mobile')
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
                                <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Re type youre password">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-dark">
                                    {{ __('Register') }}
                                </button>
                                @if (Route::has('login'))
                                    <a href="{{route('login')}}" class="btn btn-link">
                                        {{__('Have an account?')}}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                </form>
        </div>
    </div>
</section>
@endsection
