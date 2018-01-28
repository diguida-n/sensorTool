<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="/img/favicon.png"/>
    <title>@yield('title')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
    <link href="/css/agency.min.css" rel="stylesheet">
    
    <style>

        .navbar-brand {
            padding:0;
        }
        .dropdown-toggle{
            background-color: transparent !important;
        }
        .navbar-custom .nav li a{
            color: #ffffff;
        }
        .navbar-custom .nav li a:focus, .navbar-custom .nav li a:hover{
            color: #fed136;
            background-color: #222;
        }
        
        .navbar-custom .navbar-toggle:focus,
        .navbar-custom .navbar-toggle:hover {
            background-color: #0289e2;
        }
        .navbar-custom .navbar-toggle {
            background-color: #0289e2;
            border-color: #0289e2;
        }

        .navbar-default .navbar-toggle .icon-bar {
            background-color: white;
        }
    </style>
    @yield('style')
</head>
<body class="index" id="page-top">
    <div id="app">
        @include('layouts.navbar')

        @yield('content')

        @include('layouts.footer')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    @yield('script')
</body>
</html>
