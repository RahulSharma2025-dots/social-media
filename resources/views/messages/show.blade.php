@extends('layouts.app')

@section('title', 'Conversation with ' . $user->name)

@section('styles')
<style>
    :root {
        --gradient-primary: linear-gradient(135deg, #6366f1, #8b5cf6);
        --gradient-secondary: linear-gradient(135deg, #3b82f6, #2dd4bf);
        --gradient-hover: linear-gradient(135deg, #4f46e5, #7c3aed);
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .message-container {
        height: 500px;
        overflow-y: auto;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 20px;
        margin: 1rem;
    }

    .message {
        margin-bottom: 1.5rem;
        animation: fadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .message-content {
        max-width: 70%;
        word-wrap: break-word;
        padding: 1rem 1.5rem;
        border-radius: 20px;
        position: relative;
    }

    .message.sent .message-content {
        background: var(--gradient-primary);
        color: white;
        border-radius: 20px 20px 0 20px;
        box-shadow: var(--shadow-md);
    }

    .message.received .message-content {
        background: white;
        border-radius: 20px 20px 20px 0;
        box-shadow: var(--shadow-md);
    }

    .message-time {
        font-size: 0.75rem;
        opacity: 0.8;
        margin-top: 0.5rem;
    }

    .message.sent .message-time {
        color: rgba(255, 255, 255, 0.9);
    }

    .message.received .message-time {
        color: #6b7280;
    }

    .card {
        border: none;
        box-shadow: var(--shadow-lg);
        border-radius: 20px;
        background: #f8fafc;
    }

    .card-header {
        background: var(--gradient-primary);
        color: white;
        border-radius: 20px 20px 0 0 !important;
        padding: 1.5rem;
    }

    .message-input {
        border: none;
        border-radius: 20px;
        padding: 1rem 1.5rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        background: white;
    }

    .message-input:focus {
        box-shadow: var(--shadow-md);
        outline: none;
    }

    .send-button {
        background: var(--gradient-primary);
        border: none;
        border-radius: 20px;
        padding: 1rem 1.5rem;
        color: white;
        transition: all 0.3s ease;
    }

    .send-button:hover {
        background: var(--gradient-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .user-status {
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
    }

    .typing-indicator {
        font-size: 0.8rem;
        color: #6b7280;
        font-style: italic;
        padding: 0.5rem 1rem;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        margin: 0.5rem 0;
    }

    .dropdown-menu {
        border: none;
        box-shadow: var(--shadow-lg);
        border-radius: 12px;
        padding: 0.5rem;
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background: #f8fafc;
        transform: translateX(5px);
    }

    .dropdown-item i {
        width: 20px;
        color: #6366f1;
    }

    .user-avatar {
        border: 3px solid white;
        box-shadow: var(--shadow-md);
    }

    .online-status {
        width: 12px;
        height: 12px;
        background: #10b981;
        border: 2px solid white;
        border-radius: 50%;
        position: absolute;
        bottom: 0;
        right: 0;
        box-shadow: var(--shadow-sm);
    }

    .message-actions {
        position: absolute;
        right: -40px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .message:hover .message-actions {
        opacity: 1;
        right: -30px;
    }

    .message-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: white;
        border: none;
        box-shadow: var(--shadow-sm);
        color: #6366f1;
        transition: all 0.3s ease;
    }

    .message-action-btn:hover {
        background: #6366f1;
        color: white;
        transform: scale(1.1);
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('profile', $user) }}" class="text-decoration-none text-white">
                                <div class="position-relative">
                                    <img src="{{ $user->profile_picture ?? asset('images/default-avatar.png') }}"
                                        class="user-avatar rounded-circle me-3" width="40" height="40" alt="{{ $user->name }}">
                                    @if($user->is_online)
                                    <span class="online-status"></span>
                                    @endif
                                </div>
                            </a>
                            <div>
                                <h4 class="mb-0">{{ $user->name }}</h4>
                                <span class="user-status">
                                    {{ $user->is_online ? 'Online' : 'Last seen ' . $user->last_seen_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i>View Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-ban"></i>Block User</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i>Clear Chat</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="message-container" id="messageContainer">
                        <div class="message-bubble received">
                            <div class="message-content">
                                Hi Emma! I really enjoyed your latest tech review video. I have some questions about the smartphone you featured.
                            </div>
                            <div class="message-time">10:30 AM</div>
                        </div>
                        <div class="message-bubble sent">
                            <div class="message-content">
                                Thank you, Sarah! I'm glad you found it helpful. What would you like to know?
                            </div>
                            <div class="message-time">10:32 AM</div>
                        </div>
                        <div class="message-bubble received">
                            <div class="message-content">
                                Could you tell me more about the camera features? I'm particularly interested in night mode photography.
                            </div>
                            <div class="message-time">10:33 AM</div>
                        </div>
                        <div class="message-bubble sent">
                            <div class="message-content">
                                Of course! The night mode is exceptional. Would you like to schedule a one-on-one session where I can show you all the camera features in detail?
                            </div>
                            <div class="message-time">10:35 AM</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <form action="{{ route('messages.store', $user) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <div class="flex-grow-1">
                            <input type="text" name="message" class="form-control message-input"
                                placeholder="Type your message..." required>
                        </div>
                        <button type="submit" class="send-button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Auto-scroll to bottom of messages
    const messageContainer = document.getElementById('messageContainer');
    messageContainer.scrollTop = messageContainer.scrollHeight;

    // Add typing indicator
    const messageInput = document.querySelector('.message-input');
    let typingTimeout;

    messageInput.addEventListener('focus', () => {
        // Add typing indicator logic here
    });

    messageInput.addEventListener('input', () => {
        clearTimeout(typingTimeout);
        // Add typing indicator
        typingTimeout = setTimeout(() => {
            // Remove typing indicator
        }, 1000);
    });
</script>
@endsection
@endsection