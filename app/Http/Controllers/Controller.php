<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getSuggestedUsers()
    {
        $suggestedUsers = User::where('id', '!=', auth()->id())
            ->whereNotIn('id', auth()->user()->following()->pluck('following_id'))
            ->inRandomOrder()
            ->take(5)
            ->get();
        return $suggestedUsers;
    }

    public function getTrendingTopics()
    {
        $trendingTopics = Topic::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();
        return $trendingTopics;
    }
}
