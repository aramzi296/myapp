@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    Upload File ke Cloudflare R2. Multiple file.
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
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File</label>
                            <input class="form-control" type="file" id="file" name="files[]" multiple>
                            @error('files')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Upload File</button>
                    </form>

                    <hr>

                    @foreach ($fileUploads as $fileUpload)
                    <div class="row mb-3">
                        <div class="col">
                            <div class=" d-flex justify-content-between">
                                <h4>{{ $fileUpload->title }}</h4>
                                <div><a href="{{ route('uploads.delete-title', $fileUpload->id )}}">Delete Title and
                                        Files</a></div>
                            </div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Size</th>
                                        <th>Uploaded At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    @forelse($fileUpload->getMedia('cloudflare_r2') as $media)
                                    <tr>
                                        <td style="word-wrap: break-word; max-width: 200px;">{{ $media->file_name }}
                                        </td>
                                        <td>({{ formatFileSize($media->size) }})</td>
                                        <td>{{ $media->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <div class="mb-1">
                                                <a href="{{ route('uploads.download', $media->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    Download
                                                </a>
                                                <form action="{{ route('uploads.destroy', $media->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">No files found ...</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                    @endforeach








                    {{ $fileUploads->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection