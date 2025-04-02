<?php

namespace App\Http\Controllers;

use Spatie\Tags\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
                    ->toMediaCollection('article');
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
        $media = $article->getMedia('r2');

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
                $media = $article->getMedia('article')->where('id', $mediaId)->first();
                if ($media) {
                    $media->delete();
                }
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $article->addMedia($image)
                    ->toMediaCollection('article');
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
        $article->clearMediaCollection('article');

        // Delete article (this will cascade delete likes, comments, etc)
        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article berhasil dihapus.');
    }

    public function uploadGambar(Request $request)
    {
        try {
            // Validasi
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Cek apakah file ada
            if (!$request->hasFile('file')) {
                // Log::error('No file uploaded'); // for debug
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            $file = $request->file('file');

            // // Debugging informasi file
            // Log::info('File details', [
            //     'original_name' => $file->getClientOriginalName(),
            //     'size' => $file->getSize(),
            //     'mime' => $file->getMimeType()
            // ]);

            // Upload dengan nama unik
            // $path = $file->store('public/article');
            // $path = $request->file('file')->store('articles', 'public');

            // $path = $request->file('file')->storeAs(
            //     'public/articles',
            //     uniqid() . '.' . $request->file('file')->getClientOriginalExtension(),
            //     'public'
            // );

            $namaGambar = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('article'), $namaGambar);

            return response()->json(['location' => '/article/' . $namaGambar]);

            // return response()->json([
            //     'location' => $path,
            // ]);

            // dd(url($path));

            // Generate URL yang benar
            return response()->json([
                'location' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            // // Log full error
            // Log::error('Upload error: ' . $e->getMessage());
            // Log::error($e->getTraceAsString());

            return response()->json([
                'error' => 'Upload failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
