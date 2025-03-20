<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sebatam.com')</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('mentor/assets') }}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <script>
        document.addEventListener('DOMContentLoaded', function() {
        console.log('Available Alpine globals:');
        console.log('Alpine:', typeof window.Alpine);
        console.log('Focus:', typeof window.Focus);
        console.log('AlpineFocus:', typeof window.AlpineFocus);
    });
    </script>

    <style>
        body {
            padding-top: 70px;
        }

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }

        /* Gaya untuk tombol Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            /* Tombol disembunyikan secara default */
            z-index: 1000;
            /* Pastikan tombol di atas elemen lain */
            border-radius: 50%;
            /* Membuat tombol bulat */
            width: 50px;
            height: 50px;
            padding: 0;
            text-align: center;
            line-height: 50px;
        }
    </style>
    @yield('styles')
    @livewireStyles
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Sebatam.com</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Menu1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Menu 2</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Admin
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Post Category</a>
                            </li>
                            <li><a class="dropdown-item" href="#">Sub Menu 2</a></li>
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">Sub Menu 3</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Sub Sub Menu 1</a></li>
                                    <li><a class="dropdown-item" href="#">Sub Sub Menu 2</a></li>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item dropdown-toggle" href="#">Sub Sub Menu 3</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Sub Sub Sub Menu 1</a></li>
                                            <li><a class="dropdown-item" href="#">Sub Sub Sub Menu 2</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex">

                    @auth
                    <a href="{{ route('members.edit', Auth::user()->member->id) }}" class="btn btn-warning me-2">Edit
                        Profil</a>
                    <form action="{{route('logout')}}" method="POST">
                        @csrf
                        <button class="btn btn-success ">Logout</button>
                    </form>
                    @else
                    <a href="{{route('login')}}" class="btn btn-primary">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

    </div>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} Forum Laravel. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
    <script>
        // Script untuk mengaktifkan dropdown submenu
        document.querySelectorAll('.dropdown-submenu a.dropdown-toggle').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var submenu = this.nextElementSibling;
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            });
        });

        // Menutup submenu saat klik di luar
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function(submenu) {
                submenu.style.display = 'none';
            });
        });
    </script>
    @yield('scripts')
    <!-- Tombol Back to Top -->
    <button id="backToTopBtn" title="Go to top" class="btn btn-primary btn-lg back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Tampilkan atau sembunyikan tombol Back to Top berdasarkan posisi scroll
    window.onscroll = function() {
        scrollFunction();
    };

    function scrollFunction() {
        var backToTopBtn = document.getElementById("backToTopBtn");
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            backToTopBtn.style.display = "block";
        } else {
            backToTopBtn.style.display = "none";
        }
    }

    // Fungsi untuk kembali ke atas saat tombol diklik
    document.getElementById("backToTopBtn").addEventListener("click", function() {
        document.body.scrollTop = 0; // Untuk Safari
        document.documentElement.scrollTop = 0; // Untuk Chrome, Firefox, IE, dan Opera
    });
    </script>


    @livewireScripts



</body>

</html>