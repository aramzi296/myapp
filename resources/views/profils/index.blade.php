@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Daftar Profil</h1>
        <a href="{{ route('profils.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Profil
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat</th>
                            <th>Jenis Kelamin</th>
                            <th>Pendidikan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profils as $profil)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $profil->gelar_awalan }} {{ $profil->nama_lengkap }} {{ $profil->gelar_akhiran }}
                            </td>
                            <td>{{ Str::limit($profil->alamat, 30) }}</td>
                            <td>{{ ucfirst($profil->jenis_kelamin) }}</td>
                            <td>{{ $profil->pendidikan_terakhir }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('profils.show', $profil->slug) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('profils.edit', $profil->slug) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('profils.destroy', $profil->slug) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data profil</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $profils->links() }}
        </div>
    </div>
</div>
@endsection