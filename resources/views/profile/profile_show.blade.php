@extends('layouts.main')

@section('title', $title)

@section('main-content')
<div class="container">
    <div class="profile-header">
        <h1>{{ $user->name }}</h1>
        <p>{{ $bio }}</p>
        <div class="stats">
            <span><strong>Posts:</strong> {{ $posts->count() }}</span>
            <span><strong>Followers:</strong> {{ $followersCount }}</span>
            <span><strong>Following:</strong> {{ $followingCount }}</span>
        </div>
    </div>

    <div class="profile-posts">
        <h2>Posts</h2>
        @if ($posts->isEmpty())
            <p>No posts available.</p>
        @endif
        @foreach ($posts as $post)
            <div class="post">
                <h3>{{ $post->title }}</h3>
                <p>{{ $post->content }}</p>
                <p class="text-muted">{{ $post->created_at->diffForHumans() }}</p>
                <div class="post-media">
                    @foreach ($post->media as $media)
                        @if ($media->media_type === 'image')
                            <img src="{{ asset('storage/' . $media->media_path) }}" alt="Post Media" style="max-width: 100%;">
                        @elseif ($media->media_type === 'video')
                            <video controls style="max-width: 100%;">
                                <source src="{{ asset('storage/' . $media->media_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection