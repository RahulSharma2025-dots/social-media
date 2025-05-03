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
                <div class="message-list">
                    @foreach($users as $user)
                    <a class="text-decoration-none text-dark user-link" data-user-id="{{ $user->id }}">
                        <div class="d-flex align-items-center mb-3">
                            <div class="position-relative">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.png') }}" class="message-avatar" alt="{{ $user->name }}">
                                @if($user->is_online)
                                <span class="online-status" style="bottom: 4px; right: 4px;"></span>
                                @endif
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold">{{ $user->name }}</div>
                                @if($user->is_online)
                                <div class="text-success small">Online</div>
                                @else
                                <div class="text-muted small">Offline</div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                <!-- <div class="d-flex align-items-center mb-3">
                    <div class="position-relative">
                        <img src="{{ asset('images/woman.jpg') }}" class="message-avatar" alt="Sarah Johnson">
                        <span class="online-status" style="bottom: 4px; right: 4px;"></span>
                    </div>
                    <div class="ms-3">
                        <div class="fw-bold">Sarah Johnson</div>
                        <div class="text-success small">Online</div>
                    </div>
                </div> -->
                <!-- <div class="d-flex align-items-center mb-3">
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
                </div> -->
            </div>
        </div>
    </aside>

    <!-- Main Chat Area -->
    <main class="message-container flex-grow-1 d-flex flex-column">
        <header class="message-chat-header" id="chat-header">
            <div class="text-center text-muted">No conversation selected</div>
        </header>
        <section class="message-chat-body flex-grow-1" id="chat-body">
            <div class="no-conversation text-center text-muted">
                Select a user to start a conversation.
            </div>
        </section>
        <footer class="message-chat-footer" id="chat-footer" style="display: none;">
            <form autocomplete="off" id="message-form">
                <div class="message-input-group">
                    <button type="button" class="message-action-btn" title="Add Emoji"><i class="far fa-smile"></i></button>
                    <input type="text" class="message-input" id="message-input" placeholder="Type your message..." required>
                    <!-- <button type="button" class="message-action-btn" title="Attach File"><i class="fas fa-paperclip"></i></button> -->
                    <button type="submit" class="message-send-btn" title="Send Message"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </footer>
    </main>
    <!-- <main class="message-container flex-grow-1 d-flex flex-column">
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
    </main> -->
</div>
@endsection

@section('scripts')
<script>
    const messageContainer = document.querySelector('.message-chat-body');
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }


    $(document).ready(function() {
        const chatHeader = $('#chat-header');
        const chatBody = $('#chat-body');
        const chatFooter = $('#chat-footer');
        const messageForm = $('#message-form');
        const messageInput = $('#message-input');

        $('.user-link').on('click', function() {
            const userId = $(this).data('user-id');

            // Clear the chat body and show a loading indicator
            chatBody.html('<div class="text-center text-muted">Loading...</div>');
            chatFooter.hide();

            // Fetch messages via AJAX
            $.ajax({
                url: `/messages/${userId}`,
                method: 'GET',
                success: function(data) {
                    // Update chat header
                    chatHeader.html(`
                        <img src="${data.user.profile_picture ? `{{ asset('storage/') }}/${data.user.profile_picture}` : `{{ asset('images/default-avatar.png') }}`}" alt="${data.user.name}" class="message-avatar">
                        <div>
                            <h5 class="mb-0">${data.user.name}</h5>
                            <span class="message-status ${data.user.is_online ? 'online' : 'offline'}">${data.user.is_online ? 'Online' : 'Offline'}</span>
                        </div>
                    `);

                    // Update chat body
                    if (data.messages.length > 0) {
                        
                        let messagesHtml = '';
                        data.messages.forEach(function(message) {
                            const messageClass = message.sender_id === {{auth()->id()}} ? 'sent' : 'received';
                            messagesHtml += `
                            <div class="message-bubble ${messageClass}">
                                <div class="message-content">${message.message}</div>
                                <div class="message-time">${new Date(message.created_at).toLocaleTimeString()}</div>
                            </div>
                        `;
                        });
                        chatBody.html(messagesHtml);
                    } else {
                        chatBody.html('<div class="no-conversation text-center text-muted">No conversation found.</div>');
                    }

                    // Show the chat footer for sending messages
                    chatFooter.show();

                    // Scroll to the bottom of the chat
                    chatBody.scrollTop(chatBody.prop('scrollHeight'));

                    // Handle message sending
                    messageForm.off('submit').on('submit', function(e) {
                        e.preventDefault();
                        const message = messageInput.val().trim();
                        if (message) {
                            $.ajax({
                                url: `/messages/${userId}`,
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                contentType: 'application/json',
                                data: JSON.stringify({
                                    message: message
                                }),
                                success: function(newMessage) {
                                    
                                    const messageClass = 'sent';
                                    chatBody.append(`
                                    <div class="message-bubble ${messageClass}">
                                        <div class="message-content">${newMessage.data.message}</div>
                                        <div class="message-time">${new Date(newMessage.data.created_at).toLocaleTimeString()}</div>
                                    </div>
                                `);
                                    chatBody.scrollTop(chatBody.prop('scrollHeight'));
                                    messageInput.val('');
                                },
                                error: function(error) {
                                    console.error('Error sending message:', error);
                                }
                            });
                        }
                    });
                },
                error: function(error) {
                    console.error('Error fetching messages:', error);
                    chatBody.html('<div class="text-center text-danger">Failed to load messages.</div>');
                }
            });
        });

        // messageInput.on('input', function() {
        //     clearTimeout(typingTimeout);
        //     $typingIndicator.show();

        //     typingTimeout = setTimeout(function() {
        //         $typingIndicator.hide();
        //     }, 1000);
        // });


        // Listen for new messages
        // Echo.private(`chat.${messageContainer.data('user-id')}`)
        //     .listen('NewMessage', function(e) {
        //         appendMessage(e.message);
        //         // Mark message as read
        //         $.ajax({
        //             url: messageContainer.data('mark-read-url'),
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //     });
    });
</script>
@endsection