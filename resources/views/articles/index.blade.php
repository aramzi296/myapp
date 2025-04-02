@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h1>Articles</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('articles.create') }}" class="btn btn-primary">Create New Article</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        @forelse($articles as $article)
        <div class="col-md-6 mb-4">
            <div class="card">
                @if($article->getFirstMedia('r2'))
                <img src="{{ $article->getFirstMedia('r2')->getUrl('medium') }}" class="card-img-top"
                    alt="{{ $article->title }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $article->title }}</h5>
                    <p class="card-text text-muted">
                        By {{ $article->author->name }} |
                        {{ $article->created_at->format('d M Y') }}
                    </p>

                    @if($article->categories->count())
                    <div class="mb-2">
                        @foreach($article->categories as $category)
                        <span class="badge bg-secondary">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    @endif

                    @if($article->tags->count())
                    <div class="mb-2">
                        @foreach($article->tags as $tag)
                        <span class="badge bg-info text-dark">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    @endif

                    <div class="mb-3">
                        {{ Str::limit(strip_tags($article->description), 150) }}
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-primary">
                            Read More
                        </a>

                        @if(Auth::id() === $article->author_id)
                        <div>
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>
                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                No articles found.
            </div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $articles->links() }}
    </div>
</div>
@endsection