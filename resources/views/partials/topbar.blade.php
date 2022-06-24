<!-- Topbar Start -->
<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-end mb-0">
            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ asset('assets/images/users/user-1.jpg') }}" alt="user-image" class="rounded-circle">
                    <span class="pro-user-name ms-1">
                        {{ Auth::user()->name }}
                    </span>
                </a>
            </li>

            <li class="dropdown notification-list">
                <a href="{{ url('auth/logout') }}" class="nav-link right-bar-toggle waves-effect waves-light">
                    <i class="fe-log-out noti-icon"></i>
                </a>
            </li>

        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="{{ url('/') }}" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <span class="logo-lg-text-light">DeMaria</span>
                </span>
                <span class="logo-lg">
                    <span class="logo-lg-text-light">DM</span>
                </span>
            </a>

            <a href="{{ url('/') }}" class="logo logo-light text-center">
                <span class="logo-sm">
                    <span class="logo-lg-text-light">DM</span>
                </span>
                <span class="logo-lg">
                    <span class="logo-lg-text-light">DeMaria</span>
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<!-- end Topbar -->
