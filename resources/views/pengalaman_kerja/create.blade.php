<!-- resources/views/pengalaman_kerja/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Pengalaman Kerja</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengalaman-kerja.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan"
                                name="jabatan" value="{{ old('jabatan') }}">
                            @error('jabatan')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_perusahaan" class="form-label">Nama Perusahaan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}">
                            @error('nama_perusahaan')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_lain_perusahaan" class="form-label">Nama Lain Perusahaan</label>
                            <input type="text" class="form-control @error('nama_lain_perusahaan') is-invalid @enderror"
                                id="nama_lain_perusahaan" name="nama_lain_perusahaan"
                                value="{{ old('nama_lain_perusahaan') }}">
                            @error('nama_lain_perusahaan')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tahun_mulai" class="form-label">Tahun Mulai <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('tahun_mulai') is-invalid @enderror"
                                    id="tahun_mulai" name="tahun_mulai" min="1900" max="{{ date('Y') }}"
                                    value="{{ old('tahun_mulai') }}">
                                @error('tahun_mulai')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="jumlah_bulan" class="form-label">Durasi (Bulan) <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('jumlah_bulan') is-invalid @enderror"
                                    id="jumlah_bulan" name="jumlah_bulan" min="1" value="{{ old('jumlah_bulan') }}">
                                @error('jumlah_bulan')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('kerja_saat_ini') is-invalid @enderror"
                                    type="checkbox" id="kerja_saat_ini" name="kerja_saat_ini" value="1" {{
                                    old('kerja_saat_ini') ? 'checked' : '' }}>
                                <label class="form-check-label" for="kerja_saat_ini">
                                    Masih Bekerja di Sini
                                </label>
                                @error('kerja_saat_ini')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pengalaman-kerja.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection