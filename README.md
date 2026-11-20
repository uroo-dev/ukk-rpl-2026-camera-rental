# MyCam — Sistem Informasi Peminjaman & Rental Peralatan Kamera
### Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak 2026/2027 — Paket 1

<p align="center">
  <img src="public/template/assets/images/logo/brand-logo.svg" width="120" alt="MyCam Logo" />
</p>

<p align="center">
  <strong>Aplikasi Manajemen Rental Kamera Modern, Terintegrasi, Multi-Role, Multi-Item & Siap Uji</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12%2F13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="TailwindCSS" />
  <img src="https://img.shields.io/badge/DomPDF-Export-E11D48?style=for-the-badge" alt="DomPDF" />
  <img src="https://img.shields.io/badge/PhpSpreadsheet-Excel-059669?style=for-the-badge" alt="PhpSpreadsheet" />
  <img src="https://img.shields.io/badge/PHPUnit-23%20Passed%20(89%20Assertions)-10B981?style=for-the-badge&logo=php" alt="Tests" />
</p>

---

## 📌 Ringkasan Proyek

**MyCam** adalah aplikasi web berbasis **Laravel** yang dirancang khusus untuk memenuhi seluruh spesifikasi standar dan nilai tambah pada **Naskah Soal Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak Tahun Ajaran 2026/2027 Paket 1** (*Aplikasi Peminjaman Alat/Barang*).

Aplikasi ini mencakup alur lengkap bisnis sewa kamera dan peralatan multimedia: mulai dari pendaftaran pelanggan, peminjaman multi-alat (keranjang sewa), manajemen stok otomatis, persetujuan dan verifikasi jaminan oleh petugas, pengembalian alat dengan perhitungan denda keterlambatan otomatis, pencatatan log audit sistem, hingga laporan operasional dan perbendaharaan keuangan yang dapat diekspor ke format **PDF (DomPDF)** dan **Excel (.xlsx)**.

---

## 🌟 Fitur Utama Berdasarkan Role

### 1. 🛡️ Administrator
* **Dashboard Analitik & Monitoring Aset**:
  * Grafik interaktif alat yang paling sering dipinjam (*Top Popular Items*).
  * Peringatan stok kritis (*Critical Stock Alert*) untuk alat dengan sisa stok $\le 2$ unit.
  * Diagram distribusi kategori inventaris fisik.
  * Pemantauan pelanggan teraktif (*Most Active Renters*).
* **Master Data Management**:
  * Kelola kategori alat kamera (CRUD).
  * Kelola inventaris alat (nama, kategori, tarif sewa harian, stok, deskripsi, gambar).
  * Kelola pengguna (Admin, Petugas, Pelanggan) dengan validasi duplikasi email dan hashing password aman.
  * **Proteksi Integritas Data**: Mencegah penghapusan (*hard delete*) pada alat yang sedang memiliki transaksi peminjaman aktif.
* **Manajemen Keuangan & Arus Kas**:
  * Rekapitulasi metrik finansial: Total Pemasukan Kas, Pendapatan Sewa Terealisasi, Penerimaan Denda Keterlambatan, dan Estimasi Piutang/Pending.
  * Rincian penerimaan berdasarkan metode pembayaran (Tunai di Toko, Transfer Bank BCA/Mandiri/BRI, QRIS).
  * Filter rentang tanggal (`tanggal_mulai` & `tanggal_selesai`), status sewa, metode pembayaran, dan kata kunci.
  * **Ekspor Laporan Keuangan ke PDF (DomPDF)** resmi dengan tanda tangan otorisasi.
  * **Ekspor Laporan Keuangan ke Excel (.xlsx)** dengan styling header, borders, formula SUM, dan format mata uang Rupiah.
* **Audit Trail / Log Aktivitas**:
  * Memantau seluruh rekam jejak aktivitas sistem (perubahan data alat, kategori, user, dan transaksi).

---

### 2. 👔 Petugas
* **Dashboard Operasional Transaksi**:
  * Notifikasi visual badge dan toast otomatis saat terdapat permohonan pinjam status `Menunggu`.
  * Antrean persetujuan cepat (*Quick Approval / Rejection*) dengan verifikasi jaminan dan status pembayaran.
  * Pemantauan jatuh tempo peminjaman berjalan serta kalkulasi otomatis denda telat harian (Rp 50.000 / hari).
* **Alur Peminjaman & Pengembalian Alat**:
  * Persetujuan pengajuan (`Diaprove`).
  * Penyerahan alat ke pelanggan (`Dipinjam`) dengan input bukti jaminan identitas fisik (KTP/SIM/KTM).
  * Pengembalian alat (`Dikembalikan`) yang otomatis mengembalikan (*restock*) unit alat ke inventaris dan mencatat denda jika terlambat.
  * Pembatalan peminjaman (`Dibatalkan`) yang otomatis merestorasi kuota stok alat.
* **Laporan Riwayat Peminjaman**:
  * Filter riwayat berdasarkan rentang tanggal (`tanggal_mulai` & `tanggal_selesai`), status peminjaman, dan pencarian.
  * **Cetak / Unduh Laporan Peminjaman PDF (DomPDF)** berorientasi *Landscape* dengan tabel rinci dan tanda tangan penanggung jawab.
  * **Ekspor Laporan Peminjaman Excel (.xlsx)** via PhpSpreadsheet.
  * Cetak & Unduh Invoice PDF per transaksi.
* **Log Aktivitas Petugas**:
  * Khusus menampilkan riwayat operasional transaksi peminjaman dan pengembalian.

---

### 3. 👤 Pelanggan
* **Katalog & Beranda Sewa**:
  * Jelajahi katalog kamera, lensa, lighting, dan aksesoris multimedia.
  * Informasi ketersediaan stok fisik dan harga sewa per hari.
* **Multi-Peminjaman (Keranjang Sewa)**:
  * Fitur memasukkan banyak alat sekaligus ke keranjang sewa (*Rent Cart*).
  * Pemilihan durasi hari terpadu dengan kalkulasi grand total otomatis.
* **Opsi Pembayaran & Jaminan Terintegrasi**:
  * Pilihan metode: Tunai di Toko, Transfer Bank (BCA, Mandiri, BRI), QRIS.
  * Opsi pembayaran: Lunas (100%), Uang Muka / DP (50%), atau Bayar di Toko saat Pengambilan.
  * Jaminan identitas wajib: KTP Fisik, SIM, atau Kartu Pelajar / Mahasiswa.
* **Validasi Anti-Nunggak (UKK Challenge #1)**:
  * Pelanggan yang masih memiliki peminjaman aktif berjalan secara otomatis diblokir dari mengajukan peminjaman baru sampai pengembalian alat selesai diverifikasi.
* **Riwayat Peminjaman & Invoice**:
  * Memantau status transaksi (`Menunggu`, `Diaprove`, `Dipinjam`, `Dikembalikan`, `Dibatalkan`).
  * Cetak dan unduh dokumen **Invoice PDF (DomPDF)** resmi.
  * Log aktivitas peminjaman pribadi.

---

## 🗄️ Database & Fitur Nilai Tambah UKK

Skrip basis data lengkap tersedia di: [`dokumentasi/mycam_db.sql`](dokumentasi/mycam_db.sql).

Fitur objek basis data lanjutan yang telah diimplementasikan:
1. **Stored Procedures**:
   * `sp_approve_peminjaman`: Menyetujui peminjaman menggunakan blok transaksi atomik (`START TRANSACTION`, `COMMIT`, dan penanganan `ROLLBACK`).
   * `sp_pengembalian_alat`: Memproses pengembalian unit alat, mengembalikan stok fisik, serta menghitung denda keterlambatan secara otomatis.
2. **Stored Functions**:
   * `fn_hitung_total_sewa`: Menghitung total biaya sewa berdasarkan tarif harian dan lama hari pinjam.
   * `fn_hitung_denda`: Menghitung denda keterlambatan berdasarkan selisih hari pengembalian riil.
3. **Database Triggers**:
   * `trg_log_update_alat`: Mencatat riwayat perubahan data stok dan spesifikasi alat ke tabel log audit secara otomatis.
4. **Relational Integrity**:
   * Foreign keys dengan constraint integritas referensial dan pencegahan kehilangan data (*soft deletes*).

---

## 📁 Struktur Direktori Proyek

```text
my-cam/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── AlatController.php          # Kelola inventaris kamera
│   │   │   ├── DashboardController.php     # Dashboard analitik admin & grafik
│   │   │   ├── KategoriController.php      # Kelola kategori alat
│   │   │   ├── KeuanganController.php      # Fitur keuangan, arus kas & ekspor
│   │   │   ├── LogController.php           # Log aktivitas sistem
│   │   │   └── UserController.php          # Manajemen user multi-role
│   │   ├── Auth/
│   │   │   └── AuthControler.php           # Login, register, logout & role redirection
│   │   ├── Pelanggan/
│   │   │   ├── BerandaController.php       # Katalog publik & landing page
│   │   │   └── PeminjamanController.php    # Multi-peminjaman, invoice PDF & log pelanggan
│   │   └── Petugas/
│   │       ├── DashboardController.php     # Dashboard operasional & antrean
│   │       └── PeminjamanController.php    # Persetujuan, serah-terima, pengembalian & ekspor
│   └── Models/                             # Eloquent models (Alat, Peminjaman, User, dll.)
├── dokumentasi/
│   ├── mycam_db.sql                        # Skrip database MySQL, procedures, triggers
│   ├── Rancangan.drawio                    # Diagram alur sistem, ERD & rancangan UI
│   ├── soal-ukk-26.pdf                     # Naskah soal resmi UKK RPL Paket 1 2026/2027
│   ├── Screenshots/                        # Tangkapan layar antarmuka aplikasi
│   └── LAPORAN PRAKTIKUM...doc             # Laporan praktikum penyusunan proyek
├── public/
│   └── template/                           # Asset UI Donezo (Tailwind, Lucide, Chart.js)
├── resources/
│   └── views/
│       ├── Admin/                          # View dashboard, master data & keuangan
│       ├── Petugas/                        # View operasional peminjaman, invoice & PDF laporan
│       ├── Pelanggan/                      # View katalog, keranjang sewa, riwayat & invoice
│       └── Layout/                         # Layout utama, sidebar, header & toast container
├── routes/
│   └── web.php                             # Definisi route sistem terproteksi middleware
└── tests/
    └── Feature/
        └── MyCamWorkflowTest.php           # 23 automated feature test cases (89 assertions)
```

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Kloning Repositori
```bash
git clone https://github.com/uroo-dev/ukk-rpl-2026-camera-rental.git
cd ukk-rpl-2026-camera-rental
```

### 2. Pasang Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasi environment dan sesuaikan kredensial database Anda:
```bash
cp .env.example .env
php artisan key:generate
```

Pastikan konfigurasi database di `.env` sudah sesuai (contoh MySQL):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mycam_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Import / Migrasi Basis Data
Anda dapat mengimpor file `dokumentasi/mycam_db.sql` ke database MySQL Anda, atau menjalankan migrasi Laravel:
```bash
php artisan migrate --seed
```

### 5. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi dapat diakses melalui peramban web di: `http://127.0.0.1:8000`

---

## 🔑 Akun Demo Pengujian (Login)

Gunakan kredensial berikut untuk menguji masing-masing hak akses:

| Role | Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@gmail.com` | `admin123` | Master Data, Dashboard Analitik, Laporan Keuangan, Ekspor PDF/Excel, Audit Log |
| **Petugas** | `petugas@gmail.com` | `petugas123` | Verifikasi Pinjaman, Penyerahan Alat, Pengembalian & Denda, Laporan Peminjaman |
| **Pelanggan** | `pelanggan@gmail.com` | `pelanggan123` | Sewa Multi-Alat, Keranjang, Metode Pembayaran, Cek Invoice, Riwayat Pinjaman |

---

## 🧪 Pengujian Otomatis (*Automated Testing*)

Proyek ini telah dilengkapi dengan unit dan feature test komprehensif menggunakan PHPUnit:

```bash
php artisan test
```

**Hasil Pengujian:**
```text
PASS  Tests\Feature\MyCamWorkflowTest
✓ alur lengkap autentikasi admin petugas dan pelanggan
✓ validasi tambah user baru dengan email duplikat memberikan feedback
✓ pelanggan tidak bisa meminjam jika masih punya tanggungan pinjaman aktif
✓ peminjaman multi alat mengurangi stok masing masing barang
✓ penolakan peminjaman mengembalikan stok barang
✓ pengembalian peminjaman mengembalikan stok barang dan menghitung denda
✓ admin tidak bisa menghapus alat yang sedang dipinjam
✓ dashboard petugas dan admin sukses dimuat dengan chart data
✓ petugas hanya melihat log peminjaman sedangkan admin melihat semua log
✓ filter laporan peminjaman berdasarkan tanggal dan status
✓ ekspor laporan peminjaman dompdf dan excel
✓ fitur keuangan admin dan ekspor pdf excel
✓ ekspor invoice dompdf
...
Tests:    23 passed (89 assertions)
Duration: 3.02s
```

---

## 📄 Lisensi

Proyek ini dibuat dan didistribusikan untuk keperluan pendidikan dan Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak di bawah lisensi [MIT License](LICENSE).
