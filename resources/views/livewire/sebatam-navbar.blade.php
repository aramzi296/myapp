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
                    <a class="nav-link" href="#">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Menu</a>
                </li>


                {{-- Pencari kerja --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Pencaker
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="/profils">Profil</a></li>
                        <li><a class="dropdown-item" href="/uploads">Upload R2 Multi File</a></li>
                        <li><a class="dropdown-item" href="/files">R2 File Management</a></li>
                        <li><a class="dropdown-item" href="{{ route('gfiles.index')}}">Gdrive File Management</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('articles.index') }}">Article with TiniMCE</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('categories.index') }}">Article Category</a>
                        </li>
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">Article</a>
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



                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Admin
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="/welcome-email">Send Welcome Email</a></li>
                        <li><a class="dropdown-item" href="/uploads">Upload R2 Multi File</a></li>
                        <li><a class="dropdown-item" href="/files">R2 File Management</a></li>
                        <li><a class="dropdown-item" href="{{ route('gfiles.index')}}">Gdrive File Management</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('articles.index') }}">Article with TiniMCE</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('categories.index') }}">Article Category</a>
                        </li>
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">Article</a>
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
                <a href="#" class="btn btn-warning me-2">Edit
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