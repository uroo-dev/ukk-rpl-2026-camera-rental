<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MyCamWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $petugas;
    protected User $pelanggan;
    protected Kategori $kategori;
    protected Alat $alat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'nama' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'Admin',
        ]);

        $this->petugas = User::create([
            'nama' => 'Petugas Test',
            'email' => 'petugas@test.com',
            'password' => Hash::make('password123'),
            'role' => 'Petugas',
        ]);

        $this->pelanggan = User::create([
            'nama' => 'Pelanggan Test',
            'email' => 'pelanggan@test.com',
            'password' => Hash::make('password123'),
            'role' => 'Pelanggan',
        ]);

        $this->kategori = Kategori::create([
            'nama_kategori' => 'Kamera Mirrorless',
            'deskripsi' => 'Kategori Kamera Mirrorless',
        ]);

        $this->alat = Alat::create([
            'kategori_id' => $this->kategori->id,
            'nama_alat' => 'Sony Alpha A7 IV',
            'harga_sewa' => 200000,
            'stok' => 3,
            'deskripsi' => 'Full frame camera',
        ]);
    }

    public function test_peminjaman_mengurangi_stok_alat(): void
    {
        $this->actingAs($this->pelanggan);

        $initialStock = $this->alat->stok;

        $response = $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
        ]);

        $response->assertRedirect(route('riwayat.peminjaman'));

        $this->alat->refresh();
        $this->assertEquals($initialStock - 1, $this->alat->stok, 'Stok alat harus berkurang 1 saat diajukan peminjaman.');

        $this->assertDatabaseHas('peminjaman', [
            'user_id' => $this->pelanggan->id,
            'status' => 'Menunggu',
        ]);
    }

    public function test_pembatalan_peminjaman_mengembalikan_stok_alat(): void
    {
        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
        ]);

        $this->alat->refresh();
        $this->assertEquals(2, $this->alat->stok);

        $peminjaman = Peminjaman::where('user_id', $this->pelanggan->id)->first();

        // Petugas membatalkan peminjaman
        $this->actingAs($this->petugas);
        $response = $this->get(route('batal', $peminjaman->id));
        $response->assertRedirect();

        $this->alat->refresh();
        $this->assertEquals(3, $this->alat->stok, 'Stok alat harus kembali setelah peminjaman dibatalkan.');
        $peminjaman->refresh();
        $this->assertEquals('Dibatalkan', $peminjaman->status);
    }

    public function test_pengembalian_alat_menambah_stok_kembali(): void
    {
        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
        ]);

        $peminjaman = Peminjaman::where('user_id', $this->pelanggan->id)->first();

        // Petugas menyerahkan alat
        $this->actingAs($this->petugas);
        $this->put(route('pinjam', $peminjaman->id), [
            'nama_bukti' => 'KTP Budi',
        ]);

        $this->alat->refresh();
        $this->assertEquals(2, $this->alat->stok);

        // Petugas memproses pengembalian alat
        $response = $this->get(route('kembalikan', $peminjaman->id));
        $response->assertRedirect();

        $this->alat->refresh();
        $this->assertEquals(3, $this->alat->stok, 'Stok alat harus bertambah kembali saat alat dikembalikan.');
        $peminjaman->refresh();
        $this->assertEquals('Dikembalikan', $peminjaman->status);
        $this->assertDatabaseHas('pengembalian', [
            'peminjaman_id' => $peminjaman->id,
        ]);
    }

    public function test_validasi_tambah_user_email_duplikat_memberikan_pesan_error(): void
    {
        $this->actingAs($this->admin);

        // Mencoba tambah user dengan email yang sudah ada
        $response = $this->post(route('user.store'), [
            'nama' => 'Admin Baru',
            'email' => 'admin@test.com', // email sudah ada
            'password' => 'secret123',
            'role' => 'Admin',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_update_user_dengan_email_sama_tidak_error_unique(): void
    {
        $this->actingAs($this->admin);

        // Update user admin sendiri atau petugas tanpa mengganti email
        $response = $this->put(route('user.update', $this->petugas->id), [
            'nama' => 'Petugas Updated',
            'email' => 'petugas@test.com', // email tetap sama
            'role' => 'Petugas',
        ]);

        $response->assertSessionHasNoErrors();
        $this->petugas->refresh();
        $this->assertEquals('Petugas Updated', $this->petugas->nama);
    }

    public function test_update_alat_dengan_nama_sama_tidak_error_unique(): void
    {
        $this->actingAs($this->admin);

        $response = $this->put(route('alat.update', $this->alat->id), [
            'kategori_id' => $this->kategori->id,
            'nama_alat' => 'Sony Alpha A7 IV', // nama tetap sama
            'harga_sewa' => 250000,
            'stok' => 5,
        ]);

        $response->assertSessionHasNoErrors();
        $this->alat->refresh();
        $this->assertEquals(250000, $this->alat->harga_sewa);
        $this->assertEquals(5, $this->alat->stok);
    }

    public function test_update_kategori_dengan_nama_sama_tidak_error_unique(): void
    {
        $this->actingAs($this->admin);

        $response = $this->put(route('kategori.update', $this->kategori->id), [
            'nama_kategori' => 'Kamera Mirrorless', // nama tetap sama
            'deskripsi' => 'Deskripsi baru',
        ]);

        $response->assertSessionHasNoErrors();
        $this->kategori->refresh();
        $this->assertEquals('Deskripsi baru', $this->kategori->deskripsi);
    }

    public function test_peminjaman_gagal_jika_stok_habis(): void
    {
        $this->alat->update(['stok' => 0]);

        $this->actingAs($this->pelanggan);

        $response = $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, Peminjaman::where('user_id', $this->pelanggan->id)->count());
    }

    public function test_approve_peminjaman_dengan_syarat_jaminan(): void
    {
        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
        ]);

        $peminjaman = Peminjaman::where('user_id', $this->pelanggan->id)->first();

        $this->actingAs($this->petugas);
        $response = $this->post(route('approve', $peminjaman->id), [
            'syarat_jaminan' => 'KTP No. 3201999988880001 a.n. Pelanggan',
        ]);

        $response->assertSessionHas('success');
        $peminjaman->refresh();
        $this->assertEquals('Diaprove', $peminjaman->status);
        $this->assertEquals('KTP No. 3201999988880001 a.n. Pelanggan', $peminjaman->nama_bukti);
    }

    public function test_alat_yang_pernah_dipinjam_tidak_bisa_dihapus(): void
    {
        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(1)->toDateString(),
        ]);

        $this->actingAs($this->admin);
        $response = $this->delete(route('alat.destroy', $this->alat->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('alat', ['id' => $this->alat->id]);
    }

    public function test_user_yang_pernah_meminjam_tidak_bisa_dihapus(): void
    {
        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(1)->toDateString(),
        ]);

        $this->actingAs($this->admin);
        $response = $this->delete(route('user.destroy', $this->pelanggan->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->pelanggan->id]);
    }

    public function test_dashboard_petugas_dan_admin_sukses_dimuat_dengan_chart_data(): void
    {
        $this->actingAs($this->petugas);
        $responsePetugas = $this->get(route('petugas.dashboard.index'));
        $responsePetugas->assertStatus(200);
        $responsePetugas->assertViewHasAll([
            'statusMenunggu',
            'peminjamanMenunggu',
            'peminjamanSedangDipinjam',
            'aktivitasPeminjaman',
            'keuanganLabels',
            'keuanganSewa',
            'keuanganDenda'
        ]);

        $this->actingAs($this->admin);
        $responseAdmin = $this->get(route('admin.dashboard.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertViewHasAll([
            'totalAlat',
            'totalKategori',
            'totalStokFisik',
            'topAlatLabels',
            'alatStokKritis',
            'kategoriLabels',
            'topUserLabels',
            'keuanganLabels'
        ]);
    }

    public function test_pelanggan_bisa_melihat_log_aktivitas(): void
    {
        $this->actingAs($this->pelanggan);
        $response = $this->get(route('pelanggan.log'));
        $response->assertStatus(200);
        $response->assertViewHas('log');
    }

    public function test_log_petugas_hanya_menampilkan_aktivitas_peminjaman(): void
    {
        \App\Models\LogAktivitas::create([
            'user_id' => $this->admin->id,
            'aksi' => 'Tambah User',
            'keterangan' => 'Admin menambahkan user baru',
        ]);

        \App\Models\LogAktivitas::create([
            'user_id' => $this->petugas->id,
            'aksi' => 'Approve Peminjaman',
            'keterangan' => 'Petugas menyetujui peminjaman PJM-12345',
        ]);

        $this->actingAs($this->petugas);
        $response = $this->get(route('user.log'));
        $response->assertStatus(200);
        $response->assertSee('Approve Peminjaman');
        $response->assertDontSee('Tambah User');
    }

    public function test_multi_peminjaman_mengurangi_stok_semua_alat_yang_dipilih(): void
    {
        $alat2 = Alat::create([
            'kategori_id' => $this->kategori->id,
            'nama_alat' => 'Lensa Sony FE 24-70mm GM II',
            'harga_sewa' => 150000,
            'stok' => 2,
            'deskripsi' => 'Lensa zoom standar',
        ]);

        $this->actingAs($this->pelanggan);

        $response = $this->post(route('ajukan.peminjaman.multi'), [
            'alat_ids' => [$this->alat->id, $alat2->id],
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
            'metode_pembayaran' => 'Transfer Bank BCA',
            'jumlah_bayar' => 700000,
            'jaminan' => 'KTP No. 3201123456789999',
            'catatan' => 'Mohon cek baterai cadangan',
        ]);

        $response->assertRedirect(route('riwayat.peminjaman'));

        $this->alat->refresh();
        $alat2->refresh();
        $this->assertEquals(2, $this->alat->stok, 'Stok alat 1 harus berkurang 1.');
        $this->assertEquals(1, $alat2->stok, 'Stok alat 2 harus berkurang 1.');

        $peminjaman = Peminjaman::where('user_id', $this->pelanggan->id)->first();
        $this->assertNotNull($peminjaman);
        $this->assertEquals(2, $peminjaman->detailPeminjaman()->count(), 'Peminjaman harus memiliki 2 detail item alat.');
        $this->assertStringContainsString('Transfer Bank BCA', $peminjaman->nama_bukti);
        $this->assertStringContainsString('KTP No. 3201123456789999', $peminjaman->nama_bukti);
    }

    public function test_multi_peminjaman_ditolak_jika_masih_memiliki_peminjaman_aktif(): void
    {
        $this->actingAs($this->pelanggan);
        // Buat peminjaman pertama
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
        ]);

        $alat2 = Alat::create([
            'kategori_id' => $this->kategori->id,
            'nama_alat' => 'Lensa 50mm f/1.2',
            'harga_sewa' => 120000,
            'stok' => 2,
        ]);

        // Coba ajukan multi peminjaman saat masih ada pinjaman aktif
        $response = $this->post(route('ajukan.peminjaman.multi'), [
            'alat_ids' => [$alat2->id],
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(1)->toDateString(),
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Peminjaman::where('user_id', $this->pelanggan->id)->count(), 'Tidak boleh menambah peminjaman baru saat masih aktif.');
    }

    public function test_pengembalian_multi_peminjaman_mengembalikan_stok_semua_alat(): void
    {
        $alat2 = Alat::create([
            'kategori_id' => $this->kategori->id,
            'nama_alat' => 'Lighting Godox SL60W',
            'harga_sewa' => 50000,
            'stok' => 2,
        ]);

        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman.multi'), [
            'alat_ids' => [$this->alat->id, $alat2->id],
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(1)->toDateString(),
        ]);

        $this->alat->refresh();
        $alat2->refresh();
        $this->assertEquals(2, $this->alat->stok);
        $this->assertEquals(1, $alat2->stok);

        $peminjaman = Peminjaman::where('user_id', $this->pelanggan->id)->first();

        // Petugas memproses pengembalian
        $this->actingAs($this->petugas);
        $response = $this->get(route('kembalikan', $peminjaman->id));
        $response->assertSessionHas('success');

        $this->alat->refresh();
        $alat2->refresh();
        $this->assertEquals(3, $this->alat->stok, 'Stok alat 1 harus kembali semula.');
        $this->assertEquals(2, $alat2->stok, 'Stok alat 2 harus kembali semula.');

        $peminjaman->refresh();
        $this->assertEquals('Dikembalikan', $peminjaman->status);
    }

    public function test_filter_laporan_peminjaman_berdasarkan_tanggal_dan_status(): void
    {
        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
        ]);

        $this->actingAs($this->petugas);

        // Filter status Menunggu
        $response = $this->get(route('peminjaman.all', [
            'status' => 'Menunggu',
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'tanggal_selesai' => now()->addDay()->toDateString(),
        ]));
        $response->assertStatus(200);
        $response->assertSee('Menunggu');

        // Filter status Dikembalikan (harus kosong karena belum dikembalikan)
        $response2 = $this->get(route('peminjaman.all', [
            'status' => 'Dikembalikan',
        ]));
        $response2->assertStatus(200);
        $response2->assertSee('Belum ada catatan transaksi peminjaman.');
    }

    public function test_ekspor_laporan_peminjaman_dompdf_dan_excel(): void
    {
        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
        ]);

        $this->actingAs($this->petugas);

        // Ekspor PDF via DomPDF
        $pdfResponse = $this->get(route('peminjaman.laporan.pdf', [
            'status' => 'All',
        ]));
        $pdfResponse->assertStatus(200);
        $this->assertTrue(str_contains($pdfResponse->headers->get('content-type'), 'application/pdf'));

        // Ekspor Excel via PhpSpreadsheet
        $excelResponse = $this->get(route('peminjaman.laporan.excel', [
            'status' => 'All',
        ]));
        $excelResponse->assertStatus(200);
        $this->assertTrue(str_contains($excelResponse->headers->get('content-type'), 'spreadsheetml.sheet'));
    }

    public function test_fitur_keuangan_admin_dan_ekspor_pdf_excel(): void
    {
        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(2)->toDateString(),
            'metode_pembayaran' => 'Transfer Bank (BCA)',
            'jumlah_bayar' => 400000,
        ]);

        // Pelanggan tidak boleh akses menu keuangan admin
        $this->get(route('admin.keuangan.index'))->assertStatus(403);

        // Admin mengakses halaman keuangan
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.keuangan.index'));
        $response->assertStatus(200);
        $response->assertSee('Laporan Keuangan');
        $response->assertSee('Total Pemasukan Kas');

        // Ekspor Keuangan PDF
        $pdfResponse = $this->get(route('admin.keuangan.pdf'));
        $pdfResponse->assertStatus(200);
        $this->assertTrue(str_contains($pdfResponse->headers->get('content-type'), 'application/pdf'));

        // Ekspor Keuangan Excel
        $excelResponse = $this->get(route('admin.keuangan.excel'));
        $excelResponse->assertStatus(200);
        $this->assertTrue(str_contains($excelResponse->headers->get('content-type'), 'spreadsheetml.sheet'));
    }

    public function test_ekspor_invoice_dompdf(): void
    {
        $this->actingAs($this->pelanggan);
        $this->post(route('ajukan.peminjaman', $this->alat->id), [
            'tgl_pinjam' => now()->toDateString(),
            'tgl_kembali' => now()->addDays(1)->toDateString(),
        ]);

        $peminjaman = Peminjaman::where('user_id', $this->pelanggan->id)->first();

        // Download Invoice PDF Pelanggan
        $response = $this->get(route('invoice.pdf', $peminjaman->id));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'application/pdf'));
    }
}
