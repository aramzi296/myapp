@extends('layouts.app')

@section('styles')
<!-- TinyMCE -->
<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Edit Article: {{ $article->title }}</h1>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                    value="{{ old('title', $article->title) }}" required>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                    name="description" rows="10">{{ old('description', $article->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="categories" class="form-label">Categories</label>
                <select class="form-select @error('categories') is-invalid @enderror" id="categories"
                    name="categories[]" multiple required>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories',
                        $selectedCategories)) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('categories')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="tags" class="form-label">Tags (comma separated)</label>
                <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags"
                    value="{{ old('tags', $articleTags) }}">
                @error('tags')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if($media->count() > 0)
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Current Images</label>
                <div class="row">
                    @foreach($media as $item)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="{{ $item->getUrl('thumb') }}" class="card-img-top" alt="Image">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="delete_images[]"
                                        value="{{ $item->id }}" id="delete_image_{{ $item->id }}">
                                    <label class="form-check-label" for="delete_image_{{ $item->id }}">
                                        Delete this image
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="images" class="form-label">Add New Images</label>
                <input type="file" class="form-control @error('images') is-invalid @enderror" id="images"
                    name="images[]" multiple accept="image/*">
                @error('images')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Update Article</button>
                <a href="{{ route('articles.show', $article) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    tinymce.init({
    selector: '#description',
    license_key: 'gpl',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    height: 400,
    images_upload_handler: function (blobInfo, progress) {
        return new Promise((resolve, reject) => {
            var xhr = new XMLHttpRequest();
            xhr.withCredentials = true;
            xhr.open('POST', '/upload-gambar');
            
            // Tambahkan CSRF token ke header
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var json = JSON.parse(xhr.responseText);
                    resolve(json.location);
                } else {
                    reject('HTTP Error: ' + xhr.status);
                }
            };
            
            xhr.onerror = function () {
                reject('Network Error');
            };
            
            var formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            
            xhr.send(formData);
        });
    }
});
</script>
@endsection