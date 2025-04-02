<?php
// LARAVEL 11 ARTICLE SYSTEM
// =========================
// Menggunakan:
// - Laravel 11
// - Bootstrap 5.3
// - Spatie Media Library
// - Spatie Laravel Tags

// 1. INSTALASI DEPENDENCIES
// composer require spatie/laravel-medialibrary
// composer require spatie/laravel-tags
// composer require tinymce/tinymce

// 2. KONFIGURASI MEDIA LIBRARY
// php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
// php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"

// 3. KONFIGURASI TAGS
// php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-migrations"
// php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-config"

// 4. MIGRATE DATABASE
// php artisan migrate

// ==============================
// MODELS
// ==============================

// app/Models/Article.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Illuminate\Support\Str;

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTags;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'author_id',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($article) {
            if (!$article->slug) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 300, 200)
            ->nonQueued();
            
        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 600, 400)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function isLikedByUser($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
}

// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}

// app/Models/Comment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
        'content',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/Like.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// ==============================
// MIGRATIONS
// ==============================

// database/migrations/xxxx_xx_xx_create_articles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->foreignId('author_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

// database/migrations/xxxx_xx_xx_create_categories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

// database/migrations/xxxx_xx_xx_create_article_category_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_category');
    }
};

// database/migrations/xxxx_xx_xx_create_comments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

// database/migrations/xxxx_xx_xx_create_likes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            
            // Memastikan setiap user hanya bisa like sebuah article satu kali
            $table->unique(['article_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};

// ==============================
// CONTROLLERS
// ==============================

// app/Http/Controllers/ArticleController.php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['author', 'categories', 'tags'])->latest()->paginate(10);
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $article = new Article([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'author_id' => Auth::id(),
        ]);

        $article->save();

        // Attach categories
        $article->categories()->attach($request->categories);

        // Attach tags
        if ($request->filled('tags')) {
            $tagNames = explode(',', $request->tags);
            $tagNames = array_map('trim', $tagNames);
            $article->attachTags($tagNames);
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $article->addMedia($image)
                    ->toMediaCollection('images');
            }
        }

        return redirect()->route('articles.index')
            ->with('success', 'Article berhasil dibuat.');
    }

    public function show(Article $article)
    {
        $article->load(['author', 'categories', 'tags', 'comments.user']);
        $userLiked = $article->isLikedByUser(Auth::id());
        $likesCount = $article->likes()->count();
        
        return view('articles.show', compact('article', 'userLiked', 'likesCount'));
    }

    public function edit(Article $article)
    {
        // Authorization check
        if (Auth::id() !== $article->author_id) {
            return redirect()->route('articles.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit artikel ini.');
        }
        
        $categories = Category::all();
        $tags = Tag::all();
        $articleTags = $article->tags->pluck('name')->implode(', ');
        $selectedCategories = $article->categories->pluck('id')->toArray();
        $media = $article->getMedia('images');
        
        return view('articles.edit', compact('article', 'categories', 'tags', 'articleTags', 'selectedCategories', 'media'));
    }

    public function update(Request $request, Article $article)
    {
        // Authorization check
        if (Auth::id() !== $article->author_id) {
            return redirect()->route('articles.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit artikel ini.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
        ]);

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
        ]);

        // Sync categories
        $article->categories()->sync($request->categories);

        // Sync tags
        if ($request->filled('tags')) {
            $tagNames = explode(',', $request->tags);
            $tagNames = array_map('trim', $tagNames);
            $article->syncTagsWithType($tagNames);
        } else {
            $article->syncTagsWithType([]);
        }

        // Delete selected images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $mediaId) {
                $media = $article->getMedia('images')->where('id', $mediaId)->first();
                if ($media) {
                    $media->delete();
                }
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $article->addMedia($image)
                    ->toMediaCollection('images');
            }
        }

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        // Authorization check
        if (Auth::id() !== $article->author_id) {
            return redirect()->route('articles.index')
                ->with('error', 'Anda tidak memiliki izin untuk menghapus artikel ini.');
        }
        
        // Delete all media
        $article->clearMediaCollection('images');
        
        // Delete article (this will cascade delete likes, comments, etc)
        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article berhasil dihapus.');
    }
}

// app/Http/Controllers/CategoryController.php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::all();
        return view('categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::where('id', '!=', $category->id)
                                    ->whereNotIn('id', $category->children->pluck('id'))
                                    ->get();
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Prevent category from becoming its own parent or descendant
        if ($request->parent_id && $request->parent_id == $category->id) {
            return back()->withErrors(['parent_id' => 'Kategori tidak dapat menjadi parent dirinya sendiri.']);
        }
        
        // Check if selected parent is not a child of this category
        if ($request->parent_id && $category->children->pluck('id')->contains($request->parent_id)) {
            return back()->withErrors(['parent_id' => 'Child kategori tidak dapat menjadi parent.']);
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Check if category has articles
        if ($category->articles->count() > 0) {
            return back()->withErrors(['delete' => 'Kategori masih memiliki artikel terkait.']);
        }
        
        // Handle child categories if any
        if ($category->children->count() > 0) {
            // Option 1: Set child categories to null parent
            foreach ($category->children as $child) {
                $child->update(['parent_id' => null]);
            }
            
            // Option 2: Move child categories to parent of this category
            /*
            foreach ($category->children as $child) {
                $child->update(['parent_id' => $category->parent_id]);
            }
            */
        }
        
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category berhasil dihapus.');
    }
}

// app/Http/Controllers/CommentController.php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = new Comment([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'article_id' => $article->id,
        ]);
        
        $comment->save();

        return redirect()->route('articles.show', $article)
            ->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function edit(Comment $comment)
    {
        // Authorization check
        if (Auth::id() !== $comment->user_id) {
            return redirect()->route('articles.show', $comment->article_id)
                ->with('error', 'Anda tidak memiliki izin untuk mengedit komentar ini.');
        }
        
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        // Authorization check
        if (Auth::id() !== $comment->user_id) {
            return redirect()->route('articles.show', $comment->article_id)
                ->with('error', 'Anda tidak memiliki izin untuk mengedit komentar ini.');
        }
        
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return redirect()->route('articles.show', $comment->article_id)
            ->with('success', 'Komentar berhasil diperbarui.');
    }

    public function destroy(Comment $comment)
    {
        // Authorization check
        if (Auth::id() !== $comment->user_id) {
            return redirect()->route('articles.show', $comment->article_id)
                ->with('error', 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }
        
        $articleId = $comment->article_id;
        $comment->delete();

        return redirect()->route('articles.show', $articleId)
            ->with('success', 'Komentar berhasil dihapus.');
    }
}

// app/Http/Controllers/LikeController.php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Article $article)
    {
        $userId = Auth::id();
        $existing = Like::where('article_id', $article->id)
                        ->where('user_id', $userId)
                        ->first();
                        
        if ($existing) {
            // Unlike
            $existing->delete();
            $action = 'unliked';
        } else {
            // Like
            Like::create([
                'article_id' => $article->id,
                'user_id' => $userId,
            ]);
            $action = 'liked';
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'likes_count' => $article->likes()->count(),
                'action' => $action
            ]);
        }
        
        return redirect()->back()
            ->with('success', 'Artikel berhasil ' . ($action == 'liked' ? 'disukai' : 'batal disukai'));
    }
}

// ==============================
// ROUTES
// ==============================

// routes/web.php
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Articles
    Route::resource('articles', ArticleController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Comments
    Route::post('articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Likes
    Route::post('articles/{article}/like', [LikeController::class, 'toggle'])->name('articles.like');
});

// ==============================
// VIEWS
// ==============================

// resources/views/articles/index.blade.php
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
                    @if($article->getFirstMedia('images'))
                        <img src="{{ $article->getFirstMedia('images')->getUrl('medium') }}" class="card-img-top" alt="{{ $article->title }}">
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
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
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

// resources/views/articles/create.blade.php
@extends('layouts.app')

@section('styles')
<!-- TinyMCE -->
<script src="{{ asset('node_modules/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Create New Article</h1>
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

    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="10">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="categories" class="form-label">Categories</label>
                <select class="form-select @error('categories') is-invalid @enderror" id="categories" name="categories[]" multiple required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
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
                <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags') }}">
                @error('tags')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="images" class="form-label">Images</label>
                <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Save Article</button>
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>