@extends('layouts.main')

@section('title', 'Explore')

@section('main-content')
<!-- Trending Posts Section -->
<div class="trending-posts mb-4">
    <h4 class="mb-3">Trending Posts</h4>
    <div class="row">
        @foreach($trendingPosts as $post)
            <div class="col-md-6 mb-4">
                <div class="post-card">
                    <div class="post-header">
                        <img src="{{ asset('storage/'.$post->user->profile_picture)??asset('images/default-avatar.png') }}" alt="{{ $post->user->name }}" class="post-avatar">
                        <div class="user-info">
                            <a href="{{ route('profile', $post->user) }}" class="username">{{ $post->user->name }}</a>
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
                        <a href="#" class="action-btn like-btn" data-post-id="{{ $post->id }}">
                            <i class="fas fa-heart"></i>
                            <span>{{ $post->likes_count }}</span>
                        </a>
                        <a href="#" class="action-btn comment-btn" data-post-id="{{ $post->id }}">
                            <i class="fas fa-comment"></i>
                            <span>{{ $post->comments_count }}</span>
                        </a>
                        <a href="#" class="action-btn share-btn" data-post-id="{{ $post->id }}">
                            <i class="fas fa-share"></i>
                        </a>
                        <a href="#" class="action-btn bookmark-btn" data-post-id="{{ $post->id }}">
                            <i class="fas fa-bookmark"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
// Include the same slider functionality as in home.blade.php
document.addEventListener('DOMContentLoaded', function() {
    // Like button functionality
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            // Add your like functionality here
        });
    });

    // Comment button functionality
    document.querySelectorAll('.comment-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            // Add your comment functionality here
        });
    });

    // Share button functionality
    document.querySelectorAll('.share-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            // Add your share functionality here
        });
    });

    // Bookmark button functionality
    document.querySelectorAll('.bookmark-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            // Add your bookmark functionality here
        });
    });
});

// Slider functionality
const sliders = {};

function initSlider(postId) {
    const slider = document.querySelector(`#slider-${postId}`);
    if (!slider) return;

    const track = slider.querySelector('.slider-track');
    const items = slider.querySelectorAll('.slider-item');
    const dots = slider.querySelectorAll('.slider-dot');
    
    sliders[postId] = {
        currentIndex: 0,
        totalItems: items.length,
        track,
        items,
        dots
    };
}

function updateSlider(postId) {
    const slider = sliders[postId];
    if (!slider) return;

    slider.track.style.transform = `translateX(-${slider.currentIndex * 100}%)`;
    
    // Update dots
    slider.dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === slider.currentIndex);
    });
}

function nextSlide(postId) {
    const slider = sliders[postId];
    if (!slider) return;

    slider.currentIndex = (slider.currentIndex + 1) % slider.totalItems;
    updateSlider(postId);
}

function prevSlide(postId) {
    const slider = sliders[postId];
    if (!slider) return;

    slider.currentIndex = (slider.currentIndex - 1 + slider.totalItems) % slider.totalItems;
    updateSlider(postId);
}

function goToSlide(postId, index) {
    const slider = sliders[postId];
    if (!slider) return;

    slider.currentIndex = index;
    updateSlider(postId);
}

// Initialize all sliders when the page loads
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.post-media-slider').forEach(slider => {
        const postId = slider.id.split('-')[1];
        initSlider(postId);
    });
});
</script>
@endsection 