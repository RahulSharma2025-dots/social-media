<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\LiveSession;
use App\Models\OneOnOneSession;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    public function show()
    {
        $user = auth()->user();
        $posts = Post::where('user_id', $user->id)
            ->with(['user', 'likes', 'comments'])
            ->latest()
            ->paginate(10);

        $isFollowing = false;
        if (auth()->check() && $user->id !== auth()->id()) {
            $isFollowing = auth()->user()->following()->where('following_id', $user->id)->exists();
        }

        $upcomingSessions = [];
        $sessions = [];
        if ($user->user_type === 'influencer') {
            $upcomingSessions = LiveSession::where('user_id', $user->id)
                ->where('scheduled_at', '>', now())
                ->latest()
                ->take(5)
                ->get();

            $sessions = OneOnOneSession::where('influencer_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('profile.show', compact('user', 'posts', 'isFollowing', 'upcomingSessions', 'sessions'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete old profile image if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Store new profile image
            $path = $request->file('profile_picture')->store('profile-images', 'public');
            $validated['profile_picture'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }

    public function settings()
    {
        $user = auth()->user();
        return view('settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'notification_settings' => 'array'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('settings')->with('success', 'Settings updated successfully');
    }
} 