<?php
// database/seeders/ImportAnggotaManualSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportAnggotaManualSeeder extends Seeder
{
    public function run(): void
    {
        // Data lengkap dari Excel (152 anggota)
        $dataAnggota = [
            ['no_anggota' => '003553', 'nama' => 'AGIL MASHUJI SP', 'pokok' => 500000, 'wajib' => 2091994],
            ['no_anggota' => '00571', 'nama' => 'SAEFUL ADI', 'pokok' => 500000, 'wajib' => 3265270],
            ['no_anggota' => '00895', 'nama' => 'ABD KHOLIK', 'pokok' => 500000, 'wajib' => 7107258],
            ['no_anggota' => '00896', 'nama' => 'TURBIYANTO', 'pokok' => 500000, 'wajib' => 7596940],
            ['no_anggota' => '01796', 'nama' => 'PUJIANTO', 'pokok' => 500000, 'wajib' => 6165000],
            ['no_anggota' => '01815', 'nama' => 'ANGGORO ANSON S', 'pokok' => 500000, 'wajib' => 7356811],
            ['no_anggota' => '03517', 'nama' => 'SITI ARIFAH SP MP', 'pokok' => 500000, 'wajib' => 36880000],
            ['no_anggota' => '10000325', 'nama' => 'IMRON', 'pokok' => 500000, 'wajib' => 5795000],
            ['no_anggota' => '10000344', 'nama' => 'DHANNY SUKMANA PRIBADI', 'pokok' => 500000, 'wajib' => 1620000],
            ['no_anggota' => '10000346', 'nama' => 'IMAM BASIR', 'pokok' => 500000, 'wajib' => 7263138],
            ['no_anggota' => '10000348', 'nama' => 'SANIDUN', 'pokok' => 500000, 'wajib' => 6634455],
            ['no_anggota' => '10000351', 'nama' => 'MAT SIRI', 'pokok' => 500000, 'wajib' => 8643706],
            ['no_anggota' => '10000353', 'nama' => 'MULYONO', 'pokok' => 500000, 'wajib' => 7446954],
            ['no_anggota' => '10000355', 'nama' => 'SUWANTONO', 'pokok' => 500000, 'wajib' => 7546555],
            ['no_anggota' => '10000356', 'nama' => 'SUMARDI', 'pokok' => 500000, 'wajib' => 7555213],
            ['no_anggota' => '10000357', 'nama' => 'SITI AISYAH', 'pokok' => 500000, 'wajib' => 6634455],
            ['no_anggota' => '10000358', 'nama' => 'BAMBANG SUTIKNO', 'pokok' => 500000, 'wajib' => 7862184],
            ['no_anggota' => '10000359', 'nama' => 'FRENGKI SETYA W', 'pokok' => 500000, 'wajib' => 6920857],
            ['no_anggota' => '10000361', 'nama' => 'SUBANDI', 'pokok' => 500000, 'wajib' => 6620006],
            ['no_anggota' => '10000363', 'nama' => 'HERWANTO', 'pokok' => 500000, 'wajib' => 6195000],
            ['no_anggota' => '10000373', 'nama' => 'KHOIRUL ANAM', 'pokok' => 500000, 'wajib' => 9689508],
            ['no_anggota' => '10000377', 'nama' => 'RACHMAD P NILA', 'pokok' => 500000, 'wajib' => 8622040],
            ['no_anggota' => '10000379', 'nama' => 'NURUL', 'pokok' => 500000, 'wajib' => 7232426],
            ['no_anggota' => '10000380', 'nama' => 'MISBAHUL ULUM', 'pokok' => 500000, 'wajib' => 7760773],
            ['no_anggota' => '10000381', 'nama' => 'SUTRISNO SP', 'pokok' => 500000, 'wajib' => 6185000],
            ['no_anggota' => '10000382', 'nama' => 'WARSIANTO', 'pokok' => 500000, 'wajib' => 6926364],
            ['no_anggota' => '10000383', 'nama' => 'AGUK IKA H', 'pokok' => 500000, 'wajib' => 8853236],
            ['no_anggota' => '10000384', 'nama' => 'FAJAR SODIK', 'pokok' => 500000, 'wajib' => 6838001],
            ['no_anggota' => '10000385', 'nama' => 'MUKTI HARI CAHYONO', 'pokok' => 500000, 'wajib' => 6982265],
            ['no_anggota' => '10000386', 'nama' => 'STEPHANUS CAHYO HERTANTO', 'pokok' => 500000, 'wajib' => 1200000],
            ['no_anggota' => '10000387', 'nama' => 'SUGENG SUTRISNO', 'pokok' => 500000, 'wajib' => 5600000],
            ['no_anggota' => '10000390', 'nama' => 'SUEB', 'pokok' => 500000, 'wajib' => 5560221],
            ['no_anggota' => '10000391', 'nama' => 'SAKRONI PRAYITNO', 'pokok' => 500000, 'wajib' => 7016315],
            ['no_anggota' => '10000393', 'nama' => 'EDY MULYONO', 'pokok' => 500000, 'wajib' => 6195000],
            ['no_anggota' => '10000394', 'nama' => 'BUDI HERMANTO', 'pokok' => 500000, 'wajib' => 5795000],
            ['no_anggota' => '10000395', 'nama' => 'DWI APRILLA SANDI SP', 'pokok' => 500000, 'wajib' => 5728330],
            ['no_anggota' => '10000396', 'nama' => 'SRI WURYANIK', 'pokok' => 500000, 'wajib' => 8340723],
            ['no_anggota' => '10000397', 'nama' => 'HARTO', 'pokok' => 500000, 'wajib' => 7520484],
            ['no_anggota' => '10000398', 'nama' => 'BUDIONO', 'pokok' => 500000, 'wajib' => 6486055],
            ['no_anggota' => '10000400', 'nama' => 'MISYADI', 'pokok' => 500000, 'wajib' => 6886605],
            ['no_anggota' => '10000402', 'nama' => 'FARIDHA TRI PAMIKATSIH', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10000404', 'nama' => 'AGUNG BRAMASTO', 'pokok' => 500000, 'wajib' => 6135000],
            ['no_anggota' => '10000405', 'nama' => 'CHUSNUL CHOTIMAH', 'pokok' => 500000, 'wajib' => 6719353],
            ['no_anggota' => '10000406', 'nama' => 'MARGARITA ERNANINGDYAH DAUD', 'pokok' => 500000, 'wajib' => 6957701],
            ['no_anggota' => '10000410', 'nama' => 'BAMBANG PRIYAMBADA', 'pokok' => 500000, 'wajib' => 6825701],
            ['no_anggota' => '10000411', 'nama' => 'INANG ARDIANTO', 'pokok' => 500000, 'wajib' => 7254760],
            ['no_anggota' => '10000413', 'nama' => 'M SETYO BUDI', 'pokok' => 500000, 'wajib' => 6195000],
            ['no_anggota' => '10000414', 'nama' => 'R ADI MULYONO', 'pokok' => 500000, 'wajib' => 6195000],
            ['no_anggota' => '10000518', 'nama' => 'BUDI CIPTO HARTONO', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '10000519', 'nama' => 'RETNO PRASTIWI', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '10000520', 'nama' => 'KARMIYANTO', 'pokok' => 500000, 'wajib' => 5000000],
            ['no_anggota' => '10000521', 'nama' => 'MOCH CHOLID', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '10000522', 'nama' => 'ACHMAD ALI', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '10000525', 'nama' => 'MOH KAMIL', 'pokok' => 500000, 'wajib' => 1200000],
            ['no_anggota' => '10000526', 'nama' => 'SAPTO RIO SASONGKO', 'pokok' => 500000, 'wajib' => 1200000],
            ['no_anggota' => '10000527', 'nama' => 'SONY BAGUS PRATAMA', 'pokok' => 500000, 'wajib' => 1200000],
            ['no_anggota' => '10000528', 'nama' => 'MUCHAMAD BAYU SETIYO BUDI', 'pokok' => 500000, 'wajib' => 1200000],
            ['no_anggota' => '10000529', 'nama' => 'HANDIKA RISWAN PRASOJO', 'pokok' => 500000, 'wajib' => 1200000],
            ['no_anggota' => '10001', 'nama' => 'ACH FAOSI', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10002', 'nama' => 'SUPARMAN', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10003', 'nama' => 'HERMAWAN', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10004', 'nama' => 'HERI SUKAMTO', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10005', 'nama' => 'A MURSYID', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10007', 'nama' => 'SAHRUN', 'pokok' => 500000, 'wajib' => 6300000],
            ['no_anggota' => '10008', 'nama' => 'PASRAH', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10009', 'nama' => 'MULYADI', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10010', 'nama' => 'SANIAN', 'pokok' => 500000, 'wajib' => 5700000],
            ['no_anggota' => '10011', 'nama' => 'BUDI H', 'pokok' => 500000, 'wajib' => 6200000],
            ['no_anggota' => '10012', 'nama' => 'LUKMAN HARYANTO', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10013', 'nama' => 'RAHMAD HIDAYAT', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10014', 'nama' => 'YOKI RAHMAN', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10015', 'nama' => 'AHMAT HAIRULLAH', 'pokok' => 500000, 'wajib' => 6200000],
            ['no_anggota' => '10016', 'nama' => 'BAJURI', 'pokok' => 500000, 'wajib' => 6200000],
            ['no_anggota' => '10017', 'nama' => 'ABDUL KAHAR', 'pokok' => 500000, 'wajib' => 6200000],
            ['no_anggota' => '10018', 'nama' => 'SUWARTO', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10019', 'nama' => 'ACHMAD ZAMRONI', 'pokok' => 500000, 'wajib' => 6200000],
            ['no_anggota' => '10020', 'nama' => 'MOH YANTO', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10021', 'nama' => 'RONI WAHYUDI', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10022', 'nama' => 'IKHWAN PURWANTO', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10023', 'nama' => 'RUDI HARTONO', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10024', 'nama' => 'M AMIN NASIR', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10025', 'nama' => 'ARIK SETIAWAN', 'pokok' => 500000, 'wajib' => 6200000],
            ['no_anggota' => '10028', 'nama' => 'MISYATI', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10029', 'nama' => 'SUTILA', 'pokok' => 500000, 'wajib' => 6100000],
            ['no_anggota' => '10031', 'nama' => 'M FAUZI', 'pokok' => 500000, 'wajib' => 5000000],
            ['no_anggota' => '10032', 'nama' => 'SAMSUL ARIFIN P TOTOK', 'pokok' => 500000, 'wajib' => 5000000],
            ['no_anggota' => '10033', 'nama' => 'HERI ARIFIANTO', 'pokok' => 500000, 'wajib' => 5500000],
            ['no_anggota' => '10034', 'nama' => 'ABDUR ROHIM', 'pokok' => 500000, 'wajib' => 5000000],
            ['no_anggota' => '10035', 'nama' => 'AGUS ADI PRAMONO', 'pokok' => 500000, 'wajib' => 5400000],
            ['no_anggota' => '10036', 'nama' => 'CHOIRUL ANAM', 'pokok' => 500000, 'wajib' => 4900000],
            ['no_anggota' => '10037', 'nama' => 'M SAMSUL ARIFIN W', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '10038', 'nama' => 'GUNAWAN SASTRO W', 'pokok' => 500000, 'wajib' => 5300000],
            ['no_anggota' => '10039', 'nama' => 'AHMAD ZAENURI', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '10040', 'nama' => 'FERI ALFA', 'pokok' => 500000, 'wajib' => 5800000],
            ['no_anggota' => '10041', 'nama' => 'EDI CAHYONO', 'pokok' => 500000, 'wajib' => 1300000],
            ['no_anggota' => '10042', 'nama' => 'SUBAISOL', 'pokok' => 0, 'wajib' => 0],
            ['no_anggota' => '10045', 'nama' => 'ANDI SUTRISNO', 'pokok' => 0, 'wajib' => 0],
            ['no_anggota' => '10049', 'nama' => 'DWI ARYO KUSBANDI', 'pokok' => 0, 'wajib' => 0],
            ['no_anggota' => '10057', 'nama' => 'AHMAD HAMDI', 'pokok' => 500000, 'wajib' => 1000000],
            ['no_anggota' => '10058', 'nama' => 'REIFAN FAQIH HERSABIL', 'pokok' => 500000, 'wajib' => 1000000],
            ['no_anggota' => '10059', 'nama' => 'SUGENG PRAYITNO', 'pokok' => 500000, 'wajib' => 1100000],
            ['no_anggota' => '10060', 'nama' => 'PUGUH GAGANUSA', 'pokok' => 500000, 'wajib' => 2100000],
            ['no_anggota' => '10061', 'nama' => 'HASINUL HISNI', 'pokok' => 500000, 'wajib' => 2500000],
            ['no_anggota' => '10062', 'nama' => 'ERFAN EFENDI', 'pokok' => 500000, 'wajib' => 3300000],
            ['no_anggota' => '10064', 'nama' => 'NINING WIDIATANTI', 'pokok' => 500000, 'wajib' => 1100000],
            ['no_anggota' => '10065', 'nama' => 'NURUL MUSTAQIM', 'pokok' => 500000, 'wajib' => 1100000],
            ['no_anggota' => '10066', 'nama' => 'RIZKY HIDAYAT', 'pokok' => 500000, 'wajib' => 900000],
            ['no_anggota' => '10068', 'nama' => 'ANDIK PUJI SETIAWAN', 'pokok' => 500000, 'wajib' => 1000000],
            ['no_anggota' => '10069', 'nama' => 'PUJI JAYA SUSANTO', 'pokok' => 500000, 'wajib' => 800000],
            ['no_anggota' => '10070', 'nama' => 'SITI HOLIFAH', 'pokok' => 500000, 'wajib' => 1800000],
            ['no_anggota' => '10071', 'nama' => 'FAISAL HAMAM', 'pokok' => 500000, 'wajib' => 1900000],
            ['no_anggota' => '10072', 'nama' => 'SUGIMIN', 'pokok' => 500000, 'wajib' => 600000],
            ['no_anggota' => '10073', 'nama' => 'SAIFUL NURI', 'pokok' => 500000, 'wajib' => 700000],
            ['no_anggota' => '10074', 'nama' => 'DONI HIDAYAT PRASETYO', 'pokok' => 500000, 'wajib' => 1300000],
            ['no_anggota' => '10075', 'nama' => 'GATOT SUBROTO', 'pokok' => 500000, 'wajib' => 600000],
            ['no_anggota' => '10076', 'nama' => 'MUHAMMAD RAFI FIRDAUS', 'pokok' => 500000, 'wajib' => 400000],
            ['no_anggota' => '10077', 'nama' => 'GANDI SUGIANTO', 'pokok' => 500000, 'wajib' => 400000],
            ['no_anggota' => '10078', 'nama' => 'HIJIR MAULANA', 'pokok' => 500000, 'wajib' => 400000],
            ['no_anggota' => '10079', 'nama' => 'MOHAMMAD SAHURI', 'pokok' => 500000, 'wajib' => 300000],
            ['no_anggota' => '10080', 'nama' => 'IMAM MIFTAHUDIN', 'pokok' => 500000, 'wajib' => 300000],
            ['no_anggota' => '10081', 'nama' => 'HERU HERMANTO', 'pokok' => 500000, 'wajib' => 300000],
            ['no_anggota' => '10082', 'nama' => 'SUWARNO', 'pokok' => 500000, 'wajib' => 200000],
            ['no_anggota' => '10083', 'nama' => 'SUGIONO PRAMONO', 'pokok' => 500000, 'wajib' => 200000],
            ['no_anggota' => '10084', 'nama' => 'KHOIRON MIFTAHUL IHSAN', 'pokok' => 500000, 'wajib' => 200000],
            ['no_anggota' => '10085', 'nama' => 'M IMRON', 'pokok' => 500000, 'wajib' => 200000],
            ['no_anggota' => '10086', 'nama' => 'SELAMET', 'pokok' => 500000, 'wajib' => 200000],
            ['no_anggota' => '10087', 'nama' => 'INTO YULIATI', 'pokok' => 250000, 'wajib' => 100000],
            ['no_anggota' => '10088', 'nama' => 'AGUSTINI', 'pokok' => 250000, 'wajib' => 100000],
            ['no_anggota' => '10089', 'nama' => 'ABDUL SALAM', 'pokok' => 0, 'wajib' => 0],
            ['no_anggota' => '10090', 'nama' => 'DEDY KURNIAWAN', 'pokok' => 0, 'wajib' => 0],
            ['no_anggota' => '10091', 'nama' => 'HARIYANTO', 'pokok' => 0, 'wajib' => 0],
            ['no_anggota' => '10092', 'nama' => 'RAHMAN GUNAWAN', 'pokok' => 0, 'wajib' => 0],
            ['no_anggota' => '10093', 'nama' => 'EDI PURNOMO', 'pokok' => 0, 'wajib' => 0],
            ['no_anggota' => '10094', 'nama' => 'ROFIK HIDAYAT', 'pokok' => 0, 'wajib' => 0],
            ['no_anggota' => '10101', 'nama' => 'DIDIK SETIAWAN', 'pokok' => 500000, 'wajib' => 5500000],
            ['no_anggota' => '10102', 'nama' => 'DONA LAILATUL C', 'pokok' => 500000, 'wajib' => 5500000],
            ['no_anggota' => '10105', 'nama' => 'DIMAS TRI FAJAR R', 'pokok' => 500000, 'wajib' => 4500000],
            ['no_anggota' => '10106', 'nama' => 'M AFID ROHMAN', 'pokok' => 500000, 'wajib' => 4900000],
            ['no_anggota' => '10107', 'nama' => 'DESVITA FITRIANI S', 'pokok' => 500000, 'wajib' => 3600000],
            ['no_anggota' => '10108', 'nama' => 'HOSNAN EDDY JUNAIDI', 'pokok' => 500000, 'wajib' => 3100000],
            ['no_anggota' => '10355', 'nama' => 'HERU SUSANTO', 'pokok' => 500000, 'wajib' => 5200000],
            ['no_anggota' => '10392', 'nama' => 'HANIFAH', 'pokok' => 500000, 'wajib' => 5000000],
            ['no_anggota' => '10393', 'nama' => 'SITI SAROFAH', 'pokok' => 500000, 'wajib' => 5000000],
            ['no_anggota' => '19000217', 'nama' => 'SUGIYONO', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '19000219', 'nama' => 'UNTUNG DARMAWAN', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '19000220', 'nama' => 'NATIMIN', 'pokok' => 500000, 'wajib' => 5000000],
            ['no_anggota' => '19000222', 'nama' => 'EKO WAHONO', 'pokok' => 500000, 'wajib' => 5000000],
            ['no_anggota' => '19000223', 'nama' => 'FRIMANSYAH', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '19000224', 'nama' => 'HERI CATUR S', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '19000226', 'nama' => 'M FARUK', 'pokok' => 500000, 'wajib' => 5100000],
            ['no_anggota' => '19001566', 'nama' => 'PURWOTO', 'pokok' => 500000, 'wajib' => 2100000],
            ['no_anggota' => '19000215', 'nama' => 'IMAM', 'pokok' => 500000, 'wajib' => 5100000],
        ];

        $this->command->info('Mulai import data anggota...');
        $this->command->info('Total data: ' . count($dataAnggota) . ' anggota');

        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($dataAnggota as $index => $item) {
            $barisKe = $index + 1;

            try {
                DB::transaction(function () use ($item, $barisKe) {

                    // Cek duplikat no_anggota
                    if (DB::table('anggotas')->where('no_anggota', $item['no_anggota'])->exists()) {
                        throw new \Exception("No anggota {$item['no_anggota']} sudah ada di database");
                    }

                    // Buat email unik
                    $baseEmail = Str::slug(substr($item['nama'], 0, 20), '');
                    if (empty($baseEmail)) {
                        $baseEmail = 'anggota' . $item['no_anggota'];
                    }
                    $email = $baseEmail . '@gmail.com';

                    // Pastikan email unik
                    $counter = 1;
                    while (DB::table('users')->where('email', $email)->exists()) {
                        $email = $baseEmail . $counter . '@gmail.com';
                        $counter++;
                    }

                    // Insert ke tabel users
                    $userId = DB::table('users')->insertGetId([
                        'name' => $item['nama'],
                        'email' => $email,
                        'password' => Hash::make('password123'), // Password default: password123
                        'role' => 'anggota',
                        'status' => 'active',
                        'email_verified_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Insert ke tabel anggotas
                    $anggotaId = DB::table('anggotas')->insertGetId([
                        'user_id' => $userId,
                        'no_registrasi' => 'REG-' . $item['no_anggota'],
                        'no_anggota' => $item['no_anggota'],
                        'nama' => $item['nama'],
                        'grup_wilayah' => 'Non Karyawan',
                        'tanggal_daftar' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Insert simpanan pokok jika ada
                    if ($item['pokok'] > 0) {
                        DB::table('simpanans')->insert([
                            'anggota_id' => $anggotaId,
                            'no_simpanan' => 'POK-' . $item['no_anggota'] . '-' . date('Ymd') . '-' . str_pad($barisKe, 3, '0', STR_PAD_LEFT),
                            'jenis_simpanan' => 'simpanan_pokok',
                            'saldo' => $item['pokok'],
                            'status' => 'aktif',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Insert simpanan wajib jika ada
                    if ($item['wajib'] > 0) {
                        DB::table('simpanans')->insert([
                            'anggota_id' => $anggotaId,
                            'no_simpanan' => 'WJB-' . $item['no_anggota'] . '-' . date('Ymd') . '-' . str_pad($barisKe, 3, '0', STR_PAD_LEFT),
                            'jenis_simpanan' => 'simpanan_wajib',
                            'saldo' => $item['wajib'],
                            'status' => 'aktif',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });

                $success++;
                $this->command->info("✓ [{$barisKe}] Berhasil: {$item['nama']} ({$item['no_anggota']})");

            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Baris {$barisKe}: {$item['nama']} - " . $e->getMessage();
                $this->command->error("✗ [{$barisKe}] Gagal: {$item['nama']} - " . $e->getMessage());
            }
        }

        // Tampilkan ringkasan
        $this->command->newLine(2);
        $this->command->info('====================================');
        $this->command->info('         HASIL IMPORT DATA');
        $this->command->info('====================================');
        $this->command->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Total Data', count($dataAnggota)],
                ['Berhasil', $success],
                ['Gagal', $failed],
            ]
        );

        if (!empty($errors)) {
            $this->command->newLine();
            $this->command->warn('Detail Error:');
            foreach ($errors as $error) {
                $this->command->line(" - $error");
            }
        }
    }
}
