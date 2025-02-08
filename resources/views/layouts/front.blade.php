<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <title>Dolafz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/front/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/index.css') }}">

    
</head>
<body>
    <?php
    $media = DB::table('media')->first();
    ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                @if($media && $media->logo)
                <div class="logo">
                    <img src="{{ asset('storage/media/logos/' . basename($media->logo)) }}" alt="Logo" style="height: 50px; border-radius:27%">
                </div>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="search-container">
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </div>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('explore') ? 'active' : '' }}" href="{{ url('explore') }}">Explore</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('quotes/love') ? 'active' : '' }}" href="{{ url('quotes/love') }}">Love Quotes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('quotes/yaari') ? 'active' : '' }}" href="{{ url('quotes/yaari') }}">Yaari Quotes</a>
                    </li>
                    @auth
                    @php
                        $user = Auth::user();
                        $notifications = optional($user->notifications) ?? collect(); // Ensure it's a collection
                        $readNotifications = $notifications->filter(fn($notification) => is_null($notification->read_at));
                    @endphp
                
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownNotifications" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @if(isset($readNotifications) && $readNotifications->count() > 0)
                                <span class="badge badge-danger">{{ $readNotifications->count() }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right notification-menu" aria-labelledby="navbarDropdownNotifications">
                            @forelse($notifications as $notification)
                                <a class="dropdown-item notification-item" href="{{ $notification->link }}">
                                    <i class="fas fa-bell notification-icon"></i>
                                    <div class="notification-text">{{ $notification->data }}</div>
                                    <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                </a>
                            @empty
                                <p class="dropdown-item">No notifications</p>
                            @endforelse
                        </div>
                    </li>
                @endauth
                @if(Auth::user())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownUser">
                            <a class="dropdown-item" href="{{ url('profile/') }}">Profile</a>
                            <a class="dropdown-item" href="{{ url('your-quotes/') }}">Your Quotes</a>
                            <a class="dropdown-item" href="{{ url('liked-quotes/') }}">Liked Quotes</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign Out</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-user"></i>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <div class="bottom-nav">
        <a href="{{ url('/') }}" class="nav-link">
            <i class="fas fa-home"></i>
        </a>
        <a href="{{ url('quotes/yaari') }}" class="nav-link">
            <i class="fas fa-handshake"></i>
        </a>
        @if(Auth::check())
        <a href="{{ url('/create-quote') }}" class="nav-link">
            <i class="fas fa-plus"></i>
        </a>
        @else
        <a href="{{ url('/login') }}" class="nav-link">
            <i class="fas fa-plus"></i>
        </a>
        @endif
        @if(Auth::check())
        <a href="#" class="nav-link" id="navbarDropdownNotificationsMobile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell"></i>
            @if(Auth::check() &&  isset($user->notifications) && $user->notifications->count() > 0)
            <span class="badge badge-danger">{{ $user->notifications->count() }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right notification-menu" aria-labelledby="navbarDropdownNotificationsMobile">
            @forelse($user->notifications as $notification)
                <a class="dropdown-item notification-item {{ $notification->created_at}}" href="{{ $notification->link }}">
                    <i class="fas fa-bell notification-icon"></i>
                    <div class="notification-text">{{ $notification->data }}</div>
                    <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                </a>
            @empty
                <p class="dropdown-item">No notifications</p>
            @endforelse
        </div>
        @endif

        @endif
        @if(Auth::check())
        <a href="{{ url('profile/') }}" class="nav-link">
            <i class="fas fa-user"></i>
        </a>
        @else
        <a href="{{ url('profile/') }}" class="nav-link">
            <i class="fas fa-user"></i>
        </a>
        @endif
    </div>

    <footer class="bg-light text-center text-lg-start">
        <div class="container footer-container">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Quote App</h5>
                    <p>Find and share your favorite quotes.</p>
                </div>
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Links</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="{{ url('explore') }}" class="text-dark">Explore</a></li>
                        <li><a href="{{ url('quotes/love') }}" class="text-dark">Love Quotes</a></li>
                        <li><a href="{{ url('quotes/yaari') }}" class="text-dark">Yaari Quotes</a></li>
                        <li><a href="{{ url('profile/') }}" class="text-dark">Profile</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery (needed for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dom-to-image@2.6.0/src/dom-to-image.min.js"></script>


    <script  src="{{asset('assets/quotes/js/quote.js')}}"></script>
    <script  src="{{asset('assets/front/js/notification.js')}}"></script>


   
</body>
</html>
