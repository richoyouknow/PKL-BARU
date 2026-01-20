<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi Denmart Login / Registration Form</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('/login/style.css') }}">
</head>

<body>
    <div class="container slide-up" id="container">

        <!-- Register Form -->
        <div class="form-container register-container">
            <form action="{{ route('register') }}" method="post">
                @csrf
                <h1>Register Here</h1>

                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <input type="password" name="password" placeholder="Password" required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                @error('confirm_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                @if (session('failed'))
                    <div class="alert alert-danger">{{ session('failed') }}</div>
                @endif

                <button type="submit">Register</button>
                <span>or use your account</span>

                <div class="social-container">
                    <a href="#" class="social"><i class="lni lni-facebook-fill"></i></a>
                    <a href="#" class="social"><i class="lni lni-google"></i></a>
                    <a href="#" class="social"><i class="lni lni-linkedin-original"></i></a>
                </div>
            </form>
        </div>

        <!-- Login Form -->
        <div class="form-container login-container">
            <form action="/loginn" method="post">
                @csrf
                <h1>Login Here</h1>

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <input type="password" name="password" placeholder="Password" required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                @if (session('failed'))
                    <div class="alert alert-danger">{{ session('failed') }}</div>
                @endif

                <div class="content">
                    <div class="checkbox">
                        <input type="checkbox" name="remember" id="checkbox">
                        <label for="checkbox">Remember me</label>
                    </div>
                    <div class="pass-link">
                        <a href="#">Forgot password?</a>
                    </div>
                </div>

                <button type="submit">Login</button>
                <span>or use your account</span>

                <div class="social-container">
                    <a href="#" class="social"><i class="lni lni-facebook-fill"></i></a>
                    <a href="#" class="social"><i class="lni lni-google"></i></a>
                    <a href="#" class="social"><i class="lni lni-linkedin-original"></i></a>
                </div>
            </form>
        </div>

        <!-- Overlay for Desktop/Tablet -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 class="title">Selamat Datang <br> Kembali</h1>
                    <p>Silakan login untuk mengelola simpanan, pinjaman,
                        dan informasi keanggotaan Anda.</p>
                    <button class="ghost" id="login" type="button">
                        Login
                        <i class="lni lni-arrow-left login"></i>
                    </button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1 class="title">Menjadi Anggota Koperasi</h1>
                    <p>Bergabunglah bersama koperasi simpan pinjam untuk mengelola
                        simpanan dan mengajukan pinjaman secara aman dan transparan.</p>
                    <button class="ghost" id="register" type="button">
                        Register
                        <i class="lni lni-arrow-right register"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Toggle Button (Only visible on mobile) -->
        <button class="mobile-toggle" id="mobileToggle" type="button">
            <span id="toggleText">Don't have an account? Register</span>
        </button>

    </div>

    <script src="{{ asset('login/script.js') }}"></script>

</body>

</html>
