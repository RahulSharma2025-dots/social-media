@extends('layouts.main')

@section('title', 'Messages')

@section('main-content')
<div class="d-flex" style="height:100vh;">
    <!-- Left Sidebar -->
  

    <aside class="message-sidebar d-flex flex-column" style="min-width: 320px; max-width: 350px; border-left: 1px solid #e5e7eb; background: #fff;">
        <div class="message-sidebar-header">
            <h6 class="mb-3">Active Now</h6>
        </div>
        <div class="message-sidebar-content flex-grow-1 overflow-auto">
            <div class="message-list">
                <div class="d-flex align-items-center mb-3">
                    <div class="position-relative">
                        <img src="{{ asset('images/woman.jpg') }}" class="message-avatar" alt="Sarah Johnson">
                        <span class="online-status" style="bottom: 4px; right: 4px;"></span>
                    </div>
                    <div class="ms-3">
                        <div class="fw-bold">Sarah Johnson</div>
                        <div class="text-success small">Online</div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="position-relative">
                        <img src="{{ asset('images/man.jpg') }}" class="message-avatar" alt="John Smith">
                        <span class="online-status" style="bottom: 4px; right: 4px;"></span>
                    </div>
                    <div class="ms-3">
                        <div class="fw-bold">John Smith</div>
                        <div class="text-success small">Online</div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="position-relative">
                        <img src="{{ asset('images/man.jpg') }}" class="message-avatar" alt="Mike Wilson">
                        <span class="online-status" style="bottom: 4px; right: 4px;"></span>
                    </div>
                    <div class="ms-3">
                        <div class="fw-bold">Mike Wilson</div>
                        <div class="text-success small">Online</div>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Chat Area -->
    <main class="message-container flex-grow-1 d-flex flex-column">
        <header class="message-chat-header">
            <img src="{{ asset('images/woman.jpg') }}" alt="Sarah Johnson" class="message-avatar">
            <div>
                <h5 class="mb-0">Sarah Johnson</h5>
                <span class="message-status online">Online</span>
            </div>
            <div class="message-actions ms-auto">
                <button class="message-action-btn" title="Voice Call"><i class="fas fa-phone"></i></button>
                <button class="message-action-btn" title="Video Call"><i class="fas fa-video"></i></button>
                <button class="message-action-btn" title="More Options"><i class="fas fa-ellipsis-v"></i></button>
            </div>
        </header>
        <section class="message-chat-body flex-grow-1">
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
        </section>
        <footer class="message-chat-footer">
            <form autocomplete="off">
                <div class="message-input-group">
                    <button type="button" class="message-action-btn" title="Add Emoji"><i class="far fa-smile"></i></button>
                    <input type="text" class="message-input" placeholder="Type your message..." required>
                    <button type="button" class="message-action-btn" title="Attach File"><i class="fas fa-paperclip"></i></button>
                    <button type="submit" class="message-send-btn" title="Send Message"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </footer>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Auto-scroll to bottom of messages
    const messageContainer = document.querySelector('.message-chat-body');
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
</script>
@endsection