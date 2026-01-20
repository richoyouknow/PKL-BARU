<!-- Navbar Start -->
<div class="container-fluid sticky-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light border-bottom border-2 border-white">

            <!-- Logo -->
            <a href="{{ route('beranda') }}" class="navbar-brand">
                <img src="{{ asset('denmart.jpeg') }}" alt="Logo" style="height: 50px; width: auto;">
            </a>

            <button type="button" class="navbar-toggler ms-auto me-0" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">

                <div class="navbar-nav ms-auto">

                    <a href="{{ route('beranda') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">
                        Beranda
                    </a>

                    <a href="{{ route('simpanan') }}"
                        class="nav-item nav-link {{ Request::is('simpanan') ? 'active' : '' }}">
                        Simpanan
                    </a>

                    <a href="{{ route('pinjaman.index') }}"
                        class="nav-item nav-link {{ Request::is('pinjaman') ? 'active' : '' }}">
                        Pinjaman
                    </a>

                    <a href="{{ route('transaksi') }}"
                        class="nav-item nav-link {{ Request::is('transaksi') ? 'active' : '' }}">
                        Transaksi
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="nav-item nav-link fw-bold">
                            Masuk
                        </a>
                    @endguest

                    @auth
                        <div class="nav-item dropdown">

                            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center"
                                data-bs-toggle="dropdown">

                                {{-- Foto Profil --}}
                                <img
                                    src="{{ Auth::user()->anggota && Auth::user()->anggota->foto
                                        ? asset('storage/images/' . Auth::user()->anggota->foto)
                                        : asset('default-user.png') }}"
                                    class="rounded-circle"
                                    style="width: 30px; height: 30px; object-fit: cover;"
                                >

                                {{-- Nama --}}
                                <span class="ms-2 fw-semibold">
                                    Halo, {{ Auth::user()->name }}
                                </span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">

                                <a href="{{ route('profile.show') }}" class="dropdown-item py-2">
                                    Profil Saya
                                </a>

                                <div class="dropdown-divider"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger py-2">
                                        Logout
                                    </button>
                                </form>

                            </div>
                        </div>
                    @endauth

                </div>
            </div>

        </nav>
    </div>
</div>
<!-- Navbar End -->
