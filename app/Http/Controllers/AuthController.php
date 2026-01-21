<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Anggota;

class AuthController extends Controller
{
        public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|email|max:50',
                'password' => 'required|max:50',
            ]);

            // Cek apakah user ada
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->with('failed', 'Email atau password salah');
            }

            // Cek status user
            if ($user->status === 'verify') {
                return back()->with('failed', 'Akun Anda belum diverifikasi oleh admin. Silakan tunggu verifikasi.');
            }

            if ($user->status === 'banned') {
                return back()->with('failed', 'Akun Anda telah dinonaktifkan. Hubungi administrator untuk informasi lebih lanjut.');
            }

            // Cek kredensial login
            if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
                if (Auth::user()->role == 'anggota') {
                    return redirect('/anggota');
                }
                return redirect('/admin');
            }

            return back()->with('failed', 'Email atau password salah');
        }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8|max:50',
            'confirm_password' => 'required|same:password',
            'nama' => 'required|max:100',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'tempat_lahir' => 'required|max:50',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required|max:15',
            'alamat' => 'required',
            'jenis_identitas' => 'required|in:KTP,SIM,Paspor',
            'no_identitas' => 'required|max:50',
            'grup_wilayah' => 'required',
            'pekerjaan' => 'required|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Create User dengan status 'verify' (menunggu verifikasi)
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'anggota',
                'status' => 'verify', // Status awal: menunggu verifikasi
            ]);

            // Handle file upload
            $fotoName = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/images', $fotoName);
            }

            // Create Anggota
            $anggota = Anggota::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'grup_wilayah' => $request->grup_wilayah,
                'jenis_identitas' => $request->jenis_identitas,
                'no_identitas' => $request->no_identitas,
                'agama' => $request->agama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nama_pasangan' => $request->nama_pasangan,
                'pekerjaan' => $request->pekerjaan,
                'pendapatan' => $request->pendapatan,
                'alamat_kantor' => $request->alamat_kantor,
                'keterangan' => $request->keterangan,
                'foto' => $fotoName,
                'tanggal_daftar' => now(),
            ]);

            // JANGAN login otomatis, tunggu verifikasi admin
            // Auth::login($user);

            return redirect('/login')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu verifikasi admin. Anda akan mendapat notifikasi via email setelah akun diverifikasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda');
    }
}
