<!doctype html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <title>{{ $title or "" }} - Avocado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="{{ asset('img/favicon.png') }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('semantic/semantic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
    <style>
        @stack('block-styles')
    </style>
</head>
<body>

@yield('content')

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="{{ asset('semantic/semantic.js') }}"></script>
@stack('before-scripts')
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>