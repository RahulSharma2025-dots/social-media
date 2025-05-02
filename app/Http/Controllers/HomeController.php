<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\LiveSession;
use App\Models\Topic;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get suggested influencers (users with most followers)
        $suggestedInfluencers = User::where('user_type', 'influencer')
            ->withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->take(5)
            ->get();

        // Get all posts
        $posts = Post::with(['media', 'user', 'likes', 'comments'])
        ->latest()
        ->paginate(10);
        
        // Get live sessions
        $liveSessions = LiveSession::where('is_live', true)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get trending topics
        $trendingTopics = Topic::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get();

        // Get suggested users (excluding already followed)

        // Get stories (posts from the last 24 hours)
        $stories = Post::where('created_at', '>=', now()->subDay())
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('feed.home', compact(
            'suggestedInfluencers',
            'posts',
            'liveSessions',
            'trendingTopics',
            'stories'
        ));
    }
}
