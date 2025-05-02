@extends('layouts.main')

@section('title', 'Home')

@section('styles')
<style>
    .like-btn.liked i {
        color: #e74c3c;
    }

    .like-btn.liked:hover i {
        color: #c0392b;
    }

    /* Comments Styles */
    .comments-section {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
    }

    .comments-list {
        margin-bottom: 1rem;
    }

    .comment {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .comment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-content {
        flex: 1;
    }

    .comment-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .comment-username {
        font-weight: 600;
        color: #2d3748;
        text-decoration: none;
    }

    .comment-time {
        font-size: 0.875rem;
        color: #718096;
    }

    .comment-text {
        margin: 0;
        color: #4a5568;
    }

    .comment.reply {
        margin-left: 2rem;
        padding-left: 1rem;
        border-left: 2px solid #e2e8f0;
    }

    .comment-form {
        margin-top: 1rem;
    }

    .comment-form .input-group {
        background: white;
        border-radius: 2rem;
        padding: 0.5rem;
    }

    .comment-form .form-control {
        border: none;
        padding: 0.5rem 1rem;
    }

    .comment-form .form-control:focus {
        box-shadow: none;
    }

    .comment-form .btn {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Media Preview Styles */
    .media-preview {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
    }

    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .preview-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 0.5rem;
        overflow: hidden;
        background: #fff;
    }

    .preview-item img,
    .preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-item .remove-media {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #dc3545;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .preview-item .remove-media:hover {
        background: #dc3545;
        color: #fff;
    }

    .preview-item .media-type {
        position: absolute;
        bottom: 0.5rem;
        left: 0.5rem;
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
    }

    .upload-status {
        font-size: 0.875rem;
    }

    .upload-status .alert {
        padding: 0.5rem 1rem;
    }
</style>
@endsection

@section('main-content')
<!-- Latest Posts Header -->
<div class="latest-posts-header">
    <h4>Latest Posts</h4>
    <div class="dropdown">
        <button class="dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-ellipsis-h"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="fas fa-fire me-2"></i>Trending</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-clock me-2"></i>Recent</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-star me-2"></i>Most Liked</a></li>
        </ul>
    </div>
</div>

<!-- Create Post Form -->
<div class="create-post-form">
    <div class="post-content">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="create-post-form">
            @csrf
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="2" placeholder="What's on your mind?"></textarea>
            </div>
            <div class="post-actions">
                <div>
                    <label class="media-upload-btn">
                        <i class="fas fa-images me-2"></i>Add Media
                        <input type="file" name="media[]" class="d-none" accept="image/*,video/*" id="media-upload" multiple>
                    </label>
                </div>
                <button type="submit" class="post-btn">Post</button>
            </div>
            <!-- Media Preview -->
            <div class="media-preview mt-3" style="display: none;">
                <div class="preview-grid" id="preview-grid">
                    <!-- Previews will be added here -->
                </div>
                <div class="upload-status mt-2">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="status-message">Ready to upload</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Posts Feed -->
@foreach($posts as $post)
<div class="post-card">
    <div class="post-header">
        <img src="{{ $post->user->profile_picture ? asset('storage/'.$post->user->profile_picture) : asset('images/default-avatar.png') }}" alt="{{ $post->user->name }}" class="post-avatar">
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

{{ $posts->links() }}
@endsection

@section('scripts')
<script>
    // Slider functionality - Global functions
    const sliders = {};

    function initSlider(postId) {
        const slider = $(`#slider-${postId}`);
        if (!slider.length) return;

        const track = slider.find('.slider-track');
        const items = slider.find('.slider-item');
        const dots = slider.find('.slider-dot');

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

        slider.track.css('transform', `translateX(-${slider.currentIndex * 100}%)`);

        // Update dots
        slider.dots.removeClass('active')
            .eq(slider.currentIndex)
            .addClass('active');
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

    $(document).ready(function() {
        // Initialize all sliders when the page loads
        $('.post-media-slider').each(function() {
            const postId = $(this).attr('id').split('-')[1];
            initSlider(postId);
        });

        // Like button functionality
        $('.like-btn').on('click', function(e) {
            e.preventDefault();
            const $button = $(this);
            const postId = $button.data('post-id');
            const likeUrl = $button.attr('href');
            const isLiked = $button.hasClass('liked');

            $.ajax({
                url: likeUrl,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const $likeCountSpan = $button.find('span');
                    const currentCount = parseInt($likeCountSpan.text());

                    if (isLiked) {
                        // Unlike
                        $likeCountSpan.text(currentCount - 1);
                        $button.removeClass('liked');
                    } else {
                        // Like
                        $likeCountSpan.text(currentCount + 1);
                        $button.addClass('liked');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        // Comment button functionality
        $('.comment-btn').on('click', function(e) {
            e.preventDefault();
            const postId = $(this).data('post-id');
            const commentsSection = $(`#comments-${postId}`);

            // Toggle comments section
            commentsSection.slideToggle();

            // Focus on comment input if showing
            if (commentsSection.is(':visible')) {
                commentsSection.find('input[name="comment"]').focus();
            }
        });

        // Comment form submission
        $('.comment-form').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            const postId = $form.data('post-id');
            const $input = $form.find('input[name="comment"]');
            const comment = $input.val().trim();

            if (!comment) return;

            $.ajax({
                url: `/posts/${postId}/comment`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    comment: comment
                },
                success: function(response) {
                    // Add new comment to the list
                    const newComment = `
                        <div class="comment">
                            <img src="${response.user.profile_picture && response.user.profile_picture !== null ? '/storage/' + response.user.profile_picture : '/images/default-avatar.png'}" 
                                 alt="${response.user.name}" class="comment-avatar">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <a href="/profile/${response.user.id}" class="comment-username">${response.user.name}</a>
                                    <span class="comment-time">Just now</span>
                                </div>
                                <p class="comment-text">${comment}</p>
                            </div>
                        </div>
                    `;
                    $form.siblings('.comments-list').append(newComment);

                    // Update comment count
                    const $commentBtn = $(`.comment-btn[data-post-id="${postId}"]`);
                    const $countSpan = $commentBtn.find('span');
                    const currentCount = parseInt($countSpan.text());
                    $countSpan.text(currentCount + 1);

                    // Clear input
                    $input.val('');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        // Share button functionality
        $('.share-btn').on('click', function(e) {
            e.preventDefault();
            const postId = $(this).data('post-id');
            // Add your share functionality here
        });

        // Bookmark button functionality
        $('.bookmark-btn').on('click', function(e) {
            e.preventDefault();
            const postId = $(this).data('post-id');
            // Add your bookmark functionality here
        });

        // Follow button functionality
        $('.follow-btn').on('click', function(e) {
            e.preventDefault();
            // Add your follow functionality here
        });

        // Media Upload Preview
        $('#media-upload').on('change', function(e) {
            const files = e.target.files;
            const maxFiles = 10;
            const $preview = $('.media-preview');
            const $previewGrid = $('#preview-grid');
            const $status = $('.upload-status');
            const $statusMessage = $('#status-message');

            // Show preview container
            $preview.show();
            $status.show();

            // Check if too many files
            if (files.length > maxFiles) {
                $statusMessage.text(`You can only upload up to ${maxFiles} files`);
                this.value = ''; // Clear the input
                return;
            }

            // Clear existing previews
            $previewGrid.empty();

            // Add new previews
            Array.from(files).forEach((file, index) => {
                const isImage = file.type.startsWith('image/');
                const isVideo = file.type.startsWith('video/');

                if (!isImage && !isVideo) {
                    $statusMessage.text('Only images and videos are allowed');
                    return;
                }

                const previewItem = $('<div>').addClass('preview-item');
                const removeBtn = $('<button>')
                    .addClass('remove-media')
                    .html('<i class="fas fa-times"></i>')
                    .on('click', function() {
                        previewItem.remove();
                        updateFileList();
                        updateStatus();
                    });

                const mediaType = $('<div>')
                    .addClass('media-type')
                    .text(isImage ? 'Image' : 'Video');

                if (isImage) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $('<img>').attr('src', e.target.result);
                        previewItem.append(img, removeBtn, mediaType);
                    };
                    reader.readAsDataURL(file);
                } else {
                    const video = $('<video>')
                        .attr('src', URL.createObjectURL(file))
                        .attr('controls', true);
                    previewItem.append(video, removeBtn, mediaType);
                }

                $previewGrid.append(previewItem);
            });

            updateStatus();
        });

        function updateStatus() {
            const fileCount = $('#preview-grid .preview-item').length;
            const $statusMessage = $('#status-message');

            if (fileCount === 0) {
                $statusMessage.text('No media selected');
            } else {
                $statusMessage.text(`${fileCount} file${fileCount > 1 ? 's' : ''} selected`);
            }
        }

        function updateFileList() {
            const $fileInput = $('#media-upload');
            const dataTransfer = new DataTransfer();

            // Get all files from the input
            const files = $fileInput[0].files;

            // Add each file to the DataTransfer object
            for (let i = 0; i < files.length; i++) {
                dataTransfer.items.add(files[i]);
            }

            // Update the input's files
            $fileInput[0].files = dataTransfer.files;
        }

        // Form Submit
        $('#create-post-form').on('submit', function() {
            const $statusMessage = $('#status-message');
            $statusMessage.html('<i class="fas fa-spinner fa-spin me-2"></i>Uploading...');
        });

        $('.reply-btn').on('click', function () {
            const commentId = $(this).data('comment-id');
            const replyForm = $(`#reply-form-${commentId}`);

            replyForm.toggle();
        });

        // Handle reply form submission
        $('.reply-form form').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            const commentId = $form.closest('.reply-form').attr('id').split('-')[2];
            const $input = $form.find('input[name="reply"]');
            const reply = $input.val().trim();

            if (!reply) return;

            $.ajax({
                url: `/comments/${commentId}/reply`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    reply: reply
                },
                success: function(response) {
                    
                    const newReply = `
                        <div class="comment reply">
                            <img src="${response.user.profile_picture && response.user.profile_picture !== null ? '/storage/' + response.user.profile_picture : '/images/default-avatar.png'}" 
                                 alt="${response.user.name}" class="comment-avatar">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <a href="/profile/${response.user.id}" class="comment-username">${response.user.name}</a>
                                    <span class="comment-time">Just now</span>
                                </div>
                                <p class="comment-text">${reply}</p>
                            </div>
                        </div>
                    `;
                    $form.closest('.comment-content').append(newReply);

                    // Clear input and hide form
                    $input.val('');
                    $form.closest('.reply-form').hide();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>
@endsection