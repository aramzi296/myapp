<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profils = Profil::latest()->paginate(10);
        return view('profils.index', compact('profils'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('profils.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gelar_awalan' => 'nullable|string|max:20',
            'nama_lengkap' => 'required|string|max:100',
            'gelar_akhiran' => 'nullable|string|max:20',
            'nomor_ktp' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'kelurahan_id' => 'nullable|integer',
            'kecamatan_id' => 'nullable|integer',
            'kota_id' => 'nullable|integer',
            'propinsi_id' => 'nullable|integer',
            'negara_id' => 'nullable|integer',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'status_perkawinan' => 'nullable|in:singel,menikah,cerai,janda',
            'pendidikan_terakhir' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3,Lainnya',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('pas_foto')) {
            $validated['pas_foto'] = $request->file('pas_foto')->store('profils', 'public');
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['nama_lengkap'] . ' ' . now()->format('YmdHis'));

        // Set user_id to current user
        $validated['user_id'] = auth()->id();

        Profil::create($validated);

        return redirect()->route('profils.index')
            ->with('success', 'Profil berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profil $profil)
    {
        return view('profils.show', compact('profil'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profil $profil)
    {
        return view('profils.edit', compact('profil'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profil $profil)
    {
        $validated = $request->validate([
            'gelar_awalan' => 'nullable|string|max:20',
            'nama_lengkap' => 'required|string|max:100',
            'gelar_akhiran' => 'nullable|string|max:20',
            'nomor_ktp' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'kelurahan_id' => 'nullable|integer',
            'kecamatan_id' => 'nullable|integer',
            'kota_id' => 'nullable|integer',
            'propinsi_id' => 'nullable|integer',
            'negara_id' => 'nullable|integer',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'status_perkawinan' => 'nullable|in:singel,menikah,cerai,janda',
            'pendidikan_terakhir' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3,Lainnya',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_pas_foto' => 'nullable|boolean',
        ]);

        // Handle file upload/removal
        if ($request->has('remove_pas_foto') && $request->remove_pas_foto) {
            // Delete old file
            if ($profil->pas_foto) {
                Storage::disk('public')->delete($profil->pas_foto);
                $validated['pas_foto'] = null;
            }
        } elseif ($request->hasFile('pas_foto')) {
            // Delete old file if exists
            if ($profil->pas_foto) {
                Storage::disk('public')->delete($profil->pas_foto);
            }
            // Store new file
            $validated['pas_foto'] = $request->file('pas_foto')->store('profils', 'public');
        } else {
            // Keep existing file if not changing
            unset($validated['pas_foto']);
        }

        // Update slug if nama_lengkap changed
        if ($profil->nama_lengkap !== $validated['nama_lengkap']) {
            $validated['slug'] = Str::slug($validated['nama_lengkap'] . ' ' . now()->format('YmdHis'));
        }

        $profil->update($validated);

        return redirect()->route('profils.index')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profil $profil)
    {
        // Delete associated file
        if ($profil->pas_foto) {
            Storage::disk('public')->delete($profil->pas_foto);
        }

        $profil->delete();

        return redirect()->route('profils.index')
            ->with('success', 'Profil berhasil dihapus');
    }
}
