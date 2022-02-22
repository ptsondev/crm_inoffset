<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />
    <link id="site-favicon" rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CRM 2022') }}</title>

    <script src="phpgrid/Content/js/jquery.min.js"></script>
    <script src="phpgrid/Content/css/jquery-ui/jquery-ui.min.js"></script>
    <script src="phpgrid/Content/js/jquery.form.js"></script>
    <script src="phpgrid/Content/js/pqgrid.min.js"></script>
    <script src="phpgrid/Content/js/pqselect.min.js"></script>
    <script src="phpgrid/Content/js/slib.js"></script>
    <script src="{{ asset('js/myjs.js?') }}{{ time() }}"></script>

	<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="phpgrid/Content/css/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="phpgrid/Content/css/jquery-ui/jquery-ui.css">
    <link rel="stylesheet" href="phpgrid/Content/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="phpgrid/Content/css/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="phpgrid/Content/css/jquery-ui.structure.min.css">

    <link rel="stylesheet" href="phpgrid/Content/css/pqgrid.min.css">
    <link rel="stylesheet" href="phpgrid/Content/css/pqselect.min.css">
    <link rel="stylesheet" href="phpgrid/Office/pqgrid.css">
    <link href="{{ asset('css/main.css?') }}{{ time() }}" rel="stylesheet">
</head>

<body>
    @include('layouts.header')
    @yield('content')
</body>

</html>
