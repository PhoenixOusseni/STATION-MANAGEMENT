<!DOCTYPE html>
<html lang="fr">

<head>
    @include('partials.meta')
    <title>STATION MANAGER</title>
    @yield('style')
    @include('partials.style')
    <style>
        .inset-0 {
            z-index: 999999999 !important;
        }

        .btn-1 {
            background-color: #8b1a2e;
            color: white;
        }

        .page-header {
            background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);
        }

        .form-control {
            padding: 9px 12px;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #c41e3a;
            box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.1);
        }

        .form-select {
            padding: 9px 12px;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            border-color: #c41e3a;
            box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.1);
        }

        hr {
            border: 1px solid #020304;
        }
    </style>

<body class="nav-fixed">
    @include('partials.header')
    <div id="layoutSidenav_content">

        @yield('content')

        @include('partials.footer')
    </div>
    </div>
    @include('partials.script')
    @yield('script')
</body>

</html>
