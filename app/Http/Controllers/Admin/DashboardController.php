<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $influencers = User::where('user_type', 'influencer')
            ->where('is_verified', false)
            ->count();

        $reportedUsers = User::where('reports_count', '>', 0)
            ->count();

        return view('admin.dashboard', compact('users', 'influencers', 'reportedUsers'));
    }

} 