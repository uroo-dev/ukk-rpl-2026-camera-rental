<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Pelanggan;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // 1. PENGGUNA SISTEM (Akun Asli & Hak Akses UKK)
        // -------------------------------------------------------------
        $userAdmin = User::updateOrCreate(
            ['email' => 'uroo@gmail.com'],
            [
                'nama' => 'uroo',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ]
        );

        $userPetugas = User::updateOrCreate(
            ['email' => 'petugas@gmail.com'],
            [
                'nama' => 'petugas',
                'password' => Hash::make('password'),
                'role' => 'Petugas',
            ]
        );

        $userPelanggan1 = User::updateOrCreate(
            ['email' => 'pelanggan@gmail.com'],
            [
                'nama' => 'pelanggan',
                'password' => Hash::make('password'),
                'role' => 'Pelanggan',
            ]
        );

        $userPelanggan2 = User::updateOrCreate(
            ['email' => 'dimas@gmail.com'],
            [
                'nama' => 'Dimas',
                'password' => Hash::make('password'),
                'role' => 'Pelanggan',
            ]
        );

        // Profil Detail Pelanggan
        Pelanggan::updateOrCreate(
            ['user_id' => $userPelanggan1->id],
            [
                'jenis_kelamin' => 'Laki-laki',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Malioboro No. 12, Kota Yogyakarta',
            ]
        );

        Pelanggan::updateOrCreate(
            ['user_id' => $userPelanggan2->id],
            [
                'jenis_kelamin' => 'Laki-laki',
                'no_hp' => '08123456',
                'alamat' => 'Karanganyar, Jawa Tengah',
            ]
        );

        // -------------------------------------------------------------
        // 2. KATEGORI ALAT KAMERA
        // -------------------------------------------------------------
        $kategoriKamera = Kategori::updateOrCreate(
            ['nama_kategori' => 'Kamera'],
            ['deskripsi' => 'Kamera Mirrorless, DSLR, dan Cinema Digital profesional']
        );

        $kategoriDrone = Kategori::updateOrCreate(
            ['nama_kategori' => 'Drone'],
            ['deskripsi' => 'Drone aerial photography, quadcopter 4K, dan perlengkapan terbang']
        );

        $kategoriStabilizer = Kategori::updateOrCreate(
            ['nama_kategori' => 'Alat Pendukung'],
            ['deskripsi' => 'Gimbal stabilizer 3-axis, tripod, lighting, dan microphone wireless']
        );

        $kategoriLensa = Kategori::updateOrCreate(
            ['nama_kategori' => 'Lensa Kamera'],
            ['deskripsi' => 'Lensa prime, zoom telephoto, macro, dan filter lensa profesional']
        );

        // -------------------------------------------------------------
        // 3. DAFTAR ALAT & KAMERA (Menggunakan Foto Relevan di Storage)
        // -------------------------------------------------------------
        $alatList = [
            [
                'nama_alat' => 'Canon C50',
                'kategori_id' => $kategoriKamera->id,
                'harga_sewa' => 50000,
                'stok' => 5,
                'deskripsi' => 'Canon Cinema C50 4K Camcorder dengan dual pixel autofocus untuk rekaman video sinematik.',
                'gambar' => 'gambar-alat/y0KTQyvmVYMdVSNtfV8EfhMZEY3hXgxF6547anse.jpg',
            ],
            [
                'nama_alat' => 'DG Cam 4K Pro',
                'kategori_id' => $kategoriKamera->id,
                'harga_sewa' => 45000,
                'stok' => 15,
                'deskripsi' => 'Digital Action Camera 4K compact dengan stabilisasi tahan guncangan dan waterproof casing.',
                'gambar' => 'gambar-alat/FTTXchoU1AK8qxq6rBKLA9Jty1gFK7wHIbEn62cu.jpg',
            ],
            [
                'nama_alat' => 'Drone Quadcopter 4K',
                'kategori_id' => $kategoriDrone->id,
                'harga_sewa' => 80000,
                'stok' => 7,
                'deskripsi' => 'Drone aerial DJI dengan kamera gimbal 3-axis, sensor obstacle avoidance, dan jangkauan 5km.',
                'gambar' => 'gambar-alat/vSQHpYR6pPZb5kSVohgBKtOXRSPnEVUBmduOEaXJ.jpg',
            ],
            [
                'nama_alat' => 'Gimbal Stabilizer Pro',
                'kategori_id' => $kategoriStabilizer->id,
                'harga_sewa' => 35000,
                'stok' => 15,
                'deskripsi' => 'Handheld 3-Axis Gimbal Stabilizer untuk kamera DSLR & Mirrorless hingga beban 3.5 kg.',
                'gambar' => 'gambar-alat/ub5Xw2ZoltOMlIfxJ3YGccN251p3wL6srB3YE0hP.jpg',
            ],
            [
                'nama_alat' => 'Mic Wireless Dual Channel',
                'kategori_id' => $kategoriStabilizer->id,
                'harga_sewa' => 30000,
                'stok' => 20,
                'deskripsi' => 'Sistem microphone clip-on wireless 2.4GHz dengan noise reduction dan baterai tahan 8 jam.',
                'gambar' => 'gambar-alat/zfkjoLTiu3wish1yTElJdEWESTImPbvFVZYbe2HI.jpg',
            ],
            [
                'nama_alat' => 'Sony Alpha A7 IV',
                'kategori_id' => $kategoriKamera->id,
                'harga_sewa' => 200000,
                'stok' => 4,
                'deskripsi' => 'Full-frame Mirrorless 33MP, perekaman video 4K 60p 10-bit 4:2:2, real-time eye autofocus.',
                'gambar' => 'gambar-alat/3u82qnHn1oq1a2BNtqlANGktec4S5iiManW6nPb4.jpg',
            ],
            [
                'nama_alat' => 'Canon EOS R6 Mark II',
                'kategori_id' => $kategoriKamera->id,
                'harga_sewa' => 190000,
                'stok' => 2, // Stok Kritis (<= 2) untuk menguji fitur peringatan dashboard admin
                'deskripsi' => 'Kamera hybrid 24.2 MP full-frame CMOS, pemotretan beruntun 40 fps dan video 4K 60p oversampled.',
                'gambar' => 'gambar-alat/C6g1vqqXY6rJyuuwGQDstwqmRULtJadG8RmGA8xe.jpg',
            ],
            [
                'nama_alat' => 'Sony FE 24-70mm f/2.8 GM',
                'kategori_id' => $kategoriLensa->id,
                'harga_sewa' => 85000,
                'stok' => 6,
                'deskripsi' => 'Lensa zoom standar profesional aperture konstan f/2.8 untuk portrait, wedding, dan landscape.',
                'gambar' => 'gambar-alat/CLetaNAPEkGStFgf5fI0jPVSxvFeMNUJpzkQC1Yl.jpg',
            ],
            [
                'nama_alat' => 'DJI Mini 3 Pro Drone',
                'kategori_id' => $kategoriDrone->id,
                'harga_sewa' => 110000,
                'stok' => 3,
                'deskripsi' => 'Drone ultra-ringan di bawah 249g dengan sensor 1/1.3 inci dan perekaman video vertikal sejati.',
                'gambar' => 'gambar-alat/bFDNRiKlMMQzirM0A7TJLB54P8uN3y5z38M0BQy4.jpg',
            ],
            [
                'nama_alat' => 'Godox SL60W Studio Light',
                'kategori_id' => $kategoriStabilizer->id,
                'harga_sewa' => 40000,
                'stok' => 8,
                'deskripsi' => 'Lampu continuous LED daylight-balanced 5600K dengan Bowens mount untuk studio foto & video.',
                'gambar' => 'gambar-alat/mZITMxl91hyQnbgtDeevHnz3q5dXEnLOVlVrFGjn.jpg',
            ],
        ];

        foreach ($alatList as $item) {
            Alat::updateOrCreate(
                ['nama_alat' => $item['nama_alat']],
                $item
            );
        }

        // -------------------------------------------------------------
        // 4. TRANSAKSI CONTOH PEMINJAMAN (Menunggu, Diaprove, Dipinjam, Selesai)
        // -------------------------------------------------------------
        $alat1 = Alat::where('nama_alat', 'Canon C50')->first();
        $alat2 = Alat::where('nama_alat', 'Gimbal Stabilizer Pro')->first();
        $alat3 = Alat::where('nama_alat', 'Drone Quadcopter 4K')->first();
        $alat4 = Alat::where('nama_alat', 'Sony Alpha A7 IV')->first();

        // 1. Transaksi Selesai Tepat Waktu (Dikembalikan)
        if ($alat1 && $alat2) {
            $pjm1 = Peminjaman::updateOrCreate(
                ['kode' => 'PJM-20261118075123'],
                [
                    'user_id' => $userPelanggan1->id,
                    'tgl_pinjam' => Carbon::now()->subDays(6)->toDateString(),
                    'tgl_kembali' => Carbon::now()->subDays(4)->toDateString(),
                    'status' => 'Dikembalikan',
                    'subtotal' => 170000,
                    'nama_bukti' => 'Jaminan: KTP Asli | Metode: Tunai di Toko | Status: Lunas',
                ]
            );

            DetailPeminjaman::updateOrCreate(
                ['peminjaman_id' => $pjm1->id, 'alat_id' => $alat1->id],
                ['lama_pinjam' => 2, 'total' => 100000, 'status' => 'Dikembalikan']
            );

            DetailPeminjaman::updateOrCreate(
                ['peminjaman_id' => $pjm1->id, 'alat_id' => $alat2->id],
                ['lama_pinjam' => 2, 'total' => 70000, 'status' => 'Dikembalikan']
            );

            Pengembalian::updateOrCreate(
                ['peminjaman_id' => $pjm1->id],
                [
                    'tgl_dikembalikan' => Carbon::now()->subDays(4)->toDateString(),
                    'terlambat' => 0,
                    'denda' => 0,
                ]
            );
        }

        // 2. Transaksi Selesai dengan Denda Keterlambatan
        if ($alat3) {
            $pjm2 = Peminjaman::updateOrCreate(
                ['kode' => 'PJM-20261115091244'],
                [
                    'user_id' => $userPelanggan2->id,
                    'tgl_pinjam' => Carbon::now()->subDays(8)->toDateString(),
                    'tgl_kembali' => Carbon::now()->subDays(5)->toDateString(),
                    'status' => 'Dikembalikan',
                    'subtotal' => 240000,
                    'nama_bukti' => 'Jaminan: SIM A Fisik | Metode: Transfer Bank (BCA) | Status: Lunas',
                ]
            );

            DetailPeminjaman::updateOrCreate(
                ['peminjaman_id' => $pjm2->id, 'alat_id' => $alat3->id],
                ['lama_pinjam' => 3, 'total' => 240000, 'status' => 'Dikembalikan']
            );

            Pengembalian::updateOrCreate(
                ['peminjaman_id' => $pjm2->id],
                [
                    'tgl_dikembalikan' => Carbon::now()->subDays(3)->toDateString(),
                    'terlambat' => 2,
                    'denda' => 100000, // Denda 2 hari @ Rp 50.000
                ]
            );
        }

        // 3. Transaksi Berjalan (Sedang Dipinjam)
        if ($alat4) {
            $pjm3 = Peminjaman::updateOrCreate(
                ['kode' => 'PJM-20261119082210'],
                [
                    'user_id' => $userPelanggan2->id,
                    'tgl_pinjam' => Carbon::now()->subDays(1)->toDateString(),
                    'tgl_kembali' => Carbon::now()->addDays(2)->toDateString(),
                    'status' => 'Dipinjam',
                    'subtotal' => 600000,
                    'nama_bukti' => 'Jaminan: KTP Asli An. Dimas | Metode: QRIS | Status: Lunas',
                ]
            );

            DetailPeminjaman::updateOrCreate(
                ['peminjaman_id' => $pjm3->id, 'alat_id' => $alat4->id],
                ['lama_pinjam' => 3, 'total' => 600000, 'status' => 'Dipinjam']
            );
        }

        // 4. Transaksi Siap Ambil (Disetujui / Diaprove)
        if ($alat1) {
            $pjm4 = Peminjaman::updateOrCreate(
                ['kode' => 'PJM-20261120010203'],
                [
                    'user_id' => $userPelanggan1->id,
                    'tgl_pinjam' => Carbon::now()->toDateString(),
                    'tgl_kembali' => Carbon::now()->addDays(1)->toDateString(),
                    'status' => 'Diaprove',
                    'subtotal' => 50000,
                    'nama_bukti' => 'Jaminan: KTP Fisik | Syarat: DP 50% Terverifikasi',
                ]
            );

            DetailPeminjaman::updateOrCreate(
                ['peminjaman_id' => $pjm4->id, 'alat_id' => $alat1->id],
                ['lama_pinjam' => 1, 'total' => 50000, 'status' => 'Diaprove']
            );
        }

        // -------------------------------------------------------------
        // 5. AUDIT LOG AKTIVITAS AWAL
        // -------------------------------------------------------------
        LogAktivitas::create([
            'user_id' => $userAdmin->id,
            'aksi' => 'Inisialisasi Master Data',
            'keterangan' => 'Administrator mengonfigurasi katalog master data alat kamera dan kategori',
            'created_at' => Carbon::now()->subDays(10),
        ]);

        LogAktivitas::create([
            'user_id' => $userPetugas->id,
            'aksi' => 'Approve Peminjaman',
            'keterangan' => 'Petugas menyetujui pengajuan peminjaman PJM-20261120010203',
            'created_at' => Carbon::now()->subHours(2),
        ]);
    }
}
