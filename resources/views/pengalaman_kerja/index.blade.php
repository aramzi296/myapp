<!-- resources/views/pengalaman_kerja/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Pengalaman Kerja</h5>
                    <a href="{{ route('pengalaman-kerja.create') }}" class="btn btn-primary">Tambah Pengalaman Kerja</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Jabatan</th>
                                    <th>Perusahaan</th>
                                    <th>Tahun Mulai</th>
                                    <th>Durasi (Bulan)</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengalamanKerjas as $index => $pengalamanKerja)
                                <tr>
                                    <td>{{ $index + $pengalamanKerjas->firstItem() }}</td>
                                    <td>{{ $pengalamanKerja->jabatan }}</td>
                                    <td>{{ $pengalamanKerja->nama_perusahaan }}</td>
                                    <td>{{ $pengalamanKerja->tahun_mulai }}</td>
                                    <td>{{ $pengalamanKerja->jumlah_bulan }}</td>
                                    <td>
                                        @if($pengalamanKerja->kerja_saat_ini)
                                        <span class="badge bg-success">Saat Ini</span>
                                        @else
                                        <span class="badge bg-secondary">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('pengalaman-kerja.show', $pengalamanKerja->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('pengalaman-kerja.edit', $pengalamanKerja->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('pengalaman-kerja.destroy', $pengalamanKerja->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pengalaman kerja</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $pengalamanKerjas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection