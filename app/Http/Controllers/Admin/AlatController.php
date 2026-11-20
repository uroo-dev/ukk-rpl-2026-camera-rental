<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alat = Alat::with('kategori')->latest()->paginate(15);
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('Admin.alat.index', compact('alat', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_alat' => 'required|string|max:255|unique:alat,nama_alat',
            'harga_sewa' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string|max:1000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'nama_alat.required' => 'Nama alat wajib diisi.',
            'nama_alat.unique' => 'Nama alat ini sudah terdaftar.',
            'harga_sewa.required' => 'Harga sewa wajib diisi.',
            'harga_sewa.numeric' => 'Harga sewa harus berupa angka valid.',
            'harga_sewa.min' => 'Harga sewa tidak boleh bernilai negatif.',
            'stok.required' => 'Jumlah stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa bilangan bulat.',
            'stok.min' => 'Stok tidak boleh bernilai negatif.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('gambar-alat', 'public');
        }

        $alat = Alat::create($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Tambah Alat',
            'keterangan' => 'Alat baru "' . $alat->nama_alat . '" berhasil ditambahkan dengan stok ' . $alat->stok . '.',
        ]);

        return redirect()->back()->with('success', 'Alat "' . $alat->nama_alat . '" berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alat $alat)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_alat' => ['required', 'string', 'max:255', Rule::unique('alat', 'nama_alat')->ignore($alat->id)],
            'harga_sewa' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string|max:1000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'nama_alat.required' => 'Nama alat wajib diisi.',
            'nama_alat.unique' => 'Nama alat ini sudah digunakan oleh alat lain.',
            'harga_sewa.required' => 'Harga sewa wajib diisi.',
            'harga_sewa.numeric' => 'Harga sewa harus berupa angka valid.',
            'stok.required' => 'Jumlah stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa bilangan bulat.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('gambar')) {
            if ($alat->gambar && Storage::disk('public')->exists($alat->gambar)) {
                Storage::disk('public')->delete($alat->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('gambar-alat', 'public');
        }

        $alat->update($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Update Alat',
            'keterangan' => 'Data alat "' . $alat->nama_alat . '" berhasil diperbarui.',
        ]);

        return redirect()->back()->with('success', 'Data alat "' . $alat->nama_alat . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        // UKK Tantangan 4: Obyek yang sudah/sedang dipinjam tidak bisa dihapus (hard delete)
        $hasEverBeenLoaned = $alat->detailPeminjaman()->exists();

        if ($hasEverBeenLoaned) {
            return redirect()->back()->with('error', 'Alat "' . $alat->nama_alat . '" tidak dapat dihapus karena sudah memiliki riwayat atau sedang dalam peminjaman.');
        }

        $nama = $alat->nama_alat;

        if ($alat->gambar && Storage::disk('public')->exists($alat->gambar)) {
            Storage::disk('public')->delete($alat->gambar);
        }

        $alat->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Hapus Alat',
            'keterangan' => 'Alat "' . $nama . '" berhasil dihapus dari sistem.',
        ]);

        return redirect()->back()->with('success', 'Alat "' . $nama . '" berhasil dihapus.');
    }
}
