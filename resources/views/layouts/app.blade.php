<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    @vite(['resources/css/app.scss','resources/js/app.js'])
</head>
<body>
    <div id="app">

                        @guest<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        
                            @if (Route::has('register'))
                               
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                
                            @endif
                        @else
                           
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

          
<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('ออกจากระบบ') }}</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                      

                        @endguest



        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>