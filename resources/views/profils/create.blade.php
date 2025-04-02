@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Profil Baru</div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('profils.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="gelar_awalan" class="form-label">Gelar Awalan</label>
                                <input type="text" class="form-control" id="gelar_awalan" name="gelar_awalan"
                                    value="{{ old('gelar_awalan') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap*</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                    value="{{ old('nama_lengkap') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="gelar_akhiran" class="form-label">Gelar Akhiran</label>
                                <input type="text" class="form-control" id="gelar_akhiran" name="gelar_akhiran"
                                    value="{{ old('gelar_akhiran') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nomor_ktp" class="form-label">Nomor KTP</label>
                            <input type="text" class="form-control" id="nomor_ktp" name="nomor_ktp"
                                value="{{ old('nomor_ktp') }}">
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat*</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                required>{{ old('alamat') }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kelurahan_id" class="form-label">Kelurahan</label>
                                <input type="text" class="form-control" id="kelurahan_id" name="kelurahan_id"
                                    value="{{ old('kelurahan_id') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="kecamatan_id" class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" id="kecamatan_id" name="kecamatan_id"
                                    value="{{ old('kecamatan_id') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kota_id" class="form-label">Kota/Kabupaten</label>
                                <input type="text" class="form-control" id="kota_id" name="kota_id"
                                    value="{{ old('kota_id') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="propinsi_id" class="form-label">Provinsi</label>
                                <input type="text" class="form-control" id="propinsi_id" name="propinsi_id"
                                    value="{{ old('propinsi_id') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin*</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="laki-laki" {{ old('jenis_kelamin')=='laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="perempuan" {{ old('jenis_kelamin')=='perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status_perkawinan" class="form-label">Status Perkawinan</label>
                                <select class="form-select" id="status_perkawinan" name="status_perkawinan">
                                    <option value="">Pilih Status</option>
                                    <option value="singel" {{ old('status_perkawinan')=='singel' ? 'selected' : '' }}>
                                        Singel</option>
                                    <option value="menikah" {{ old('status_perkawinan')=='menikah' ? 'selected' : '' }}>
                                        Menikah</option>
                                    <option value="cerai" {{ old('status_perkawinan')=='cerai' ? 'selected' : '' }}>
                                        Cerai</option>
                                    <option value="janda" {{ old('status_perkawinan')=='janda' ? 'selected' : '' }}>
                                        Janda/Duda</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                                <select class="form-select" id="pendidikan_terakhir" name="pendidikan_terakhir">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="SD" {{ old('pendidikan_terakhir')=='SD' ? 'selected' : '' }}>SD
                                    </option>
                                    <option value="SMP" {{ old('pendidikan_terakhir')=='SMP' ? 'selected' : '' }}>SMP
                                    </option>
                                    <option value="SMA" {{ old('pendidikan_terakhir')=='SMA' ? 'selected' : '' }}>SMA
                                    </option>
                                    <option value="D3" {{ old('pendidikan_terakhir')=='D3' ? 'selected' : '' }}>D3
                                    </option>
                                    <option value="S1" {{ old('pendidikan_terakhir')=='S1' ? 'selected' : '' }}>S1
                                    </option>
                                    <option value="S2" {{ old('pendidikan_terakhir')=='S2' ? 'selected' : '' }}>S2
                                    </option>
                                    <option value="S3" {{ old('pendidikan_terakhir')=='S3' ? 'selected' : '' }}>S3
                                    </option>
                                    <option value="Lainnya" {{ old('pendidikan_terakhir')=='Lainnya' ? 'selected' : ''
                                        }}>Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="pas_foto" class="form-label">Pas Foto</label>
                            <input type="file" class="form-control" id="pas_foto" name="pas_foto">
                            <div class="form-text">Format: JPG, PNG. Maksimal 2MB</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profils.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection