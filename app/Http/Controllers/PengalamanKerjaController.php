<?php

namespace App\Http\Controllers;

use App\Models\PengalamanKerja;
use Illuminate\Http\Request;

class PengalamanKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengalamanKerjas = PengalamanKerja::latest()->paginate(10);
        return view('pengalaman_kerja.index', compact('pengalamanKerjas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengalaman_kerja.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'nama_lain_perusahaan' => 'nullable|string|max:255',
            'tahun_mulai' => 'required|numeric|min:1900|max:' . date('Y'),
            'jumlah_bulan' => 'required|numeric|min:1',
            'kerja_saat_ini' => 'required|boolean',
            'keterangan' => 'nullable|string',
        ]);

        PengalamanKerja::create($request->all());

        return redirect()->route('pengalaman-kerja.index')
            ->with('success', 'Pengalaman kerja berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PengalamanKerja $pengalamanKerja)
    {
        return view('pengalaman_kerja.show', compact('pengalamanKerja'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengalamanKerja $pengalamanKerja)
    {
        return view('pengalaman_kerja.edit', compact('pengalamanKerja'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengalamanKerja $pengalamanKerja)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'nama_lain_perusahaan' => 'nullable|string|max:255',
            'tahun_mulai' => 'required|numeric|min:1900|max:' . date('Y'),
            'jumlah_bulan' => 'required|numeric|min:1',
            'kerja_saat_ini' => 'required|boolean',
            'keterangan' => 'nullable|string',
        ]);

        $pengalamanKerja->update($request->all());

        return redirect()->route('pengalaman-kerja.index')
            ->with('success', 'Pengalaman kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengalamanKerja $pengalamanKerja)
    {
        $pengalamanKerja->delete();

        return redirect()->route('pengalaman-kerja.index')
            ->with('success', 'Pengalaman kerja berhasil dihapus.');
    }
}
