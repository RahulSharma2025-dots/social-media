<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with('post')
            ->latest()
            ->paginate(20);

        return view('bookmarks.index', compact('bookmarks'));
    }

    public function store(Post $post)
    {
        auth()->user()->bookmarks()->create([
            'post_id' => $post->id
        ]);

        return back()->with('success', 'Post bookmarked successfully!');
    }

    public function destroy(Post $post)
    {
        auth()->user()->bookmarks()->where('post_id', $post->id)->delete();
        return back()->with('success', 'Bookmark removed successfully!');
    }
} 