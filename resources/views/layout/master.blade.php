<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>KOPERASI DENMART</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="{{ asset('iStudio-1.0.0/img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Space+Grotesk&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('iStudio-1.0.0/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('iStudio-1.0.0/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Stylesheet -->
    <link href="{{ asset('iStudio-1.0.0/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('iStudio-1.0.0/css/style.css') }}" rel="stylesheet">


    {{-- Tambahan Styles Untuk Halaman Anak --}}
    @stack('styles')
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    {{-- NAVBAR --}}
    @include('layout.header')

    {{-- CONTENT --}}
  <div id="page-content">
    @yield('content')
    </div>


    {{-- FOOTER --}}
    @include('layout.footer')

    <!-- Back to Top -->
    <a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('iStudio-1.0.0/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('iStudio-1.0.0/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('iStudio-1.0.0/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('iStudio-1.0.0/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('iStudio-1.0.0/js/main.js') }}"></script>

    {{-- Script Tambahan Halaman Anak --}}
    @stack('scripts')
    @if(session('show_login_popup'))
    <div id="popup-overlay">
        <div class="popup-box">
            <h4 class="mb-2">Login Diperlukan</h4>
            <p class="popup-text">Anda harus login terlebih dahulu untuk mengakses halaman ini.</p>
            <a href="/loginn" class="btn btn-primary w-100 mt-3">Login Sekarang</a>
        </div>
    </div>

    <style>
        /* Blur hanya untuk konten halaman */
        #page-content {
            filter: blur(5px);
            pointer-events: none; /* konten tidak bisa di klik */
        }

        #popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.45); /* efek gelap */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(4px); /* background blur */
        }

        .popup-box {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            width: 340px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .popup-text {
        color: #000 !important;  /* warna hitam */
        font-size: 15px;
        }
    </style>
@endif

</body>

</html>
