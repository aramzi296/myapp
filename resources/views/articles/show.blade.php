@extends('layouts.app')

@section('styles')
<style>
    .like-btn {
        cursor: pointer;
    }

    .liked {
        color: red;
    }
</style>
@endsection

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ $article->title }}</h1>
            <p class="text-muted">
                By {{ $article->author->name }} |
                {{ $article->created_at->format('d M Y') }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('articles.index') }}" class="btn btn-secondary">Back to List</a>

            @if(Auth::id() === $article->author_id)
            <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                    Delete
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            @if($article->getMedia('article')->count() > 0)
            <div id="articleCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($article->getMedia('article') as $index => $media)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $media->getUrl() }}" class="d-block w-100" alt="Article Image">
                    </div>
                    @endforeach
                </div>
                @if($article->getMedia('article')->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#articleCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#articleCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                @endif
            </div>
            @endif

            <div class="mb-4">
                @if($article->categories->count())
                <div class="mb-2">
                    Categories:
                    @foreach($article->categories as $category)
                    <span class="badge bg-secondary">{{ $category->name }}</span>
                    @endforeach
                </div>
                @endif

                @if($article->tags->count())
                <div class="mb-2">
                    Tags:
                    @foreach($article->tags as $tag)
                    <span class="badge bg-info text-dark">{{ $tag->name }}</span>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="article-content mb-4">
                {!! $article->description !!}
            </div>

            <div class="d-flex align-items-center mb-4">
                <form action="{{ route('articles.like', $article) }}" method="POST" class="like-form me-2">
                    @csrf
                    <button type="submit" class="btn btn-sm like-btn {{ $userLiked ? 'liked' : '' }}">
                        <i class="bi bi-heart{{ $userLiked ? '-fill' : '' }}"></i>
                        <span class="likes-count">{{ $likesCount }}</span> Likes
                    </button>
                </form>
                <div>
                    <i class="bi bi-chat"></i> {{ $article->comments->count() }} Comments
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="row">
        <div class="col-md-12">
            <h3>Comments</h3>

            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('comments.store', $article) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">Add a Comment</label>
                            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

            @forelse($article->comments()->latest()->get() as $comment)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">{{ $comment->user->name }}</h5>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="card-text">{{ $comment->content }}</p>

                    @if(Auth::id() === $comment->user_id)
                    <div class="mt-2">
                        <a href="{{ route('comments.edit', $comment) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
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
            @empty
            <div class="alert alert-info">No comments yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
    $('.like-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                const likesCount = form.find('.likes-count');
                const likeBtn = form.find('.like-btn');
                const icon = form.find('i');
                
                likesCount.text(response.likes_count);
                
                if (response.action === 'liked') {
                    likeBtn.addClass('liked');
                    icon.removeClass('bi-heart').addClass('bi-heart-fill');
                } else {
                    likeBtn.removeClass('liked');
                    icon.removeClass('bi-heart-fill').addClass('bi-heart');
                }
            }
        });
    });
});
</script>
@endsection