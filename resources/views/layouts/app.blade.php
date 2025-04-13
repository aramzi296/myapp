<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sebatam.com')</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">



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

    @livewire('sebatam-navbar')


    @yield('content')

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} Forum Laravel. All rights reserved.</p>
        </div>
    </footer>


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

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>

    @yield('scripts')

    @livewireScripts

</body>

</html>