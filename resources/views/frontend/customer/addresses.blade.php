@extends('layouts.app')
@section('content')
    <!-- HERO SECTION-->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row px-4 px-lg-5 py-lg-4 align-items-center">
                <div class="col-lg-6">
                    <h1 class="h2 text-uppercase mb-0">{{ auth()->user()->full_name }} Profile</h1>
                </div>
                <div class="col-lg-6 text-lg-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-lg-end mb-0 px-0">
                            <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customer.profile') }}">Profile</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5">

        <div class="row">
            <div class="col-lg-8">
                <h2 class="h5 text-uppercase mb-4">General Information</h2>

                <livewire:frontend.customer.addresses />
            </div>


            @include('frontend.customer.partial.sidebar')
        </div>


    </section>
@stop
