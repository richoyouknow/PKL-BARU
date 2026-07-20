<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportAllMarkdownDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Truncate existing tables to avoid duplicate entries and constraints issues
        $this->command->info("Truncating existing tables...");
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transaksis')->truncate();
        DB::table('pinjamen')->truncate();
        DB::table('simpanans')->truncate();
        DB::table('anggotas')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Re-create default admin & anggota template users
        $this->command->info("Re-creating default users...");
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'anggota',
                'email' => 'anggota@gmail.com',
                'role' => 'anggota',
                'status' => 'active',
                'password' => Hash::make('anggota'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Define helper functions
        $parseDate = function ($dateStr) {
            if (empty($dateStr)) return null;
            $dateStr = trim($dateStr);
            $dateStr = str_replace('/', '-', $dateStr);
            if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $dateStr, $matches)) {
                return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
            }
            try {
                return date('Y-m-d', strtotime($dateStr));
            } catch (\Exception $e) {
                return null;
            }
        };

        $parseNumber = function ($numStr) {
            if (empty($numStr)) return 0.0;
            $numStr = strip_tags($numStr);
            $numStr = trim($numStr);
            $numStr = str_replace('.', '', $numStr);
            $numStr = str_replace(',', '.', $numStr);
            return floatval($numStr);
        };

        $mapGrupWilayah = function ($str) {
            if (empty($str)) return 'Non Karyawan';
            $str = str_replace('<br>', ' ', $str);
            $str = str_replace("\n", ' ', $str);
            $str = preg_replace('/\s+/', ' ', $str);
            $str = trim(strtoupper($str));

            $mapping = [
                'OUTSOURCING' => 'Outsourcing',
                'KARYAWAN TETAP' => 'Karyawan Tetap',
                'KARYAWAN PKWT' => 'Karyawan PKWT',
                'KARYAWAN KOPERASI' => 'Karyawan Koperasi',
                'PETUGAS GUDANG PENGOLAH' => 'Petugas Gudang Pengolah',
                'PENSIUN' => 'Pensiun',
            ];

            return $mapping[$str] ?? 'Non Karyawan';
        };

        // Helper to find/create member on the fly if missing in main list
        $anggotaIdMap = []; // Maps no_anggota -> anggota_id in database
        
        $ensureMemberExists = function($noAnggota, $name = null, $tglDaftar = null) use (&$anggotaIdMap) {
            if (isset($anggotaIdMap[$noAnggota])) {
                return $anggotaIdMap[$noAnggota];
            }

            $displayName = $name ?: 'MEMBER-' . $noAnggota;
            $cleanName = strtolower(str_replace(' ', '', $displayName));
            $email = $cleanName . $noAnggota . '@koperasi.com';

            $userId = DB::table('users')->insertGetId([
                'name' => $displayName,
                'email' => $email,
                'role' => 'anggota',
                'status' => 'active',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $anggotaId = DB::table('anggotas')->insertGetId([
                'user_id' => $userId,
                'no_registrasi' => 'REG-' . $noAnggota,
                'no_anggota' => $noAnggota,
                'nama' => $displayName,
                'alamat' => null,
                'grup_wilayah' => 'Non Karyawan',
                'no_telepon' => null,
                'pekerjaan' => null,
                'tanggal_lahir' => null,
                'tanggal_daftar' => $tglDaftar ?: now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $anggotaIdMap[$noAnggota] = $anggotaId;
            $this->command->warn("Auto-created missing member: {$displayName} ({$noAnggota})");
            return $anggotaId;
        };

        // 4. Parse Members from LapDaftarAnggota.md
        $this->command->info("Parsing members from LapDaftarAnggota.md...");
        $anggotaPath = base_path('LapDaftarAnggota.md');
        if (!file_exists($anggotaPath)) {
            $this->command->error("File not found: LapDaftarAnggota.md");
            return;
        }

        $anggotaLines = file($anggotaPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $members = [];

        foreach ($anggotaLines as $line) {
            $line = trim($line);
            if (strpos($line, '|') !== 0) continue;
            $parts = explode('|', $line);
            array_shift($parts);
            array_pop($parts);
            $parts = array_map('trim', $parts);

            if (count($parts) < 8) continue;
            if (strpos($parts[0], '---') !== false) continue;
            if (strpos($parts[0], 'No.') !== false) continue;
            if (strpos($parts[1], 'No. Anggota') !== false) continue;

            if (count($parts) == 8) {
                $col0 = $parts[0];
                if (strpos($col0, '<br>') !== false) {
                    $col0Parts = explode('<br>', $col0);
                    $tglDaftar = $parseDate($col0Parts[0]);
                } else {
                    $tglDaftar = $parseDate($col0);
                }
                $noAnggota = $parts[1];
                $nama = $parts[2];
                $alamat = $parts[3];
                $grup = $mapGrupWilayah($parts[4]);
                $noTelp = $parts[5];
                $pekerjaan = $parts[6];
                $tglLahir = $parseDate($parts[7]);
            } else if (count($parts) == 9) {
                $tglDaftar = $parseDate($parts[1]);
                $noAnggota = $parts[2];
                $nama = $parts[3];
                $alamat = $parts[4];
                $grup = $mapGrupWilayah($parts[5]);
                $noTelp = $parts[6];
                $pekerjaan = $parts[7];
                $tglLahir = $parseDate($parts[8]);
            } else {
                continue;
            }

            if (empty($noAnggota) || !is_numeric($noAnggota)) continue;

            $members[$noAnggota] = [
                'no_anggota' => $noAnggota,
                'nama' => $nama,
                'tanggal_daftar' => $tglDaftar,
                'alamat' => $alamat ?: null,
                'grup_wilayah' => $grup,
                'no_telepon' => $noTelp ?: null,
                'pekerjaan' => $pekerjaan ?: null,
                'tanggal_lahir' => $tglLahir,
            ];
        }

        $this->command->info("Parsed " . count($members) . " members.");

        // 5. Insert Members into Database (User and Anggota tables)
        $this->command->info("Inserting members into database...");

        foreach ($members as $noAnggota => $m) {
            // Generate email
            $cleanName = strtolower(str_replace(' ', '', $m['nama']));
            $email = $cleanName . '@koperasi.com';
            $checkEmail = DB::table('users')->where('email', $email)->exists();
            if ($checkEmail) {
                $email = $cleanName . $noAnggota . '@koperasi.com';
            }

            $userId = DB::table('users')->insertGetId([
                'name' => $m['nama'],
                'email' => $email,
                'role' => 'anggota',
                'status' => 'active',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $anggotaId = DB::table('anggotas')->insertGetId([
                'user_id' => $userId,
                'no_registrasi' => 'REG-' . $noAnggota,
                'no_anggota' => $noAnggota,
                'nama' => $m['nama'],
                'alamat' => $m['alamat'],
                'grup_wilayah' => $m['grup_wilayah'],
                'no_telepon' => $m['no_telepon'],
                'pekerjaan' => $m['pekerjaan'],
                'tanggal_lahir' => $m['tanggal_lahir'],
                'tanggal_daftar' => $m['tanggal_daftar'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $anggotaIdMap[$noAnggota] = $anggotaId;
        }

        $this->command->info("Successfully inserted " . count($members) . " member accounts.");

        // 6. Parse Savings from Lap Simpanan Anggota.md
        $this->command->info("Parsing and inserting savings from Lap Simpanan Anggota.md...");
        $simpananPath = base_path('Lap Simpanan Anggota.md');
        if (!file_exists($simpananPath)) {
            $this->command->error("File not found: Lap Simpanan Anggota.md");
            return;
        }

        $simpananLines = file($simpananPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentMemberNo = null;
        $savingsCount = 0;

        $getMemberIdFromParts = function($parts) {
            if (strpos($parts[0], '<br>') !== false) {
                $sub = explode('<br>', $parts[0]);
                if (is_numeric($sub[0])) return $sub[0];
            }
            if (isset($parts[1]) && strpos($parts[1], '<br>') !== false) {
                $sub = explode('<br>', $parts[1]);
                if (is_numeric($sub[0])) return $sub[0];
            }
            if (is_numeric($parts[0])) {
                if (isset($parts[1]) && is_numeric($parts[1])) {
                    return $parts[1];
                }
                return $parts[0];
            }
            return null;
        };

        foreach ($simpananLines as $line) {
            $line = trim($line);
            if (strpos($line, '|') !== 0) continue;
            $parts = explode('|', $line);
            array_shift($parts);
            array_pop($parts);
            $parts = array_map('trim', $parts);

            if (count($parts) < 5) continue;
            if (strpos($parts[0], '---') !== false) continue;
            if (strpos($parts[0], 'No. Rekenin') !== false || strpos($parts[0], 'No. Anggota') !== false) continue;

            $isAccountRow = false;
            $accountCode = null;
            $accountDate = null;
            $accountType = null;
            $accountBalance = 0.0;

            foreach ($parts as $part) {
                if (preg_match('/^(SP|SW|SKR|Simp|POK|WJB|SKL|BJK)-/i', $part, $matches)) {
                    $isAccountRow = true;
                    $accountCode = $part;
                    break;
                }
            }

            if (!$isAccountRow && isset($parts[1]) && preg_match('/^(SW1)$/i', $parts[1])) {
                $isAccountRow = true;
                $accountCode = 'SW-10036';
            }

            if ($isAccountRow) {
                foreach ($parts as $part) {
                    if (preg_match('/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}$/', $part)) {
                        $accountDate = $parseDate($part);
                        break;
                    }
                }
                if (strpos($accountCode, 'SP-') !== false) {
                    $accountType = 'simpanan_pokok';
                } else if (strpos($accountCode, 'SW-') !== false) {
                    $accountType = 'simpanan_wajib';
                } else if (strpos($accountCode, 'SKR-') !== false) {
                    $accountType = 'simpanan_sukarela';
                }

                foreach ($parts as $part) {
                    if (preg_match('/^\d{1,3}(\.\d{3})*,\d{2}$/', $part)) {
                        $accountBalance = $parseNumber($part);
                        break;
                    } else if (strpos($part, '<br>') !== false) {
                        $subParts = explode('<br>', $part);
                        foreach ($subParts as $sp) {
                            if (preg_match('/^\d{1,3}(\.\d{3})*,\d{2}$/', $sp)) {
                                $accountBalance = $parseNumber($sp);
                                break;
                            }
                        }
                    }
                }

                if ($accountCode === 'SW-10036' && $accountBalance == 0.0) {
                    $accountBalance = 5300000.00;
                    $accountDate = '2024-10-30';
                }

                if ($currentMemberNo && $accountType) {
                    $anggotaId = $ensureMemberExists($currentMemberNo, null, $accountDate);
                    DB::table('simpanans')->insert([
                        'anggota_id' => $anggotaId,
                        'no_simpanan' => $accountCode,
                        'jenis_simpanan' => $accountType,
                        'saldo' => $accountBalance,
                        'status' => 'aktif',
                        'created_at' => $accountDate ? $accountDate . ' 00:00:00' : now(),
                        'updated_at' => now(),
                    ]);
                    $savingsCount++;
                }
            } else {
                $memberId = $getMemberIdFromParts($parts);
                if ($memberId) {
                    $currentMemberNo = strval($memberId);
                }
            }
        }

        $this->command->info("Successfully inserted " . $savingsCount . " savings accounts.");

        // 7. Parse Loans from Lap Pinjaman dan Sisa Angsuran.md
        $this->command->info("Parsing and inserting loans from Lap Pinjaman dan Sisa Angsuran.md...");
        $pinjamanPath = base_path('Lap Pinjaman dan Sisa Angsuran.md');
        if (!file_exists($pinjamanPath)) {
            $this->command->error("File not found: Lap Pinjaman dan Sisa Angsuran.md");
            return;
        }

        $pinjamanLines = file($pinjamanPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $loanCount = 0;

        foreach ($pinjamanLines as $line) {
            $line = trim($line);
            if (strpos($line, '|') !== 0) continue;
            if (strpos($line, '|---|') === 0) continue;
            if (strpos($line, '|**Nomer**|') === 0 || strpos($line, '||**Anggota**||') === 0) continue;
            if (strpos($line, '|TOTAL') === 0) continue;

            $parts = explode('|', $line);
            array_shift($parts);
            array_pop($parts);
            $parts = array_map('trim', $parts);

            if (count($parts) < 11) continue;

            $noAnggota = $parts[0];
            $nama = $parts[1];
            $noPinj = $parts[2];
            $tglPinjam = $parseDate($parts[3]);

            if (count($parts) == 12) {
                if (strpos($parts[4], '<br>') !== false) {
                    // Page 1 Layout
                    $col4Parts = explode('<br>', $parts[4]);
                    $plafond = $parseNumber($col4Parts[0]);
                    $tenor = intval($col4Parts[1]);
                    $jasaPersen = $parseNumber($parts[5]);
                    $jasaRp = $parseNumber($parts[6]);

                    $col7Parts = explode('<br>', $parts[7]);
                    $paidPokok = $parseNumber($col7Parts[0]);
                    $paidTenor = intval($col7Parts[1]);

                    $col8Parts = explode('<br>', $parts[8]);
                    $paidJasa = $parseNumber($col8Parts[0]);
                    $remTenor = intval($col8Parts[1]);

                    $sisaPokok = $parseNumber($parts[9]);
                    $sisaJasa = $parseNumber($parts[10]);
                    $sisaTotal = $parseNumber($parts[11]);
                } else {
                    // Page 2 Layout
                    $plafond = $parseNumber($parts[4]);
                    $col5Parts = explode('<br>', $parts[5]);
                    $tenor = intval($col5Parts[0]);
                    $jasaPersen = $parseNumber($col5Parts[1]);
                    $jasaRp = $parseNumber($parts[6]);

                    $col7Parts = explode('<br>', $parts[7]);
                    $paidPokok = $parseNumber($col7Parts[0]);
                    $paidTenor = intval($col7Parts[1]);

                    $col8Parts = explode('<br>', $parts[8]);
                    $paidJasa = $parseNumber($col8Parts[0]);
                    $remTenor = intval($col8Parts[1]);

                    $sisaPokok = $parseNumber($parts[9]);
                    $sisaJasa = $parseNumber($parts[10]);
                    $sisaTotal = $parseNumber($parts[11]);
                }
            } else if (count($parts) == 11) {
                // Page 3 Layout
                $col4Parts = explode('<br>', $parts[4]);
                $plafond = $parseNumber($col4Parts[0]);
                $tenor = intval($col4Parts[1]);
                $jasaPersen = $parseNumber($col4Parts[2]);

                $col5Parts = explode('<br>', $parts[5]);
                $jasaRp = $parseNumber($col5Parts[0]);
                $paidTenor = intval($col5Parts[1]);

                $paidPokok = $parseNumber($parts[6]);

                $col7Parts = explode('<br>', $parts[7]);
                $paidJasa = $parseNumber($col7Parts[0]);
                $remTenor = intval($col7Parts[1]);

                $sisaPokok = $parseNumber($parts[8]);
                $sisaJasa = $parseNumber($parts[9]);
                $sisaTotal = $parseNumber($parts[10]);
            } else {
                continue;
            }

            $anggotaId = $ensureMemberExists($noAnggota, $nama, $tglPinjam);
            
            $totalPinjamanDenganBunga = $plafond + $jasaRp;
            $saldoPinjaman = $sisaPokok + $sisaJasa; // Sisa pokok + sisa jasa

            // Calculate angsuran per bulan = total pinjaman dengan bunga / tenor
            $angsuranPerBulan = $tenor > 0 ? round($totalPinjamanDenganBunga / $tenor, 2) : 0;

            // Calculate jatuh tempo date
            $tanggalPinjamanObj = \Carbon\Carbon::parse($tglPinjam);
            $tanggalJatuhTempo = $tanggalPinjamanObj->copy()->addMonths($tenor)->format('Y-m-d');

            // Insert pinjaman
            $pinjamanId = DB::table('pinjamen')->insertGetId([
                'anggota_id' => $anggotaId,
                'no_pinjaman' => $noPinj,
                'kategori_pinjaman' => 'pinjaman_cash',
                'jumlah_pinjaman' => $plafond,
                'tenor' => $tenor,
                'bunga_per_tahun' => $jasaPersen,
                'angsuran_per_bulan' => $angsuranPerBulan,
                'total_pinjaman_dengan_bunga' => $totalPinjamanDenganBunga,
                'saldo_pinjaman' => $saldoPinjaman,
                'tanggal_pinjaman' => $tglPinjam,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'status' => 'aktif',
                'keterangan' => 'Imported from Laporan Sisa Angsuran',
                'created_at' => $tglPinjam . ' 00:00:00',
                'updated_at' => now(),
            ]);

            // Create Transaction for Pencairan Pinjaman
            $trxCodePencairan = 'TRX-PJM-' . $noPinj . '-01';
            DB::table('transaksis')->insert([
                'kode_transaksi' => $trxCodePencairan,
                'jenis_transaksi' => 'pinjaman',
                'anggota_id' => $anggotaId,
                'pinjaman_id' => $pinjamanId,
                'jumlah' => $plafond,
                'saldo_sebelum' => 0,
                'saldo_sesudah' => $plafond,
                'status' => 'sukses',
                'keterangan' => "Pencairan pinjaman Cash dengan nomor {$noPinj}",
                'created_at' => $tglPinjam . ' 00:00:00',
                'updated_at' => now(),
            ]);

            // Create Transaction for Paid Instalments (if any paid amount exists)
            $totalPaid = $paidPokok + $paidJasa;
            if ($totalPaid > 0) {
                $trxCodePayment = 'TRX-PAY-' . $noPinj . '-02';
                DB::table('transaksis')->insert([
                    'kode_transaksi' => $trxCodePayment,
                    'jenis_transaksi' => 'pembayaran_pinjaman',
                    'anggota_id' => $anggotaId,
                    'pinjaman_id' => $pinjamanId,
                    'jumlah' => $totalPaid,
                    'saldo_sebelum' => $totalPinjamanDenganBunga,
                    'saldo_sesudah' => $saldoPinjaman,
                    'status' => 'sukses',
                    'keterangan' => "Pembayaran angsuran akumulasi ({$paidTenor} bulan) untuk pinjaman {$noPinj}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $loanCount++;
        }

        $this->command->info("Successfully inserted " . $loanCount . " loan accounts and transactions.");
    }
}
