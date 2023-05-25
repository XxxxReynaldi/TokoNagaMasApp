@if(auth()->user()->role_id == 1)
    {{-- <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ route('home') }}">
    <span class="subtitle"><strong>MGBK SMA Kota Malang</strong></span>
    </a>

    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarTop">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
    </a>
    </div>

    <div id="navbarTop" class="navbar-menu">
        <div class="navbar-end mr-4">
            <a class="navbar-item" href="{{ route('home') }}">
                <span class="icon">
                    <i class="fas fa-home"></i>
                </span>
                <span>
                    Home
                </span>
            </a>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    <span class="icon">
                        <i class="fas fa-cogs"></i>
                    </span>
                    <span>
                        Master
                    </span>
                </a>

                <div class="navbar-dropdown is-right">
                    <a class="navbar-item" href="{{ route('admin.kegiatan.index') }}">
                        Kegiatan
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item" href="{{ route('admin.week.index') }}">
                        Week
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item" href="{{ route('admin.sekolah.index') }}">
                        Sekolah
                    </a>
                </div>
            </div>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    <span class="icon">
                        <i class="fas fa-sticky-note"></i>
                    </span>
                    <span>
                        Laporan
                    </span>
                </a>

                <div class="navbar-dropdown is-right">
                    <a class="navbar-item" href="{{ route('admin.laporan.harian') }}">
                        Harian
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item" href="{{ route('admin.laporan.mingguan') }}">
                        Mingguan
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item" href="{{ route('admin.laporan.bulanan') }}">
                        Bulanan
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item" href="{{ route('admin.laporan.semesteran') }}">
                        Semesteran
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item" href="{{ route('admin.laporan.tahunan') }}">
                        Tahunan
                    </a>
                </div>
            </div>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    <span class="icon">
                        <i class="fas fa-user-circle"></i>
                    </span>
                    <span>
                        Profil
                    </span>
                </a>

                <div class="navbar-dropdown is-right">
                    <a class="navbar-item" href="{{ route('profile.index') }}">
                        <span class="icon">
                            <i class="fas fa-user"></i>
                        </span>
                        <span>
                            Lihat Profil
                        </span>
                    </a>
                    <a class="navbar-item" href="{{ route('update-password.edit') }}">
                        <span class="icon">
                            <i class="fas fa-lock"></i>
                        </span>
                        <span>
                            Ubah Password
                        </span>
                    </a>
                    <hr class="navbar-divider">
                    <a class="navbar-item has-text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <span class="icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </span>
                        <span>
                            Sign Out
                        </span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                        class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    </nav> --}}
    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
        id="sidenav-main">

        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard "
                target="_blank">
                <img src="{{ asset('img/logo-ct.png') }}" class="navbar-brand-img h-100"
                    alt="main_logo">
                <span class="ms-1 font-weight-bold text-white">{{ auth()->user()->name }}</span>
            </a>
        </div>

        <hr class="horizontal light mt-0 mb-2">

        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('dashboard') ? 'active bg-gradient-primary' : '' }} " href="{{ route('dashboard') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="./dashboard.html">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">receipt</span>
                        </div>
                        <span class="nav-link-text ms-1">Transaksi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('mechanics') ? 'active bg-gradient-primary' : '' }} " href="{{ route('mechanics.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">home_repair_service</span>
                        </div>
                        <span class="nav-link-text ms-1">Mekanik</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('products') ? 'active bg-gradient-primary' : '' }}" href="./dashboard.html">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">inventory</span>
                        </div>
                        <span class="nav-link-text ms-1">Produk</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('galleries') ? 'active bg-gradient-primary' : '' }}" href="./dashboard.html">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">collections_bookmark</span>
                        </div>
                        <span class="nav-link-text ms-1">Galeri</span>
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages
                    </h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('users') ? 'active bg-gradient-primary' : '' }}" href="./profile.html">

                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">person</i>
                        </div>

                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">logout</span>
                        </div>
                        <span class="nav-link-text ms-1">Sign Out</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                        class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>

        <div class="sidenav-footer position-absolute w-100 bottom-0 ">
            {{-- <div class="mx-3">
                <a class="btn bg-gradient-primary mt-4 w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
            </div> --}}

        </div>

    </aside>


@elseif(auth()->user()->role_id == 2)

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-link">Sign Out</button>
</form>

@endif
