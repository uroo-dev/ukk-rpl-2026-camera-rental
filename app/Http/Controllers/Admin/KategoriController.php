<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::withCount('alat')->latest()->paginate(15);
        return view('Admin.kategori.index', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori ini sudah terdaftar.',
        ]);

        $kategori = Kategori::create($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Tambah Kategori',
            'keterangan' => 'Kategori "' . $kategori->nama_kategori . '" berhasil ditambahkan.',
        ]);

        return redirect()->back()->with('success', 'Kategori "' . $kategori->nama_kategori . '" berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', Rule::unique('kategori', 'nama_kategori')->ignore($kategori->id)],
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori ini sudah digunakan oleh kategori lain.',
        ]);

        $kategori->update($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Update Kategori',
            'keterangan' => 'Data kategori "' . $kategori->nama_kategori . '" berhasil diperbarui.',
        ]);

        return redirect()->back()->with('success', 'Kategori "' . $kategori->nama_kategori . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        if ($kategori->alat()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori "' . $kategori->nama_kategori . '" tidak dapat dihapus karena masih digunakan oleh alat.');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Hapus Kategori',
            'keterangan' => 'Kategori "' . $nama . '" berhasil dihapus dari sistem.',
        ]);

        return redirect()->back()->with('success', 'Kategori "' . $nama . '" berhasil dihapus.');
    }
}
