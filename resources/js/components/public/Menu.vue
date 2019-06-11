<template>
    <div class="menu">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top" id="main-nav">
            <div class="container">
            <a href="index.html" class="navbar-brand">
                ChangeYourDestiny</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <router-link class="nav-link" to="/dashboard">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/today">Today</router-link>
                </li>
                @can('isAdmin')
                <li class="nav-item">
                    <router-link class="nav-link" to="/dev">Developer</router-link>
                </li>
                @endcan
                <li class="nav-item dropdown">
                    <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">Purpose</a>
                    <div class="dropdown-menu">
                        <router-link class="nav-link dropsdown-item text-primary" to="/purpose">Show all Purpose</router-link>
                        <router-link class="nav-link dropsdown-item text-primary" to="/purpose/add">Add new Purpose</router-link>
                        <router-link class="nav-link dropsdown-item text-primary" to="/purpose/detail">Check detail of Purpose</router-link>
                    </div>
                </li>
                <!-- <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">Link 1</a>
                        <a href="#" class="dropdown-item">Link 2</a>
                        <a href="#" class="dropdown-item">Link 3</a>
                    </div>
                </li>
                 -->
                <li class="nav-item">
                    <router-link class="nav-link" to="/time-analytic">Time Analytic</router-link>
                </li>
                
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown show">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            Welcome Danh
                        </a>
                        <div class="dropdown-menu">
                            <router-link to="/user/profile" class="dropdown-item">Profile</router-link>
                            <router-link to="/logout" class="dropdown-item">Logout</router-link>
                            <router-link to="#" class="dropdown-item">Link 3</router-link>
                        </div>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="index.html">Logout</a>
                    </li>
                </ul>
            </div>
            </div>
        </nav>
    </div>
    
    
</template>

<style>
</style>
