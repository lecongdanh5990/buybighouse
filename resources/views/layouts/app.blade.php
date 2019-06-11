<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Change your destiny') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    {{-- font awesome --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>
<body>
    <div id="app">
       
    <div class="menu">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top" id="main-nav">
            <div class="container">
            <a href="/dashboard" class="navbar-brand">
                ChangeYourDestiny</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/target">Target</router-link>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">Next</a>
                        <div class="dropdown-menu">
                            <router-link class="nav-link dropsdown-item text-primary" to="/tomorrow">Tomorrow</router-link>
                            <router-link class="nav-link dropsdown-item text-primary" to="/in-week">In week</router-link>
                            <router-link class="nav-link dropsdown-item text-primary" to="/in-month">In month</router-link>
                        </div>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">Purpose</a>
                        <div class="dropdown-menu">
                            <router-link class="nav-link dropsdown-item text-primary" to="/purpose">Show all Purpose</router-link>
                            <router-link class="nav-link dropsdown-item text-primary" to="/purpose/add">Add new Purpose</router-link>
                        </div>
                    </li>
                    {{-- <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item">Link 1</a>
                            <a href="#" class="dropdown-item">Link 2</a>
                            <a href="#" class="dropdown-item">Link 3</a>
                        </div>
                    </li> --}}
                    
                    <li class="nav-item">
                        <router-link class="nav-link" to="/time-analytic">Time Analytic</router-link>
                    </li>
                
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <img src="{{asset('img/profile/'.Auth::user()->photo)}}" alt="">
                        </a>
                        <div class="dropdown-menu">
                            <router-link class="nav-link dropsdown-item text-primary" to="/user/profile">Profile</router-link>
                            <a class="nav-link dropsdown-item text-primary" href="/logout">Logout</a>
                        </div>
                    </li>
                    
                </ul>
            </div>
            </div>
        </nav>
    </div>
    <div class="content py-5" id="content">
        @yield('content')
    </div>
       
    </div>
</body>
</html>
