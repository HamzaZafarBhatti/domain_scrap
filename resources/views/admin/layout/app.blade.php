<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.0.0/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @yield('styles')
</head>
<style>
    .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
    border: none;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #0d6efd; /* Bootstrap primary color */
    color: white;
    margin-right: 5px; /* Space between pills */
    line-height: 20px; /* Adjust line height to vertically center text */
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white;
    margin-right: 4px;
    vertical-align: middle; /* Center the remove icon vertically */
}
.select2-selection__choice__remove {
    border: none!important;
}
</style>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{(dist/img/AdminLTELogo.png)}}" alt="AdminLTELogo" height="60"
                width="60">
        </div> --}}

        @include('admin.layout.header')

        @include('admin.layout.sidebar')


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page_name')</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        @include('admin.layout.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.0.0/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        @if (Session::has('success'))
            toastr.success("{{ session('success') }}")
        @endif
        @if (Session::has('error'))
            toastr.error("{{ session('error') }}")
        @endif
    </script>
    @yield('scripts')
</body>

</html>
