<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote App</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #f8f9fa;
        }
        .navbar-brand img {
            height: 40px;
        }
        .nav-link, .navbar-brand, .dropdown-item {
            color: #000 !important;
        }
        .nav-link:hover, .dropdown-item:hover {
            color: #555 !important;
        }
        .form-control {
            border-radius: 20px;
        }
        .search-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            position: relative;
        }
        .search-container input[type="search"] {
            border-radius: 20px;
            padding-right: 35px;
            width: 300px;
        }
        .search-container .btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #000;
        }
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f8f9fa;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .bottom-nav .nav-link {
            flex-grow: 1;
            text-align: center;
            padding: 10px 0;
        }
        .bottom-nav .nav-link i {
            display: block;
            font-size: 20px;
        }
        .bottom-nav .nav-link span {
            font-size: 12px;
        }
        @media (max-width: 767px) {
            .bottom-nav {
                display: flex;
            }
            .navbar-nav {
                display: none;
            }
            .search-container {
                display: none;
            }
        }

        /* Footer Styles */
        footer {
            margin-top: 70px; /* Ensure footer is not overlapped by the bottom nav */
        }
        .footer-container {
            padding: 30px 0;
        }
        @media (max-width: 767px) {
            .footer-container {
                padding-bottom: 80px; /* Ensure space for bottom navigation bar on mobile */
            }
        }

        <style>
        .background-selector {
            height: 300px;
            background-size: cover;
            background-position: center;
            position: relative;
            margin-bottom: 20px;
        }
        .quote-text {
            position: absolute;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
            font-size: 24px;
            max-width: 90%;
            transform: translate(-50%, -50%);
        }
    </style>
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="path-to-your-logo.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Explore</a>
                    </li>
                </ul>
                <div class="search-container">
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </div>
                <ul class="navbar-nav ml-auto">
                    @if(Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Profile</a>
                            <a class="dropdown-item" href="#">Your Quotes</a>
                            <a class="dropdown-item" href="#">Liked Quotes</a>
                            <a class="dropdown-item" href="#">Your Orders</a>
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
        <a href="#" class="nav-link">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="#" class="nav-link">
            <i class="fas fa-compass"></i>
            <span>Explore</span>
        </a>
        <a href="{{url('/create-quote')}}" class="nav-link">
            <i class="fas fa-plus"></i>
            <span>Create Quote</span>
        </a>
        <a href="#" class="nav-link">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
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
                        <li><a href="#!" class="text-dark">Home</a></li>
                        <li><a href="#!" class="text-dark">Explore</a></li>
                        <li><a href="#!" class="text-dark">Profile</a></li>
                        <li><a href="#!" class="text-dark">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center p-3 bg-light">
            © 2024 Quote App
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
