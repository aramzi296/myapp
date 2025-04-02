<?php

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
