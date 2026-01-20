<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileAnggotaController extends Controller
{
    /**
     * Display profile anggota
     */
    public function show()
    {
        // Ambil data anggota berdasarkan user yang login
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Ambil data anggota berdasarkan user_id yang login
        $anggota = Anggota::with('user')->where('user_id', $user->id)->first();

        // Jika belum ada data anggota, redirect ke create
        if (!$anggota) {
            return redirect()->route('profile.create')
                ->with('info', 'Silakan lengkapi data anggota Anda terlebih dahulu');
        }

        // Format data untuk view
        $data = [
            'anggota' => $anggota,
            'foto_url' => $anggota->foto_url,
            'tanggal_lahir_formatted' => $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d F Y') : '-',
            'tanggal_daftar_formatted' => $anggota->tanggal_daftar ? $anggota->tanggal_daftar->format('d F Y') : '-',
            'berlaku_sampai_formatted' => $anggota->berlaku_sampai ? $anggota->berlaku_sampai->format('d F Y') : 'Seumur Hidup',
            'pendapatan_formatted' => $anggota->pendapatan ? 'Rp ' . number_format($anggota->pendapatan, 0, ',', '.') : '-',
            'jenis_kelamin_label' => $anggota->jenis_kelamin ?? '-',
            'status_label' => $this->getStatusLabel($anggota),
            'is_pengurus' => $this->isPengurus($anggota),
        ];

        return view('anggota.profile', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Cek apakah sudah ada data anggota
        $existingAnggota = Anggota::where('user_id', $user->id)->first();

        if ($existingAnggota) {
            return redirect()->route('profile.show')
                ->with('info', 'Data anggota Anda sudah ada');
        }

        $data = [
            'user' => $user,
            'agama_options' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'],
            'jenis_identitas_options' => ['KTP', 'SIM', 'Paspor'],
            'jenis_kelamin_options' => ['Pria', 'Wanita'],
            'grup_wilayah_options' => ['Anggota', 'Calon Anggota', 'Pengurus', 'Lainnya'],
        ];

        return view('anggota.createprofile', $data);
    }

    /**
     * Store new anggota
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Cek apakah sudah ada data anggota
        $existingAnggota = Anggota::where('user_id', $user->id)->first();

        if ($existingAnggota) {
            return redirect()->route('profile.show')
                ->with('info', 'Data anggota Anda sudah ada');
        }

        // Validasi
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Pria,Wanita',
            'agama' => 'nullable|string|max:50',
            'nama_pasangan' => 'nullable|string|max:255',
            'jenis_identitas' => 'nullable|in:KTP,SIM,Paspor',
            'no_identitas' => 'nullable|string|max:50',
            'berlaku_sampai' => 'nullable|string|max:50',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'pendapatan' => 'nullable|numeric|min:0',
            'alamat_kantor' => 'nullable|string',
            'grup_wilayah' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Set data tambahan
        $validated['user_id'] = $user->id;
        $validated['tanggal_daftar'] = now();

        // Generate nomor anggota dan registrasi otomatis
        $validated['no_anggota'] = $this->generateNoAnggota();
        $validated['no_registrasi'] = $this->generateNoRegistrasi();

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'anggota_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs('images', $file, $filename);
            $validated['foto'] = $filename;
        }

        // Buat data anggota baru
        $anggota = Anggota::create($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Data anggota berhasil dibuat! Selamat bergabung.');
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Ambil data anggota berdasarkan user_id yang login
        $anggota = Anggota::with('user')->where('user_id', $user->id)->first();

        if (!$anggota) {
            return redirect()->route('profile.create')
                ->with('info', 'Silakan buat data anggota terlebih dahulu');
        }

        $data = [
            'anggota' => $anggota,
            'foto_url' => $anggota->foto_url,
            'agama_options' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'],
            'jenis_identitas_options' => ['KTP', 'SIM', 'Paspor'],
            'jenis_kelamin_options' => ['Pria', 'Wanita'],
            'grup_wilayah_options' => ['Anggota', 'Calon Anggota', 'Pengurus', 'Lainnya'],
        ];

        return view('anggota.editprofile', $data);
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Ambil data anggota berdasarkan user_id yang login
        $anggota = Anggota::where('user_id', $user->id)->first();

        if (!$anggota) {
            return redirect()->route('profile.create')
                ->with('error', 'Data anggota tidak ditemukan');
        }

        // Validasi
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Pria,Wanita',
            'agama' => 'nullable|string|max:50',
            'nama_pasangan' => 'nullable|string|max:255',
            'jenis_identitas' => 'nullable|in:KTP,SIM,Paspor',
            'no_identitas' => 'nullable|string|max:50',
            'berlaku_sampai' => 'nullable|string|max:50',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'pendapatan' => 'nullable|numeric|min:0',
            'alamat_kantor' => 'nullable|string',
            'grup_wilayah' => 'nullable|string|max:100',
            'no_anggota' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama (jika ada)
            if ($anggota->foto && Storage::disk('public')->exists('images/' . $anggota->foto)) {
                Storage::disk('public')->delete('images/' . $anggota->foto);
            }

            $file = $request->file('foto');
            $filename = 'anggota_' . $anggota->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs('images', $file, $filename);
            $validated['foto'] = $filename;
        }

        // Update data anggota
        $anggota->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Generate nomor anggota otomatis
     */
    private function generateNoAnggota()
    {
        $prefix = 'AGT';
        $year = date('Y');

        $lastAnggota = Anggota::where('no_anggota', 'like', "{$prefix}-{$year}-%")
            ->orderBy('no_anggota', 'desc')
            ->first();

        if ($lastAnggota) {
            $lastNumber = (int) substr($lastAnggota->no_anggota, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $year, $nextNumber);
    }

    /**
     * Generate nomor registrasi otomatis
     */
    private function generateNoRegistrasi()
    {
        $prefix = 'REG';
        $date = date('Ymd');

        $lastReg = Anggota::where('no_registrasi', 'like', "{$prefix}-{$date}-%")
            ->orderBy('no_registrasi', 'desc')
            ->first();

        if ($lastReg) {
            $lastNumber = (int) substr($lastReg->no_registrasi, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $nextNumber);
    }

    /**
     * Get status label
     */
    private function getStatusLabel($anggota)
    {
        if ($anggota->grup_wilayah === 'Anggota') {
            return 'Aktif';
        } elseif ($anggota->grup_wilayah === 'Calon Anggota') {
            return 'Calon';
        }

        return 'Aktif';
    }

    /**
     * Check if anggota is pengurus
     */
    private function isPengurus($anggota)
    {
        return $anggota->grup_wilayah === 'Pengurus';
    }

    /**
     * Get tahun bergabung
     */
    private function getTahunBergabung($anggota)
    {
        return $anggota->tanggal_daftar ? $anggota->tanggal_daftar->format('Y') : '-';
    }

    public function index()
    {
        return redirect()->route('profile.show');
    }
}
