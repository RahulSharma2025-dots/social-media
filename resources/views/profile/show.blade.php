@extends('layouts.main')

@section('title', 'My Profile')

@section('main-content')
<div class="container py-4" style="max-width: 900px;">
    <!-- Profile Header -->
    <div class="card mb-4 shadow-sm" style="border-radius: 18px; overflow: hidden;">
        <div style="background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%); height: 110px; position: relative;">
            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png') }}" alt="{{ Auth::user()->name }}" class="rounded-circle border border-3 border-white" style="width: 90px; height: 90px; object-fit: cover; position: absolute; left: 32px; bottom: -45px; box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary position-absolute" style="right: 32px; bottom: 16px; z-index: 2;">
                <i class="fas fa-edit me-1"></i> Edit Profile
            </a>
        </div>
        <div class="card-body d-flex justify-content-between pt-5 pb-3 ps-5">
            <div>
                <h4 class="mb-0">{{ Auth::user()->name ?? 'N/A' }}</h4>
                <div class="text-muted mb-1" style="font-size: 1.05em;">{{ '@'.Auth::user()->username ?? 'N/A' }}</div>
            </div>
            <div class="d-flex">
                <a href="{{ route('profile.followers_and_following', ['user' => $user->id, 'type' => 'followers']) }}" class="text-muted mb-1" style="font-size: 1.05em;">
                    {{ $followersCount }} <span class="text-muted">Follower{{ $followersCount == 1 ? '' : 's' }}</span>
                </a>
                <a href="{{ route('profile.followers_and_following', ['user' => $user->id, 'type' => 'following']) }}" class="text-muted mb-1 ms-2" style="font-size: 1.05em;">
                    {{ $followingCount }} <span class="text-muted">Following</span>
                </a>
            </div>
        </div>
    </div>

    <!-- About Me -->
    <div class="card mb-3 shadow-sm" style="border-radius: 14px;">
        <div class="card-body">
            <h6 class="fw-bold mb-2">About Me</h6>
            <div class="text-muted">
                {{ Auth::user()->bio ?? 'N/A' }}
            </div>
        </div>
    </div>

    <!-- post, media, sessions -->
    <div>
        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-post-tab" data-bs-toggle="pill" data-bs-target="#pills-post" type="button" role="tab" aria-controls="pills-post" aria-selected="true">Posts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-media-tab" data-bs-toggle="pill" data-bs-target="#pills-media" type="button" role="tab" aria-controls="pills-media" aria-selected="false">Media</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-session-tab" data-bs-toggle="pill" data-bs-target="#pills-session" type="button" role="tab" aria-controls="pills-session" aria-selected="false">Sessions</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-post" role="tabpanel" aria-labelledby="pills-post-tab" tabindex="0">
                @foreach($posts as $post)
                <div class="post-card">
                    <div class="post-header">
                        <img src="{{ $post->user->profile_picture ? asset('storage/'.$post->user->profile_picture) : asset('images/default-avatar.png') }}" alt="{{ $post->user->name }}" class="post-avatar">
                        <div class="user-info">
                            <a href="{{ auth()->id() === $post->user->id ? route('profile') : route('profile.show', $post->user) }}" class="username">{{ $post->user->name }}</a>
                            <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <div class="post-content">
                        <p>{{ $post->content }}</p>
                        @if($post->media->count() > 0)
                        <div class="post-media-slider" id="slider-{{ $post->id }}">
                            <div class="slider-container">
                                <div class="slider-track">
                                    @foreach($post->media as $media)
                                    <div class="slider-item">
                                        @if($media->media_type === 'image')
                                        <img src="{{ asset('storage/'.$media->media_path) }}" alt="{{ $post->user->name }}" class="post-image">
                                        @elseif($media->media_type === 'video')
                                        <video controls class="post-video">
                                            <source src="{{ asset('storage/'.$media->media_path) }}" type="video/mp4">
                                        </video>
                                        @endif
                                        <div class="media-type-indicator">
                                            <i class="fas fa-{{ $media->media_type === 'image' ? 'image' : 'video' }}"></i>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @if($post->media->count() > 1)
                            <button class="slider-nav slider-prev" onclick="prevSlide({{ $post->id }})">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="slider-nav slider-next" onclick="nextSlide({{ $post->id }})">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            <div class="slider-dots">
                                @for($i = 0; $i < $post->media->count(); $i++)
                                    <div class="slider-dot {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $post->id }}, {{ $i }})"></div>
                                    @endfor
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="post-actions">
                        <a href="{{ route('posts.like', $post->id) }}" class="action-btn like-btn {{ $post->likes->contains('user_id', auth()->id()) ? 'liked' : '' }}" data-post-id="{{ $post->id }}">
                            <i class="fas fa-heart"></i>
                            <span>{{ $post->likes->count() }}</span>
                        </a>
                        <a href="#" class="action-btn comment-btn" data-post-id="{{ $post->id }}">
                            <i class="fas fa-comment"></i>
                            <span>{{ $post->comments->count() }}</span>
                        </a>
                        <a href="#" class="action-btn share-btn" data-post-id="{{ $post->id }}">
                            <i class="fas fa-share"></i>
                        </a>
                        <a href="#" class="action-btn bookmark-btn" data-post-id="{{ $post->id }}">
                            <i class="fas fa-bookmark"></i>
                        </a>
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section" id="comments-{{ $post->id }}" style="display: none;">
                        <div class="comments-list">
                            @foreach($post->comments->whereNull('parent_id') as $comment)
                            <div class="comment">
                                <img src="{{ $comment->user->profile_picture ? asset('storage/'.$comment->user->profile_picture) : asset('images/default-avatar.png') }}"
                                    alt="{{ $comment->user->name }}" class="comment-avatar">
                                <div class="comment-content">
                                    <div class="comment-header">
                                        <a href="{{ route('profile', $comment->user) }}" class="comment-username">{{ $comment->user->name }}</a>
                                        <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="comment-text">{{ $comment->content }}</p>

                                    <!-- Reply Button -->
                                    <button class="btn btn-link reply-btn" data-comment-id="{{ $comment->id }}">Reply</button>

                                    <!-- Reply Input Field -->
                                    <div class="reply-form" id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 0.5rem;">
                                        <form action="{{ route('comments.reply', $comment->id) }}" method="POST">
                                            @csrf
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Write a reply..." name="reply">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Replies Section -->
                                    @if($comment->replies->count() > 0)
                                    <div class="replies-section">
                                        @foreach($comment->replies()->orderBy('created_at', 'asc')->get() as $reply)
                                        <div class="comment reply">
                                            <img src="{{ $reply->user->profile_picture ? asset('storage/'.$reply->user->profile_picture) : asset('images/default-avatar.png') }}"
                                                alt="{{ $reply->user->name }}" class="comment-avatar">
                                            <div class="comment-content">
                                                <div class="comment-header">
                                                    <a href="{{ route('profile', $reply->user) }}" class="comment-username">{{ $reply->user->name }}</a>
                                                    <span class="comment-time">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="comment-text">{{ $reply->content }}</p>

                                                <!-- Reply Button for Replies -->
                                                <button class="btn btn-link reply-btn" data-comment-id="{{ $reply->id }}">Reply</button>

                                                <!-- Reply Input Field for Replies -->
                                                <div class="reply-form" id="reply-form-{{ $reply->id }}" style="display: none; margin-top: 0.5rem;">
                                                    <form action="{{ route('comments.reply', $reply->id) }}" method="POST">
                                                        @csrf
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="Write a reply..." name="reply">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- Nested Replies -->
                                                @if($reply->replies->count() > 0)
                                                <div class="replies-section">
                                                    @foreach($reply->replies()->orderBy('created_at', 'asc')->get() as $nestedReply)
                                                    <div class="comment reply">
                                                        <img src="{{ $nestedReply->user->profile_picture ? asset('storage/'.$nestedReply->user->profile_picture) : asset('images/default-avatar.png') }}"
                                                            alt="{{ $nestedReply->user->name }}" class="comment-avatar">
                                                        <div class="comment-content">
                                                            <div class="comment-header">
                                                                <a href="{{ route('profile', $nestedReply->user) }}" class="comment-username">{{ $nestedReply->user->name }}</a>
                                                                <span class="comment-time">{{ $nestedReply->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <p class="comment-text">{{ $nestedReply->content }}</p>

                                                            <!-- Reply Button for Nested Replies -->
                                                            <button class="btn btn-link reply-btn" data-comment-id="{{ $nestedReply->id }}">Reply</button>

                                                            <!-- Reply Input Field for Nested Replies -->
                                                            <div class="reply-form" id="reply-form-{{ $nestedReply->id }}" style="display: none; margin-top: 0.5rem;">
                                                                <form action="{{ route('comments.reply', $nestedReply->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" placeholder="Write a reply..." name="reply">
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fas fa-paper-plane"></i>
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <form class="comment-form" data-post-id="{{ $post->id }}">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Write a comment..." name="comment">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="tab-pane fade" id="pills-media" role="tabpanel" aria-labelledby="pills-media-tab" tabindex="0"> 
                <h2>Media Tab</h2>
            </div>

            <div class="tab-pane fade" id="pills-session" role="tabpanel" aria-labelledby="pills-session-tab" tabindex="0">

                <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-one-on-one-tab" data-bs-toggle="pill" data-bs-target="#pills-one-on-one" type="button" role="tab" aria-controls="pills-one-on-one" aria-selected="true">One to One Sessions</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-live-straming-tab" data-bs-toggle="pill" data-bs-target="#pills-live-straming" type="button" role="tab" aria-controls="pills-live-straming" aria-selected="false">Live Streaming Sessions</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-one-on-one" role="tabpanel" aria-labelledby="pills-one-on-one-tab" tabindex="0"></div>
                    <div class="tab-pane fade" id="pills-live-straming" role="tabpanel" aria-labelledby="pills-live-straming-tab" tabindex="0"></div>
                </div>

            </div>
        </div>

    </div>
    <!-- Areas of Expertise -->
    <!-- <div class="card mb-3 shadow-sm" style="border-radius: 14px;">
        <div class="card-body d-flex flex-wrap align-items-center">
            <h6 class="fw-bold mb-0 me-3">Areas of Expertise</h6>
            <span class="badge bg-light text-dark me-2 mb-2">Tech Reviews</span>
            <span class="badge bg-light text-dark me-2 mb-2">Lifestyle</span>
            <span class="badge bg-light text-dark me-2 mb-2">Digital Marketing</span>
            <span class="badge bg-light text-dark me-2 mb-2">Photography</span>
            <button class="btn btn-link ms-auto text-primary" style="font-size: 1.1em;"><i class="fas fa-plus"></i> Add</button>
        </div>
    </div> -->

    <!-- Create New Content -->
    <!-- <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card h-100 text-center shadow-sm" style="border-radius: 14px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-pen-nib fa-2x mb-2 text-primary"></i>
                    <h6 class="fw-bold mb-1">Create Post</h6>
                    <div class="text-muted small">Share photos or videos</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center shadow-sm" style="border-radius: 14px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-broadcast-tower fa-2x mb-2 text-primary"></i>
                    <h6 class="fw-bold mb-1">Live Session</h6>
                    <div class="text-muted small">Start streaming</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center shadow-sm" style="border-radius: 14px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-user-friends fa-2x mb-2 text-primary"></i>
                    <h6 class="fw-bold mb-1">One-on-One Session</h6>
                    <div class="text-muted small">Schedule private sessions</div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Session Settings -->
    <!-- <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm" style="border-radius: 14px;">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">One-on-One Sessions</h6>
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="text-muted small">Session Price</div>
                            <div class="fw-bold">$50</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Viewer Price</div>
                            <div class="fw-bold">$15</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-muted small">Available Hours</div>
                            <div class="fw-bold">Weekly schedule</div>
                        </div>
                        <a href="#" class="btn btn-link ms-auto text-primary">Configure</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm" style="border-radius: 14px;">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Live Sessions</h6>
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="text-muted small">Session Duration</div>
                            <div class="fw-bold">30 min</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Default length</div>
                            <div class="fw-bold">30 min</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-muted small">Available Hours</div>
                            <div class="fw-bold">Custom length</div>
                        </div>
                        <a href="#" class="btn btn-link ms-auto text-primary">Configure</a>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
@endsection