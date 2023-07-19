<!doctype html>
<html lang="en">
<head>
    <title>Inventory</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

{{--    @include('dashboard.layouts.vite')--}}
    @include('dashboard.layouts.header')
    @include('dashboard.layouts.css')
    @yield('css')
</head>
<body class="hold-transition skin-blue sidebar-mini" style="font-family: 'Arial Narrow', sans-serif">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a style="background: linear-gradient(270deg,#667eea 0,#667eea);" href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>IT</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>INVENTORY</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav style="background: linear-gradient(90deg,#667eea 0,#764ba2);" class="navbar navbar-static-top">
            @include('dashboard.layouts.nav')
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar" style="background: #34444c;">
        <!-- sidebar: style can be found in sidebar.less -->
        @include('dashboard.layouts.sidebar')
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield('content-header')
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            @yield('content')
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        @include('dashboard.layouts.footer')
    </footer>

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    {{--    <div class="control-sidebar-bg"></div>--}}
</div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
@include('dashboard.layouts.js')
@yield('js')
</body>

</html>
