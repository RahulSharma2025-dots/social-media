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
                <span>{{ $categoryName }}</span>
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
                    <div class="tab-pane fade show active" id="pills-one-on-one" role="tabpanel" aria-labelledby="pills-one-on-one-tab" tabindex="0">
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <h4>List</h4>
                            </div>
                            <div>
                                <button class="gradient-button" id="add-session-btn"><i class="fa-solid fa-plus"></i></button>
                                <button class="gradient-button"><i class="fa-solid fa-list-ul"></i></button>
                                <button class="gradient-button"><i class="fa-solid fa-calendar-days"></i></button>
                            </div>
                        </div>
                        <div class="card border border-secondary">
                            <div class="card-body">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Wednesday 12 March 2025
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light mb-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">VIP Private session - Your Desires, My Focus</h6>
                                                        <p class="mb-0 text-muted small">08.00 AM - 09.00 AM</p>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('icons/tron.png') }}" alt="icon">
                                                        <span class="me-2 fw-bold">50<span class="text-muted">TK</span></span>
                                                        <button class="btn btn-secondary btn-sm">Complete</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between p-3 border border-success rounded bg-light mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('images/profile.png') }}" alt="Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-1 fw-bold">William</h6>
                                                            <p class="mb-1 text-success fw-bold">Spank Me, Tease Me, Please Me</p>
                                                            <p class="mb-0 text-success small">09.00 AM – 10.00 AM</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('icons/tron-success.png') }}" alt="icon">
                                                        <span class="me-2 fw-bold text-success">30<span class="text-success">TK</span></span>
                                                        <button class="btn btn-success btn-sm">Join Now</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between p-3 border border-primary rounded bg-light mb-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-primary">Exclusive Private Experience - Just You & Me</h6>
                                                        <p class="mb-0 small text-primary">11.00 AM - 12.00 AM</p>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('icons/tron-primary.png') }}" alt="icon">
                                                        <span class="me-2 fw-bold text-primary">40<span class="text-primary">TK</span></span>
                                                        <button class="gradient-button" id="edit-session-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                                                    </div>
                                                </div>
                                                <div class="border border-primary rounded bg-light" id="edit-session-form">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                                            <h5 class="card-title fw-bold">Edit Sessions</h5>
                                                            <button class="gradient-button">
                                                                <img src="{{ asset('icons/delete.svg') }}" alt="">
                                                            </button>
                                                        </div>
                                                        <form>
                                                            <!-- Title -->
                                                            <div class="mb-3">
                                                                <label for="sessionTitle" class="form-label">Title</label>
                                                                <input type="text" class="form-control" id="sessionTitle" value="Exclusive Private Experience – Just You & Me">
                                                            </div>
                                                            <!-- Date -->
                                                            <div class="mb-3">
                                                                <label for="sessionDate" class="form-label">Date</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" id="sessionDate" value="10 March 2025">
                                                                    <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                            <!-- Start Time and End Time -->
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label for="startTime" class="form-label">Start Time</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" id="startTime" value="09.00 AM">
                                                                        <span class="input-group-text"><i class="fa-regular fa-clock"></i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="endTime" class="form-label">End Time</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" id="endTime" value="09.00 AM">
                                                                        <span class="input-group-text"><i class="fa-regular fa-clock"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Token -->
                                                            <div class="mb-3 mt-3">
                                                                <label for="token" class="form-label">Token</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><img src="{{ asset('icons/tron.png') }}" alt="icon"></span>
                                                                    <input type="text" class="form-control" id="token">
                                                                </div>
                                                            </div>
                                                            <!-- Buttons -->
                                                            <div class="d-flex justify-content-center mt-4">
                                                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2">Cancel</button>
                                                                <button type="submit" class="btn btn-primary rounded-pill" style="background: linear-gradient(to right, #4e54c8, #8f94fb);">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Tuesday 11 March 2025
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light mb-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">VIP Private session - Your Desires, My Focus</h6>
                                                        <p class="mb-0 text-muted small">08.00 AM - 09.00 AM</p>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('icons/tron.png') }}" alt="icon">
                                                        <span class="me-2 fw-bold">50<span class="text-muted">TK</span></span>
                                                        <button class="btn btn-secondary btn-sm">Complete</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between p-3 border border-success rounded bg-light mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('images/profile.png') }}" alt="Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-1 fw-bold">William</h6>
                                                            <p class="mb-1 text-success fw-bold">Spank Me, Tease Me, Please Me</p>
                                                            <p class="mb-0 text-success small">09.00 AM – 10.00 AM</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('icons/tron-success.png') }}" alt="icon">
                                                        <span class="me-2 fw-bold text-success">30<span class="text-success">TK</span></span>
                                                        <button class="btn btn-success btn-sm">Join Now</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between p-3 border border-primary rounded bg-light mb-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-primary">Exclusive Private Experience - Just You & Me</h6>
                                                        <p class="mb-0 small text-primary">11.00 AM - 12.00 AM</p>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('icons/tron-primary.png') }}" alt="icon">
                                                        <span class="me-2 fw-bold text-primary">40<span class="text-primary">TK</span></span>
                                                        <button class="gradient-button" id="edit-session-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                                                    </div>
                                                </div>
                                                <div class="border border-primary rounded bg-light" id="edit-session-form">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                                            <h5 class="card-title fw-bold">Edit Sessions</h5>
                                                            <button class="gradient-button">
                                                                <img src="{{ asset('icons/delete.svg') }}" alt="">
                                                            </button>
                                                        </div>
                                                        <form>
                                                            <!-- Title -->
                                                            <div class="mb-3">
                                                                <label for="sessionTitle" class="form-label">Title</label>
                                                                <input type="text" class="form-control" id="sessionTitle" value="Exclusive Private Experience – Just You & Me">
                                                            </div>
                                                            <!-- Date -->
                                                            <div class="mb-3">
                                                                <label for="sessionDate" class="form-label">Date</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" id="sessionDate" value="10 March 2025">
                                                                    <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                            <!-- Start Time and End Time -->
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label for="startTime" class="form-label">Start Time</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" id="startTime" value="09.00 AM">
                                                                        <span class="input-group-text"><i class="fa-regular fa-clock"></i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="endTime" class="form-label">End Time</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" id="endTime" value="09.00 AM">
                                                                        <span class="input-group-text"><i class="fa-regular fa-clock"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Token -->
                                                            <div class="mb-3 mt-3">
                                                                <label for="token" class="form-label">Token</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><img src="{{ asset('icons/tron.png') }}" alt="icon"></span>
                                                                    <input type="text" class="form-control" id="token">
                                                                </div>
                                                            </div>
                                                            <!-- Buttons -->
                                                            <div class="d-flex justify-content-center mt-4">
                                                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2">Cancel</button>
                                                                <button type="submit" class="btn btn-primary rounded-pill" style="background: linear-gradient(to right, #4e54c8, #8f94fb);">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Monday 10 March 2025
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light mb-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">VIP Private session - Your Desires, My Focus</h6>
                                                        <p class="mb-0 text-muted small">08.00 AM - 09.00 AM</p>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('icons/tron.png') }}" alt="icon">
                                                        <span class="me-2 fw-bold">50<span class="text-muted">TK</span></span>
                                                        <button class="btn btn-secondary btn-sm">Complete</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between p-3 border border-success rounded bg-light mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('images/profile.png') }}" alt="Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-1 fw-bold">William</h6>
                                                            <p class="mb-1 text-success fw-bold">Spank Me, Tease Me, Please Me</p>
                                                            <p class="mb-0 text-success small">09.00 AM – 10.00 AM</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('icons/tron-success.png') }}" alt="icon">
                                                        <span class="me-2 fw-bold text-success">30<span class="text-success">TK</span></span>
                                                        <button class="btn btn-success btn-sm">Join Now</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between p-3 border border-primary rounded bg-light mb-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-primary">Exclusive Private Experience - Just You & Me</h6>
                                                        <p class="mb-0 small text-primary">11.00 AM - 12.00 AM</p>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('icons/tron-primary.png') }}" alt="icon">
                                                        <span class="me-2 fw-bold text-primary">40<span class="text-primary">TK</span></span>
                                                        <button class="gradient-button" id="edit-session-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                                                    </div>
                                                </div>
                                                <div class="border border-primary rounded bg-light" id="edit-session-form">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                                            <h5 class="card-title fw-bold">Edit Sessions</h5>
                                                            <button class="gradient-button">
                                                                <img src="{{ asset('icons/delete.svg') }}" alt="">
                                                            </button>
                                                        </div>
                                                        <form>
                                                            <!-- Title -->
                                                            <div class="mb-3">
                                                                <label for="sessionTitle" class="form-label">Title</label>
                                                                <input type="text" class="form-control" id="sessionTitle" value="Exclusive Private Experience – Just You & Me">
                                                            </div>
                                                            <!-- Date -->
                                                            <div class="mb-3">
                                                                <label for="sessionDate" class="form-label">Date</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" id="sessionDate" value="10 March 2025">
                                                                    <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                            <!-- Start Time and End Time -->
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label for="startTime" class="form-label">Start Time</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" id="startTime" value="09.00 AM">
                                                                        <span class="input-group-text"><i class="fa-regular fa-clock"></i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="endTime" class="form-label">End Time</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" id="endTime" value="09.00 AM">
                                                                        <span class="input-group-text"><i class="fa-regular fa-clock"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Token -->
                                                            <div class="mb-3 mt-3">
                                                                <label for="token" class="form-label">Token</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><img src="{{ asset('icons/tron.png') }}" alt="icon"></span>
                                                                    <input type="text" class="form-control" id="token">
                                                                </div>
                                                            </div>
                                                            <!-- Buttons -->
                                                            <div class="d-flex justify-content-center mt-4">
                                                                <button type="button" class="btn btn-outline-secondary rounded-pill me-2">Cancel</button>
                                                                <button type="submit" class="btn btn-primary rounded-pill" style="background: linear-gradient(to right, #4e54c8, #8f94fb);">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-live-straming" role="tabpanel" aria-labelledby="pills-live-straming-tab" tabindex="0"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addSessionModal" tabindex="-1" aria-labelledby="addSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSessionModalLabel">One to One Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Date -->
                <div class="mb-3">
                    <label for="sessionDate" class="form-label">Date</label>
                    <input type="text" class="form-control" id="sessionDate" value="10 March 2025" readonly>
                </div>

                <!-- Post Details -->
                <h6 class="fw-bold mb-3">Post Details</h6>
                <div class="form-check form-switch mb-3">
                    <label class="form-check-label" for="postToggle">Would you like to post this on the feed when only two sessions remain?</label>
                    <input class="form-check-input" type="checkbox" id="postToggle">
                </div>
                <div class="mb-3">
                    <label for="postTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="postTitle" placeholder="Hurry Up">
                </div>
                <div class="mb-3">
                    <label for="postDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="postDescription" rows="3" placeholder="Hey you beautiful people, we have only 2 slots left for 30th March. Hurry up and book."></textarea>
                </div>

                <!-- Slots -->
                <h6 class="fw-bold mb-3">Slots</h6>
                <div class="mb-3">
                    <label for="slotTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="slotTitle" value="Exclusive Private Experience – Just You & Me">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="startTime" class="form-label">Start Time</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="startTime" value="09.00 AM">
                            <span class="input-group-text"><i class="fa-regular fa-clock"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="endTime" class="form-label">End Time</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="endTime" value="11.00 AM">
                            <span class="input-group-text"><i class="fa-regular fa-clock"></i></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <label for="token" class="form-label">Token</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <img src="{{ asset('icons/tron.png') }}" alt="Token Icon" style="width: 20px;">
                        </span>
                        <input type="text" class="form-control" id="token" placeholder="Enter token value">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end">
                    <button class="gradient-button me-2">
                        <img src="{{ asset('icons/delete.svg') }}" alt="">
                    </button>
                    <button class="gradient-button"><i class="fa-solid fa-plus"></i></button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" style="background: linear-gradient(to right, #4e54c8, #8f94fb);">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $("#edit-session-form").hide();
        $("#edit-session-btn").click(function() {
            $("#edit-session-form").slideToggle("slow", "swing");
        });

        $("#add-session-btn").click(function() {
            $("#addSessionModal").modal("show");
        });
    });
</script>
@endsection