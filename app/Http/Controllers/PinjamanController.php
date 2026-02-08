<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    /**
     * Display dashboard pinjaman anggota
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Ambil data anggota berdasarkan user yang login
        $anggota = Anggota::where('user_id', $user->id)->first();

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan untuk user Anda');
        }

        // Query pinjaman berdasarkan filter status
        $query = Pinjaman::where('anggota_id', $anggota->id);

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Ambil semua pinjaman dengan relasi anggota
        $pinjamans = $query->with('anggota')->latest()->get();

        // Hitung statistik
        $totalSisaPinjaman = Pinjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['aktif', 'diproses', 'disetujui'])
            ->sum('saldo_pinjaman');

        $pinjamanAktif = Pinjaman::where('anggota_id', $anggota->id)
            ->where('status', 'aktif')
            ->count();

        // Kategori pinjaman aktif
        $kategoriAktif = Pinjaman::where('anggota_id', $anggota->id)
            ->where('status', 'aktif')
            ->select('kategori_pinjaman', DB::raw('count(*) as total'))
            ->groupBy('kategori_pinjaman')
            ->get()
            ->pluck('total', 'kategori_pinjaman')
            ->toArray();

        // Status kolektibilitas (berdasarkan keterlambatan pembayaran)
        $statusKolektibilitas = $this->getStatusKolektibilitas($anggota->id);

        // Hitung limit tersedia (asumsi limit maksimal)
        $limitMaksimal = 25000000; // Rp 25 juta
        $limitTerpakai = $totalSisaPinjaman;
        $limitTersedia = $limitMaksimal - $limitTerpakai;

        $data = [
            'anggota' => $anggota,
            'pinjamans' => $pinjamans,
            'total_sisa_pinjaman' => $totalSisaPinjaman,
            'pinjaman_aktif' => $pinjamanAktif,
            'kategori_aktif' => $kategoriAktif,
            'status_kolektibilitas' => $statusKolektibilitas,
            'limit_tersedia' => $limitTersedia,
            'limit_maksimal' => $limitMaksimal,
        ];

        return view('anggota.pinjaman', $data);
    }

    /**
     * Show form pengajuan pinjaman baru (UNIFIED FORM)
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        $anggota = Anggota::where('user_id', $user->id)->first();

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan untuk user Anda');
        }

        // Hitung limit tersedia
        $limitMaksimal = 25000000;
        $totalSisaPinjaman = Pinjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['aktif', 'diproses', 'disetujui'])
            ->sum('saldo_pinjaman');
        $limitTersedia = $limitMaksimal - $totalSisaPinjaman;

        // Tenor options (dalam bulan) - semua tenor untuk unified form
        $tenorOptions = [3, 6, 9, 12, 18, 24, 30, 36];

        // Kategori pinjaman
        $kategoriOptions = [
            'pinjaman_cash' => 'Pinjaman Cash',
            'pinjaman_elektronik' => 'Pinjaman Elektronik',
        ];

        $data = [
            'anggota' => $anggota,
            'limit_tersedia' => $limitTersedia,
            'limit_maksimal' => $limitMaksimal,
            'tenor_options' => $tenorOptions,
            'kategori_options' => $kategoriOptions,
            'bunga_per_tahun' => 1.5, // Default bunga 1.5%
        ];

        return view('anggota.ajukanpinjaman', $data);
    }

    /**
     * Store pengajuan pinjaman (UNIFIED - Handle both Cash & Elektronik)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        $anggota = Anggota::where('user_id', $user->id)->first();

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan untuk user Anda');
        }

        // Determine kategori pinjaman
        $kategori = $request->input('kategori_pinjaman');

        if ($kategori === 'pinjaman_cash') {
            return $this->storePinjamanCash($request, $anggota);
        } elseif ($kategori === 'pinjaman_elektronik') {
            return $this->storePinjamanElektronik($request, $anggota);
        }

        return redirect()->back()->with('error', 'Kategori pinjaman tidak valid');
    }

    /**
     * Store Pinjaman Cash
     */
    private function storePinjamanCash(Request $request, $anggota)
    {
        // Validasi untuk pinjaman cash
        $validated = $request->validate([
            'kategori_pinjaman' => 'required|in:pinjaman_cash',
            'jumlah_pinjaman' => 'required|numeric|min:500000|max:25000000',
            'tenor' => 'required|integer|in:3,6,9,12,18,24,30,36',
            'bunga_per_tahun' => 'nullable|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'jumlah_pinjaman.required' => 'Jumlah pinjaman harus diisi.',
            'jumlah_pinjaman.min' => 'Jumlah pinjaman minimal Rp 500.000.',
            'jumlah_pinjaman.max' => 'Jumlah pinjaman maksimal Rp 25.000.000.',
            'tenor.required' => 'Tenor pinjaman harus dipilih.',
            'tenor.in' => 'Tenor yang dipilih tidak valid.',
        ]);

        // Validasi tenor berdasarkan jumlah pinjaman
        if ($validated['jumlah_pinjaman'] < 10000000 && $validated['tenor'] > 12) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Untuk pinjaman di bawah Rp 10.000.000, tenor maksimal adalah 12 bulan.');
        }

        // Cek limit tersedia
        $limitMaksimal = 25000000;
        $totalSisaPinjaman = Pinjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['aktif', 'diproses', 'disetujui'])
            ->sum('saldo_pinjaman');
        $limitTersedia = $limitMaksimal - $totalSisaPinjaman;

        if ($validated['jumlah_pinjaman'] > $limitTersedia) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jumlah pinjaman melebihi limit tersedia Anda (Rp ' . number_format($limitTersedia, 0, ',', '.') . ')');
        }

        // Hitung angsuran per bulan
        $bungaPerTahun = $validated['bunga_per_tahun'] ?? 1.5;
        $angsuranPerBulan = Pinjaman::hitungAngsuran(
            $validated['jumlah_pinjaman'],
            $validated['tenor'],
            $bungaPerTahun
        );

        // Hitung total pinjaman dengan bunga
        $totalPinjamanDenganBunga = $angsuranPerBulan * $validated['tenor'];

        // Hitung tanggal jatuh tempo
        $tanggalPinjaman = now();
        $tanggalJatuhTempo = now()->addMonths((int) $validated['tenor']);

        // Buat pinjaman baru
        $pinjaman = Pinjaman::create([
            'anggota_id' => $anggota->id,
            'kategori_pinjaman' => 'pinjaman_cash',
            'jumlah_pinjaman' => $validated['jumlah_pinjaman'],
            'total_pinjaman_dengan_bunga' => $totalPinjamanDenganBunga,
            'saldo_pinjaman' => $totalPinjamanDenganBunga,
            'tenor' => $validated['tenor'],
            'bunga_per_tahun' => $bungaPerTahun,
            'angsuran_per_bulan' => $angsuranPerBulan,
            'tanggal_pinjaman' => $tanggalPinjaman,
            'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
            'status' => 'diajukan',
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('pinjaman.index')
            ->with('success', 'Pengajuan pinjaman cash berhasil! No. Pinjaman: ' . $pinjaman->no_pinjaman . '. Menunggu persetujuan admin.');
    }

    /**
     * Store Pinjaman Elektronik (TANPA NOMINAL - hanya keterangan barang)
     */
    private function storePinjamanElektronik(Request $request, $anggota)
    {
        // Validasi untuk pinjaman elektronik
        $validated = $request->validate([
            'kategori_pinjaman' => 'required|in:pinjaman_elektronik',
            'keterangan_barang' => 'required|string|min:10|max:1000',
            'tenor' => 'required|integer|in:3,6,9,12,18,24,30,36',
            'terms' => 'required|accepted',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'keterangan_barang.required' => 'Keterangan barang elektronik harus diisi.',
            'keterangan_barang.min' => 'Keterangan barang minimal 10 karakter.',
            'keterangan_barang.max' => 'Keterangan barang maksimal 1000 karakter.',
            'tenor.required' => 'Tenor pinjaman harus dipilih.',
            'tenor.in' => 'Tenor yang dipilih tidak valid.',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        try {
            DB::beginTransaction();

            // Build keterangan lengkap
            $keteranganLengkap = "PINJAMAN ELEKTRONIK\n\n";
            $keteranganLengkap .= "Barang yang diminta:\n" . $validated['keterangan_barang'];

            if (!empty($validated['keterangan'])) {
                $keteranganLengkap .= "\n\nCatatan tambahan:\n" . $validated['keterangan'];
            }

            $keteranganLengkap .= "\n\nTenor yang dipilih: " . $validated['tenor'] . " bulan";
            $keteranganLengkap .= "\n\n*Nominal pinjaman akan ditentukan setelah verifikasi barang di koperasi.";

            // Buat pinjaman elektronik dengan nominal 0 (akan diupdate oleh admin)
            $pinjaman = Pinjaman::create([
                'anggota_id' => $anggota->id,
                'kategori_pinjaman' => 'pinjaman_elektronik',
                'jumlah_pinjaman' => 0,
                'total_pinjaman_dengan_bunga' => 0,
                'saldo_pinjaman' => 0,
                'tenor' => $validated['tenor'],
                'bunga_per_tahun' => 1.5,
                'angsuran_per_bulan' => 0,
                'status' => 'diajukan',
                'keterangan' => $keteranganLengkap,
            ]);

            DB::commit();

            return redirect()->route('pinjaman.index')
                ->with('success', 'Pengajuan pinjaman elektronik berhasil! No. Pinjaman: ' . $pinjaman->no_pinjaman . '. Silakan datang ke koperasi untuk verifikasi barang dan penentuan nominal pinjaman.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display detail pinjaman
     */
public function show($id)
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
    }

    $anggota = Anggota::where('user_id', $user->id)->first();

    if (!$anggota) {
        return redirect()->back()->with('error', 'Data anggota tidak ditemukan untuk user Anda');
    }

    // Ambil pinjaman dan pastikan milik anggota yang login
    $pinjaman = Pinjaman::where('id', $id)
        ->where('anggota_id', $anggota->id)
        ->with(['anggota', 'pembayarans' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->firstOrFail();

    // Hitung statistik
    $totalDibayar = $pinjaman->total_pinjaman_dengan_bunga - $pinjaman->saldo_pinjaman;

    // Hitung jumlah angsuran yang sudah dibayar
    $jumlahAngsuranDibayar = 0;
    if ($pinjaman->angsuran_per_bulan > 0) {
        $jumlahAngsuranDibayar = floor($totalDibayar / $pinjaman->angsuran_per_bulan);
    }

    $sisaTenor = max(0, $pinjaman->tenor - $jumlahAngsuranDibayar);
    $persentaseDibayar = ($pinjaman->total_pinjaman_dengan_bunga > 0)
        ? ($totalDibayar / $pinjaman->total_pinjaman_dengan_bunga) * 100
        : 0;

    // PERBAIKAN: Gunakan method helper yang aman
    $isTerlambat = $pinjaman->isTerlambat();
    $hariKeterlambatan = $isTerlambat ? $pinjaman->hari_keterlambatan : 0;

    $data = [
        'pinjaman' => $pinjaman,
        'anggota' => $anggota,
        'total_dibayar' => $totalDibayar,
        'jumlah_angsuran_dibayar' => $jumlahAngsuranDibayar,
        'sisa_tenor' => $sisaTenor,
        'persentase_dibayar' => $persentaseDibayar,
        'is_terlambat' => $isTerlambat,
        'hari_keterlambatan' => $hariKeterlambatan,
    ];

    return view('anggota.detail_pinjaman', $data);
}

    /**
     * Get status kolektibilitas
     */
    private function getStatusKolektibilitas($anggotaId)
    {
        // Cek apakah ada pinjaman yang macet
        $pinjamanMacet = Pinjaman::where('anggota_id', $anggotaId)
            ->where('status', 'macet')
            ->count();

        if ($pinjamanMacet > 0) {
            return [
                'label' => 'Macet',
                'color' => 'red',
                'description' => 'Ada tunggakan pembayaran'
            ];
        }

        // Cek pinjaman aktif
        $pinjamanAktif = Pinjaman::where('anggota_id', $anggotaId)
            ->where('status', 'aktif')
            ->count();

        if ($pinjamanAktif > 0) {
            return [
                'label' => 'Lancar',
                'color' => 'green',
                'description' => 'Tidak ada tunggakan'
            ];
        }

        // Tidak ada pinjaman aktif
        return [
            'label' => 'Tidak Ada Pinjaman',
            'color' => 'gray',
            'description' => 'Belum ada pinjaman aktif'
        ];
    }

    /**
     * Calculate angsuran for preview (AJAX)
     */
    public function calculateAngsuran(Request $request)
    {
        $validated = $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:500000',
            'tenor' => 'required|integer|min:1',
            'bunga_per_tahun' => 'required|numeric|min:0',
        ]);

        $angsuran = Pinjaman::hitungAngsuran(
            $validated['jumlah_pinjaman'],
            $validated['tenor'],
            $validated['bunga_per_tahun']
        );

        // Hitung total bunga
        $totalBayar = $angsuran * $validated['tenor'];
        $totalBunga = $totalBayar - $validated['jumlah_pinjaman'];

        return response()->json([
            'angsuran_per_bulan' => $angsuran,
            'total_bayar' => $totalBayar,
            'total_bunga' => $totalBunga,
            'angsuran_formatted' => 'Rp ' . number_format($angsuran, 0, ',', '.'),
            'total_bayar_formatted' => 'Rp ' . number_format($totalBayar, 0, ',', '.'),
            'total_bunga_formatted' => 'Rp ' . number_format($totalBunga, 0, ',', '.'),
        ]);
    }
}
