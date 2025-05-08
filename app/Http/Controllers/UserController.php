<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function explore()
    {
        try {
            // Get trending posts
            $trendingPosts = Post::with(['user', 'media'])
                ->withCount(['likes', 'comments'])
                ->orderBy('likes_count', 'desc')
                ->take(10)
                ->get();

            return view('explore', compact('trendingPosts'));
        } catch (\Exception $e) {
            \Log::error('Explore page error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load explore page. Please try again.');
        }
    }

    public function follow(User $user)
    {
        try {
            // dd($user->id);
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot follow yourself.');
            }

            auth()->user()->following()->attach($user->id);
            return back()->with('success', 'You are now following ' . $user->name);
        } catch (\Exception $e) {
            \Log::error('Follow action failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to follow user. Please try again.');
        }
    }

    public function unfollow(User $user)
    {
        try {
            $authUser = auth()->user();
            $authUser->following()->detach($user->id);

            return back()->with('success', 'You have unfollowed ' . $user->name);
        } catch (\Exception $e) {
            Log::error('Unfollow action failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to unfollow user. Please try again.');
        }
    }
}
