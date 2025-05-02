<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function explore()
    {
        // Get trending posts
        $trendingPosts = Post::with(['user', 'media'])
            ->withCount(['likes', 'comments'])
            ->orderBy('likes_count', 'desc')
            ->take(10)
            ->get();

        return view('explore', compact('trendingPosts', 'suggestedUsers', 'trendingTopics'));
    }

    public function follow(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        auth()->user()->following()->attach($user->id);
        return back()->with('success', 'You are now following ' . $user->name);
    }

    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user->id);
        return back()->with('success', 'You have unfollowed ' . $user->name);
    }
}
