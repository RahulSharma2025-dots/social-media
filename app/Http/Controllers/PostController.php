<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\PostMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000',
                'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // 10MB max per file
            ]);

            $post = Post::create([
                'user_id' => Auth::id(),
                'content' => $request->content
            ]);

            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    
                    // Determine media type
                    $mediaType = in_array($extension, ['mp4', 'mov', 'avi']) ? 'video' : 'image';
                    
                    // Generate unique filename
                    $filename = uniqid() . '.' . $extension;
                    
                    // Store the file
                    $path = $file->storeAs('posts/' . Auth::id(), $filename, 'public');

                    // Create post media record
                    PostMedia::create([
                        'post_id' => $post->id,
                        'media_type' => $mediaType,
                        'media_path' => $path
                    ]);
                }
            }

            return back()->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            \Log::error('Post creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create post. Please try again.');
        }
    }

    public function like(Post $post)
    {
        try {
            $existingLike = Like::where('user_id', Auth::id())
                ->where('post_id', $post->id)
                ->first();

            if ($existingLike) {
                // Unlike the post
                $existingLike->delete();
            } else {
                // Like the post
                Like::create([
                    'user_id' => Auth::id(),
                    'post_id' => $post->id
                ]);
            }

            return back();
        } catch (\Exception $e) {
            \Log::error('Like/Unlike failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to process like/unlike action.');
        }
    }

    public function comment(Request $request, Post $post)
    {
        try {
            $request->validate([
                'comment' => 'required|string|max:500',
            ]);

            $comment = Comment::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'content' => $request->comment,
            ]);

            return response()->json([
                'user' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'profile_picture' => Auth::user()->profile_picture
                ],
                'comment' => $comment
            ]);
        } catch (\Exception $e) {
            \Log::error('Comment creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to add comment'], 500);
        }
    }

    public function reply(Request $request, Comment $comment)
    {
        try {
            $request->validate([
                'reply' => 'required|string|max:500',
            ]);

            $reply = Comment::create([
                'user_id' => Auth::id(),
                'post_id' => $comment->post_id,
                'parent_id' => $comment->id,
                'content' => $request->reply,
            ]);

            return response()->json([
                'user' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'profile_picture' => Auth::user()->profile_picture
                ],
                'reply' => $reply
            ]);
        } catch (\Exception $e) {
            \Log::error('Reply creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to add reply'], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            // Delete all media files
            foreach ($post->media as $media) {
                Storage::disk('public')->delete($media->media_path);
            }

            $post->delete();
            return back()->with('success', 'Post deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Post deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete post. Please try again.');
        }
    }
}
