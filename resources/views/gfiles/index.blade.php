<!-- resources/views/files/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">


                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar File</span>
                    <a href="{{ route('gfiles.create') }}" class="btn btn-primary btn-sm">Upload File Baru</a>
                </div>


                <div class="card-body">

                    {{-- @foreach ($fileEntry as $file) --}}
                    {{-- <h4>{{ $file->description}}</h4>
                    <div>
                        @foreach($file->tags as $tag)
                        <span class="badge bg-info">{{ $tag->name }}</span>
                        @endforeach
                    </div> --}}


                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama File</th>
                                    <th>Tipe</th>
                                    <th>Ukuran</th>
                                    <th>Aksi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>


                                @forelse ($fileEntry as $index => $media)

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $media->file_name}}</td>
                                    <td>{{ $media->file_mime_type }}</td>
                                    <td>{{$media->file_size}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('gfiles.show', $media->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                            <a href="{{ route('files.download', $media->id) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                            <form action="{{ route('gfiles.destroy', $media->id) }}" method="POST"
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
                                    <td>
                                        {{ $media->gfile->description}}
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

                    {{-- @endforeach --}}

                </div>

                <div class="card-footer">
                    {{ $fileEntry->links('pagination::bootstrap-5')}}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection