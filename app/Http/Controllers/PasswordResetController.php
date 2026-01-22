<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPasswordMail;

class PasswordResetController extends Controller
{
    // Tampilkan form lupa password
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Kirim kode verifikasi
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate kode verifikasi 6 digit
        $resetCode = $user->generateResetToken();

        // Kirim email
        Mail::to($user->email)->send(new ResetPasswordMail($resetCode));

        // Simpan email di session untuk verifikasi selanjutnya
        session(['reset_email' => $user->email]);

        return redirect()->route('password.verify')
            ->with('success', 'Kode verifikasi telah dikirim ke email Anda. Kode berlaku selama 30 menit.');
    }

    // Tampilkan form verifikasi kode
    public function showVerifyForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.forgot')
                ->with('failed', 'Sesi tidak valid. Silakan minta kode verifikasi kembali.');
        }

        return view('auth.verify-code');
    }

    // Verifikasi kode
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $email = session('reset_email');
        if (!$email) {
            return back()->with('failed', 'Sesi telah habis. Silakan minta kode verifikasi kembali.');
        }

        $user = User::where('email', $email)->first();

        if (!$user || !$user->isValidResetToken($request->code)) {
            return back()->with('failed', 'Kode verifikasi tidak valid atau telah kadaluarsa.');
        }

        // Kode valid, lanjutkan ke form reset password
        session(['verified_code' => $request->code]);

        return redirect()->route('password.reset');
    }

    // Tampilkan form reset password
    public function showResetForm()
    {
        if (!session('reset_email') || !session('verified_code')) {
            return redirect()->route('password.forgot')
                ->with('failed', 'Sesi tidak valid. Silakan mulai dari awal.');
        }

        return view('auth.reset-password');
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = session('reset_email');
        $code = session('verified_code');

        if (!$email || !$code) {
            return redirect()->route('password.forgot')
                ->with('failed', 'Sesi tidak valid. Silakan mulai dari awal.');
        }

        $user = User::where('email', $email)->first();

        if (!$user || !$user->isValidResetToken($code)) {
            return back()->with('failed', 'Kode verifikasi tidak valid atau telah kadaluarsa.');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->clearResetToken();

        // Clear session
        session()->forget(['reset_email', 'verified_code']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    // Resend kode verifikasi
    public function resendCode()
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.forgot')
                ->with('failed', 'Sesi tidak valid. Silakan minta kode verifikasi kembali.');
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $resetCode = $user->generateResetToken();
            Mail::to($user->email)->send(new ResetPasswordMail($resetCode));

            return back()->with('success', 'Kode verifikasi baru telah dikirim.');
        }

        return back()->with('failed', 'Terjadi kesalahan. Silakan coba lagi.');
    }
}
