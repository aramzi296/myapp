@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Profil: {{ $profil->nama_lengkap }}</span>
                    <div class="btn-group">
                        <a href="{{ route('profils.edit', $profil->slug) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('profils.destroy', $profil->slug) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            @if($profil->pas_foto)
                            <img src="{{ asset('storage/' . $profil->pas_foto) }}" alt="Pas Foto"
                                class="img-thumbnail mb-3" style="max-width: 200px;">
                            @else
                            <div class="bg-light p-5 text-center">
                                <i class="fas fa-user fa-5x text-secondary"></i>
                                <p class="mt-2">Tidak ada foto</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $profil->gelar_awalan }} {{ $profil->nama_lengkap }} {{ $profil->gelar_akhiran }}
                            </h3>
                            <hr>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nomor KTP</th>
                                    <td>{{ $profil->nomor_ktp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>{{ $profil->tanggal_lahir ? $profil->tanggal_lahir->format('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>{{ ucfirst($profil->jenis_kelamin) }}</td>
                                </tr>
                                <tr>
                                    <th>Status Perkawinan</th>
                                    <td>{{ ucfirst($profil->status_perkawinan) }}</td>
                                </tr>
                                <tr>
                                    <th>Pendidikan Terakhir</th>
                                    <td>{{ $profil->pendidikan_terakhir }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Alamat</h5>
                        <p>{{ $profil->alamat }}</p>
                        <p>
                            Kelurahan: {{ $profil->kelurahan_id ?? '-' }},
                            Kecamatan: {{ $profil->kecamatan_id ?? '-' }},
                            Kota/Kab: {{ $profil->kota_id ?? '-' }},
                            Provinsi: {{ $profil->propinsi_id ?? '-' }}
                        </p>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('profils.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection