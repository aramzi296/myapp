<!-- resources/views/pengalaman_kerja/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Detail Pengalaman Kerja</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 30%">Jabatan</th>
                                <td>{{ $pengalamanKerja->jabatan }}</td>
                            </tr>
                            <tr>
                                <th>Nama Perusahaan</th>
                                <td>{{ $pengalamanKerja->nama_perusahaan }}</td>
                            </tr>
                            @if($pengalamanKerja->nama_lain_perusahaan)
                            <tr>
                                <th>Nama Lain Perusahaan</th>
                                <td>{{ $pengalamanKerja->nama_lain_perusahaan }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Tahun Mulai</th>
                                <td>{{ $pengalamanKerja->tahun_mulai }}</td>
                            </tr>
                            <tr>
                                <th>Durasi</th>
                                <td>{{ $pengalamanKerja->jumlah_bulan }} bulan</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($pengalamanKerja->kerja_saat_ini)
                                    <span class="badge bg-success">Masih Bekerja</span>
                                    @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            @if($pengalamanKerja->keterangan)
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $pengalamanKerja->keterangan }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('pengalaman-kerja.index') }}" class="btn btn-secondary">Kembali</a>
                        <div>
                            <a href="{{ route('pengalaman-kerja.edit', $pengalamanKerja->id) }}"
                                class="btn btn-warning">Edit</a>
                            <form action="{{ route('pengalaman-kerja.destroy', $pengalamanKerja->id) }}" method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger ms-2">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection