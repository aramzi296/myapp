<!-- resources/views/files/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar File</span>
                    <a href="{{ route('files.create') }}" class="btn btn-primary btn-sm">Upload File Baru</a>
                </div>

                <div class="card-body">
                    {{-- @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif --}}

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama File</th>
                                    <th>Tipe</th>
                                    <th>Deskripsi</th>
                                    <th>Tags</th>
                                    <th>Tanggal Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fileEntry as $index => $file)
                                @php
                                $media = $file->getFirstMedia('ramzi');
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $media ? $media->file_name : 'Tidak ada file' }}</td>
                                    <td>{{ $media ? $media->mime_type : '-' }}</td>
                                    <td>{{ $file->description ?? '-' }}</td>
                                    <td>
                                        @foreach($file->tags as $tag)
                                        <span class="badge bg-info">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $file->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('files.show', $file) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                            <a href="{{ route('files.download', $file) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                            <form action="{{ route('files.destroy', $file) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?');"
                                                class="d-inline">
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
                                    <td colspan="7" class="text-center">Tidak ada file</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection