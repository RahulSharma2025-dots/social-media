<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookmarkController extends Controller
{
    public function index()
    {
        try {
            $bookmarks = auth()->user()->bookmarks()
                ->with('post')
                ->latest()
                ->paginate(20);

            return view('bookmarks.index', compact('bookmarks'));
        } catch (\Exception $e) {
            \Log::error('Failed to load bookmarks: ' . $e->getMessage());
            return back()->with('error', 'Failed to load bookmarks. Please try again.');
        }
    }

    public function store(Post $post)
    {
        try {
            auth()->user()->bookmarks()->create([
                'post_id' => $post->id
            ]);

            return back()->with('success', 'Post bookmarked successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to bookmark post: ' . $e->getMessage());
            return back()->with('error', 'Failed to bookmark post. Please try again.');
        }
    }

    public function destroy(Post $post)
    {
        try {
            auth()->user()->bookmarks()->where('post_id', $post->id)->delete();
            return back()->with('success', 'Bookmark removed successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to remove bookmark: ' . $e->getMessage());
            return back()->with('error', 'Failed to remove bookmark. Please try again.');
        }
    }
} 