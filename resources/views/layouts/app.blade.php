<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    <title>Instagram</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/filters.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    
</head>
<div id="app">
    @auth
    <div id="sidebar" class="bg-primary text-white">
        <div class="sidebar-header p-3">
            <h5 class="mb-0" style="font-size: 22px;"> Menu</h5>
        </div>
        <ul class="list-unstyled">
            <li>
                <a href="{{ route('insta.feed') }}" class="d-flex align-items-center p-3 text-white sidebar-item" style="font-size: 16px;">
                    <i class="fa-solid fa-house me-5"></i>
                    <span class="mx-2">Feed</span>
                </a>
            </li>
            <li>
                <a href="{{ route('chat.list') }}" class="d-flex align-items-center p-3 text-white sidebar-item" style="font-size: 16px;">
                    <i class="fa-solid fa-comments me-5"></i>
                    <span class="mx-2"> Messages</span>
                </a>
            </li>
        </ul>
    </div>
    @endauth

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid w-75">
            <a style="font-size:19px;" class="navbar-brand" href="{{ url('/') }}">
                <h2><i class="fa-brands fa-instagram fa-1x"></i> Instagram</h2>
            </a>

            <div class="d-flex align-items-center ms-auto">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <div class="search-container me-3">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search..." autocomplete="off" />
                        <input type="hidden" id="authUserId" value="{{ Auth::user()->id }}">
                        <ul id="searchResults" class="list-group" style="position: absolute; z-index: 10; width: 100%; display: none;"></ul>
                    </div>

                    <a href="{{ route('home') }}" class="nav-link me-3">
                        <i class="far fa-user-circle fa-2x"></i>
                    </a>

                    <a href="#" id="signout" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt fa-2x"></i> 
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                    <a href="#" id="toggle-sidebar" class="nav-link" onclick="toggleSidebar()">
                        <i class="fa-solid fa-bars fa-2x"></i>
                    </a>

                @endguest
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('front/js/jquery-3.5.1.slim.min.js') }}"></script>
<script src="{{ asset('front/js/popper.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js" integrity="sha512-7Pi/otdlbbCR+LnW+F7PwFcSDJOuUJB3OxtEHbg4vSMvzvJjde4Po1v4BR9Gdc9aXNUNFVUY+SK51wWT8WF0Gg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script src="https://kit.fontawesome.com/c49ef8856d.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.4/dist/echo.iife.js"></script>
<script src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>
