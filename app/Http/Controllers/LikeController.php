<?php

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
