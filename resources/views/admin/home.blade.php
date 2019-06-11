<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
    <nav id="app" class="admin">
            <header class="position-relative">
                <nav class="navbar text-white bg-dark mb-3">
                    <a  href="index.html" class="navbar-brand">
                    Change your Destiny</a>
                
                        
                    <ul class="ml-auto navbar-nav flex-row">
                    <li id="rss-widget" class="nav-item dropdown mr-2">
                        <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-wifi fa-2x"></i>
                            <span class="badge badge-warning display-none"></span>
                        </a>
                            
                    </li>
                    
                    <li id="twitter-widget" class="nav-item dropdown mr-2">
                        <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-sms fa-2x"></i>
                            <span class="badge badge-info display-none"></span>
                        </a>
                        
                    </li>
                        
                    <li id="messages-widget" class=" nav-item mr-2 dropdown">
                        <a href="javascript:void(0)" class="nav-link nav-item dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-bell fa-2x"></i>
                            <!-- If the <span> element with .badge class has no content it will disappear (not in IE8 and below because of using :empty in CSS) -->
                            <span class="badge badge-success">1</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right widget">
                            <li class="widget-heading"><i class="fa fa-comment pull-right"></i>Latest Messages</li>
                            <li class="new-on">
                                <div class="media">
                                    <a class="pull-left" href="javascript:void(0)">
                                        <img src="img/placeholders/image_light_64x64.png" alt="fakeimg">
                                    </a>
                                    <div class="media-body">
                                        <h6 class="media-heading"><a href="javascript:void(0)">George</a><span class="label label-success">2 min ago</span></h6>
                                        <div class="media">Thanks for your help! The tutorial really helped me a lot!</div>
                                    </div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="media">
                                    <a class="pull-left" href="javascript:void(0)">
                                        <img src="img/placeholders/image_light_64x64.png" alt="fakeimg">
                                    </a>
                                    <div class="media-body">
                                        <h6 class="media-heading"><a href="javascript:void(0)">Mike</a><span class="label label-default">6 hours ago</span></h6>
                                        <div class="media">The logo is ready, have a look and let me know what you think!</div>
                                    </div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="media">
                                    <a class="pull-left" href="javascript:void(0)">
                                        <img src="img/placeholders/image_light_64x64.png" alt="fakeimg">
                                    </a>
                                    <div class="media-body">
                                        <h6 class="media-heading"><a href="javascript:void(0)">Julia</a><span class="label label-default">1 day ago</span></h6>
                                        <div class="media">We should better consider our social media marketing strategy!</div>
                                    </div>
                                </div>
                            </li>
                            
                            <li class="text-center"><a href="page_inbox.html">View All Messages</a></li>
                        </ul>
                    </li>

                    
                    <ul class="navbar-nav pull-right d-sm-none mr-2">
                        
                        <li>
                            <!-- It is set to open and close the main navigation on smaller screens. The class .navbar-main-collapse was added to aside#page-sidebar -->
                            <a class="nav-link" href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-main-collapse">
                                <i class="fa fa-bars fa-2x"></i>
                            </a>

                            
                        </li>
                    </ul>
                    
                </ul>
                
            </nav>
            {{-- hidden menu for mobile device --}}
            <nav id="hiddenMenu" class="bg-white text-danger position-absolute d-md-none navbar-collapse navbar-main-collapse">
                <ul class="list-unstyled">
                    <li class="nav-item dropdown ">
                        <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="mx-3 fa fa-table"></i>User</a>
                        <div class="dropdown-menu">
                            <router-link class="nav-link dropsdown-item text-primary" to="/admin/user">Show all User</router-link>
                            <router-link class="nav-link dropsdown-item text-primary" to="/admin/admin">Show all Admin</router-link>
                        </div>
                    </li>
                    <li class="nav-item dropdown ">
                        <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="mx-3 fas fa-bullseye"></i>Nothing here</a>
                        <div class="dropdown-menu">
                            <router-link class="nav-link dropsdown-item text-primary" to="/status">Show all User</router-link>
                            <router-link class="nav-link dropsdown-item text-primary" to="/purpose">Show all Admin</router-link>
                        </div>
                    </li>
                </ul>
            </nav>
            </header>
            
            <section class="main" id="main">
                <div class="row">
                <div class="col-md-3 d-none d-sm-block ">
                    <nav id="primary-nav">
                        <ul>
                    <li class="nav-item dropdown">
                        <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-table"></i>User</a>
                        <div class="dropdown-menu">
                            <router-link class="nav-link dropsdown-item text-primary" to="/status">Show all User</router-link>
                            <router-link class="nav-link dropsdown-item text-primary" to="/purpose">Show all Admin</router-link>
                        </div>
                    </li>
                </ul>
                    </nav>
                    <!-- END Primary Navigation -->
                </div>

                <div class="col-md-9">
                    <router-view></router-view>
                </div>
            </div>
            </section>
        
            
        
        {{-- <main class="py-4">
            @yield('content')
        </main> --}}
    </nav>
</body>
</html>
