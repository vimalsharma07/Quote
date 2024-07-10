<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables Core CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <!-- Custom Styles -->
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link:hover {
            color: #adb5bd;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }
        .content {
            padding: 20px;
        }
        .navbar {
            padding: 0.5rem 1rem;
        }
        .navbar-brand img {
            max-height: 50px;
        }
        .navbar-toggler {
            border: none;
        }
    </style>
</head>
<body>
    @php
    use Illuminate\Support\Facades\Session;
@endphp

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}" href="{{ url('admin/users') }}">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/quote-backgrounds/create') ? 'active' : '' }}" href="{{ url('admin/quote-backgrounds/create') }}">
                                <i class="fas fa-quote-left"></i> Create Background
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/quote/background') ? 'active' : '' }}" href="{{ url('admin/quote/background') }}">
                                <i class="fas fa-image"></i> Quotes Background
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-toggle="collapse" href="#settingsMenu" aria-expanded="false">
                                <i class="fas fa-cogs"></i> Tags
                            </a>
                            <div class="collapse" id="settingsMenu">
                                <ul class="nav flex-column pl-3">
                                    <!-- Logo Management -->
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/tag/create') ? 'active' : '' }}" href="{{ url('admin/tag/create') }}">
                                            <i class="fas fa-image"></i> Create Tag
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/tags') ? 'active' : '' }}" href="{{ url('admin/tags') }}">
                                            <i class="fas fa-image"></i>  Tags
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/categories') ? 'active' : '' }}" href="{{ url('admin/categories') }}">
                                <i class="fas fa-list"></i> Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-toggle="collapse" href="#settingsMenu" aria-expanded="false">
                                <i class="fas fa-cogs"></i> Settings
                            </a>
                            <div class="collapse" id="settingsMenu">
                                <ul class="nav flex-column pl-3">
                                    <!-- Logo Management -->
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/settings/logo') ? 'active' : '' }}" href="{{ url('admin/settings/logo') }}">
                                            <i class="fas fa-image"></i> Logo & Media
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.logout') }}">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- jQuery Full Version CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
    <!-- Popper.js for Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables Core JavaScript -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons Extension JavaScript -->
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <!-- JSZip for DataTables Buttons Extension -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <!-- PDFMake for DataTables Buttons Extension -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/2.5.0/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/2.5.0/vfs_fonts.js"></script>
    <!-- DataTables Buttons Extension PDF Export -->
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <!-- DataTables Buttons Extension Print Export -->
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
    <!-- DataTables Buttons Extension Copy Export -->
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>

    @yield('scripts')
</body>
</html>
