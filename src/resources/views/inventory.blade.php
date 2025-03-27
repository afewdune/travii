<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ถังเก็บปลา</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    @vite(['resources/css/app.scss','resources/js/app.js'])
</head>
<body id="traviiBG">
    <div id="app">
        <inventory-page :user="{{ json_encode(Auth::user()) }}"></inventory-page>
    </div>
    <effect></effect>
</body>
</html>