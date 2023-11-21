<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#"
                    data-toggle="sidebar"
                    class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#"
                data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image"
                    src="{{ asset('img/avatar/avatar-1.png') }}"
                    class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Logged</div>
                <a href="{{ route('user.profile') }}"
                    class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                @if (auth()->user()->isAdmin && auth()->user()->isHasOutlet)
                <a href="/outlet/desync-outlet"
                    class="dropdown-item has-icon text-danger">
                    <i class="fa fa-door-closed"></i> Desync Outlet
                </a>
                @endif
                <form action="/logout" method="POST" class="form-inline">
                    @csrf
                    <button class="dropdown-item has-icon text-danger btn-logout">
                        Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
