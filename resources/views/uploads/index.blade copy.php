<!-- ini adalah index untuk upload single file -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    Upload File ke Cloudflare R2
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul File</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Masukkan judul file">
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File</label>
                            <input class="form-control" type="file" id="file" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload File</button>
                    </form>

                    <hr>

                    <h5>Daftar File</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                            <tr>
                                <td>{{ $file->title }}</td>
                                <td>
                                    @if($file->hasMedia('cloudflare_r2'))
                                    <a href="{{ $file->getFirstMediaUrl('cloudflare_r2') }}" target="_blank">
                                        {{ $file->getFirstMedia('cloudflare_r2')->file_name }}
                                    </a>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('uploads.destroy', $file->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus file?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $files->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection