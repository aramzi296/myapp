<!-- resources/views/files/show.blade.php -->
@extends('layouts.app')


@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail File</div>

                <div class="card-body">
                    @php
                    $media = $fileEntry->getFirstMedia('ramzi');
                    @endphp

                    <div class="mb-4">
                        <h5 class="mb-3">{{ $media ? $media->file_name : 'Tidak ada file' }}</h5>

                        @if($media)
                        <div class="mb-3">
                            @if(Str::startsWith($media->mime_type, 'image/'))
                            <img src="{{ $media->getUrl() }}" alt="{{ $media->file_name }}" class="img-fluid mb-3"
                                style="max-height: 300px;">
                            @elseif(Str::startsWith($media->mime_type, 'video/'))
                            <video controls class="w-100 mb-3" style="max-height: 300px;">
                                <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}">
                                Browser Anda tidak mendukung pemutaran video.
                            </video>
                            @elseif(Str::startsWith($media->mime_type, 'audio/'))
                            <audio controls class="w-100 mb-3">
                                <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}">
                                Browser Anda tidak mendukung pemutaran audio.
                            </audio>
                            @elseif($media->mime_type === 'application/pdf')
                            <div class="mb-3">
                                <p class="text-muted">File PDF</p>
                                <a href="{{ $media->getUrl() }}" target="_blank"
                                    class="btn btn-sm btn-outline-secondary">Buka PDF</a>
                            </div>
                            @else
                            <p class="text-muted">{{ $media->mime_type }}</p>
                            @endif
                        </div>
                        @endif
                    </div>

                    <dl class="row">
                        <dt class="col-sm-3">Tipe File</dt>
                        <dd class="col-sm-9">{{ $media ? $media->mime_type : '-' }}</dd>

                        <dt class="col-sm-3">Ukuran</dt>
                        <dd class="col-sm-9">
                            @if($media)
                            {{ number_format($media->size / 1024, 2) }} KB
                            @else
                            -
                            @endif
                        </dd>

                        <dt class="col-sm-3">Tanggal Upload</dt>
                        <dd class="col-sm-9">{{ $fileEntry->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-3">Deskripsi</dt>
                        <dd class="col-sm-9">{{ $fileEntry->description ?? '-' }}</dd>

                        <dt class="col-sm-3">Tags</dt>
                        <dd class="col-sm-9">
                            @forelse($fileEntry->tags as $tag)
                            <span class="badge bg-info">{{ $tag->name }}</span>
                            @empty
                            -
                            @endforelse
                        </dd>
                    </dl>

                    <div class="d-flex gap-2 mt-3">

                        {{-- <a href="{{ $downloadLink }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-download"></i> Download
                        </a> --}}
                        <a href="{{ route('files.download', $fileEntry) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <form action="{{ route('files.destroy', $fileEntry) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                        <a href="{{ route('files.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection