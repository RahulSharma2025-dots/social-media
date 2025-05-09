<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\LiveSession;
use App\Models\OneOnOneSession;
use Illuminate\Support\Facades\Log;
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
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
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
        $posts->load('media');
        $preferenceId = auth()->user()->preferences->preference_key;
        $categoryName = collect(config('preference.categories'))->firstWhere('id', $preferenceId)['name'] ?? 'N.A.';
        return view('profile.show', compact('user', 'posts', 'isFollowing', 'upcomingSessions', 'sessions', 'followersCount', 'followingCount', 'posts', 'categoryName'));
    }

    /** 
     * Display the profile of a follower or following user.
    */
    public function showProfile($userId)
    {
        try {
            $user = User::with(['posts.media', 'followers', 'following'])->findOrFail($userId);
            return view('profile.profile_show', [
                'user' => $user,
                'posts' => $user->posts,
                'followersCount' => $user->followers->count(),
                'followingCount' => $user->following->count(),
                'bio' => $user->bio,
                'title' => $user->name . "'s Profile",
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user profile: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'User profile not found.');
        }
    }

    public function edit()
    {
        $user = auth()->user()->load('preferences');
        $availablePreferences = config('preference');
        return view('profile.edit', compact('user', 'availablePreferences'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $user = auth()->user();
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'bio' => 'nullable|string|max:1000',
                'profile_picture' => 'nullable|image|max:2048',
                'preferences' => 'nullable',
            ]);
            // dd($validated);
            if ($request->hasFile('profile_picture')) {
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }

                $path = $request->file('profile_picture')->store('profile-images', 'public');
                $validated['profile_picture'] = $path;
            }

            $user->update($validated);

            if (isset($validated['preferences'])) {
                $user->preferences()->delete();
                // foreach ($validated['preferences'] as $preference) {
                $user->preferences()->create(['preference_key' => $validated['preferences']]);
                // }
            }

            return redirect()->route('profile')->with('success', 'Profile updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while updating the profile.');
        }
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

    public function followersAndFollowing(User $user, $type)
    {
        try {
            if ($type === 'followers') {
                $users = $user->followers()->paginate(10);
                $title = "{$user->name}'s Followers";
            } elseif ($type === 'following') {
                $users = $user->following()->paginate(10);
                $title = "{$user->name}'s Following";
            } else {
                abort(404);
            }

            return view('profile.followers_and_following', compact('user', 'users', 'title', 'type'));
        } catch (\Exception $e) {
            Log::error('Error fetching followers or following: ' . $e->getMessage());

            return redirect()->route('home')->with('error', 'Unable to fetch followers or following.');
        }
    }
}
