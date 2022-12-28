<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Ecommerce Application">
    <meta name="author" content="Ahmed">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('favicon2.ico')}}">

    <title>{{ config('app.name', 'Laravel') }} Dashboard</title>



    <!-- Custom fonts for this template-->
    <link href="{{asset('backend/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{asset('backend/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('backend/vendor/bootstrap-input-file/css/fileinput.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/vendor/summernote/summernote-bs4.min.css')}}">
    <livewire:styles />
    @yield('style')
</head>
<body id="page-top">

    <div id="app">
        <!-- Page Wrapper -->
        <div id="wrapper">
            @include('partiacl.backend.sidebar')
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    @include('partiacl.backend.navbar')
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        @include('partiacl.backend.flash')
                        @yield('content')
                    </div>
                </div>
                @include('partiacl.backend.footer')
            </div>
        </div>
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        @include('partiacl.backend.modal')
    </div>
    <!-- Scripts -->
    <livewire:scripts />
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{asset('backend/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset('backend/js/sb-admin-2.min.js')}}"></script>
    <script src="{{asset('backend/js/custom.js')}}"></script>
    <script src="{{asset('backend/vendor/bootstrap-input-file/js/plugins/piexif.min.js')}}"></script>
    <script src="{{asset('backend/vendor/bootstrap-input-file/js/plugins/sortable.min.js')}}"></script>
    <script src="{{asset('backend/vendor/bootstrap-input-file/js/fileinput.min.js')}}"></script>
    <script src="{{asset('backend/vendor/bootstrap-input-file/themes/fas/theme.min.js')}}"></script>
    <script src="{{asset('backend/vendor/summernote/summernote-bs4.min.js')}}"></script>

    @yield('script')
</body>
</html>
