<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]> <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]> <html class="ie8 oldie"> <![endif]-->
<!--[if IE 9]> <html class="ie9 oldie"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html>
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <!--
  =============================================================================
  === PLANTILLA DESARROLLADA POR LA OFICINA DE MEDIOS DIGITALES - UNIMEDIOS ===
  =============================================================================
-->
    <!-- base href="http://subdominio.unal.edu.co/" -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="revisit-after" content="1 hour">
    <meta name="distribution" content="all">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.5, user-scalable=yes">
    <meta name="expires" content="1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="all">

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-theme.min.css') }}" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/reset.css') }}" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/unal.css') }}" media="all">

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" media="all">

    <title>@yield('title', 'Alert System')</title>

    @include('template.header')
</head>

<body>
<div id="app">
    <main class="detalle">
        <br><br><br>
        @yield('content')
    </main>
</div>

<script src="{{ asset(elixir('js/app.js'))  }}"></script>
<script src="{{ asset('js/unal.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/html5shiv.js') }}" type="text/javascript"></script>
{{--[endif]--}}
{{--<!--[if lt IE 9]>--}}
<script src="{{ asset('js/respond.js') }}" type="text/javascript"></script>
{{--<![endif]-->--}}

@yield('javascript')
</body>
</html>