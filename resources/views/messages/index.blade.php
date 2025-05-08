@extends('layouts.main')

@section('title', 'Messages')

@section('main-content')
<div class="d-flex" style="height:100vh;">
    <!-- Left Sidebar -->
    <aside class="message-sidebar d-flex flex-column" style="min-width: 320px; max-width: 350px; border-left: 1px solid #e5e7eb; background: #fff;">
        <div class="message-sidebar-header">
            <h6 class="mb-3">Messages</h6>
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
            </div>
        </div>
    </aside>

    <!-- Main Chat Area -->
    <main class="message-container flex-grow-1 d-flex flex-column">
        <header class="message-chat-header" id="chat-header">
            <div class="text-center text-muted">No conversation selected</div>
        </header>
        <section class="message-chat-body flex-grow-1" id="chat-body" data-mark-read-url="{{ route('messages.markAsRead', $user) }}" data-user-id="{{ auth()->id() }}">
            <div class="no-conversation text-center text-muted">
                Select a user to start a conversation.
            </div>
        </section>
        <div id="typing-indicator" class="text-muted small" style="display: none;">
            The user is typing...
        </div>
        <footer class="message-chat-footer" id="chat-footer" style="display: none;">
            <form autocomplete="off" id="message-form">
                <div class="message-input-group">
                    <!-- <button type="button" class="message-action-btn" title="Add Emoji"><i class="far fa-smile"></i></button> -->
                    <input type="text" class="message-input" id="message-input" placeholder="Type your message..." required>
                    <!-- <button type="button" class="message-action-btn" title="Attach File"><i class="fas fa-paperclip"></i></button> -->
                    <button type="submit" class="message-send-btn" title="Send Message"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </footer>
    </main>
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
        const authUserId = {{ auth()->id() }};
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
                            const messageClass = message.sender_id === authUserId ? 'sent' : 'received';
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

                    // Mark messages as read
                    $.ajax({
                        url: `/messages/${userId}/mark-as-read`,
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        success: function () {
                            console.log('Messages marked as read.');
                        },
                        error: function (error) {
                            console.error('Error marking messages as read:', error);
                        }
                    });

                    // Handle message sending
                    messageForm.off('submit').on('submit', function(e) {
                        e.preventDefault();
                        const message = messageInput.val().trim();
                        if (message) {
                            $.ajax({
                                url: `/messages/${userId}`,
                                method: 'POST',
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
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
                                    // toastr.success('Message sent successfully!');
                                },
                                error: function(error) {
                                    console.error('Error sending message:', error);
                                    // Check for validation error
                                    if (error.responseJSON && error.responseJSON.errors && error.responseJSON.errors.message) {
                                        toastr.error(error.responseJSON.errors.message[0]);
                                    } else {
                                        toastr.error('Failed to send the message. Please try again.');
                                    }
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

        // Scroll to bottom of messages
        chatFooter.scrollTop(chatFooter[0].scrollHeight);
        
        let typingTimeout;
        messageInput.on('input', function () {
            clearTimeout(typingTimeout);

            // Broadcast the typing event
            Echo.private(`chat.${userId}`).whisper('typing', {
                sender_id: authUserId,
            });

            // Hide the typing indicator after 1 second of inactivity
            typingTimeout = setTimeout(function () {
                Echo.private(`chat.${userId}`).whisper('stopTyping', {
                    sender_id: authUserId,
                });
            }, 1000);
        });

        // Listen for new messages
        Echo.private(`chat.${authUserId}`)
        .listen('NewMessage', function (e) {
            console.log('Received event:', e);
            appendMessage(e.message);

            // Scroll to the bottom of the chat
            chatBody.scrollTop(chatBody.prop('scrollHeight'));
        })
        .listenForWhisper('typing', function (e) {
            console.log('User is typing:', e.sender_id);
            $('#typing-indicator').show(); // Show the typing indicator
        })
        .listenForWhisper('stopTyping', function (e) {
            console.log('User stopped typing:', e.sender_id);
            $('#typing-indicator').hide(); // Hide the typing indicator
        });


        // Append a new message to the container
        function appendMessage(message) {
            console.log('Appending message:', message);
            
            const messageClass = message.sender_id === authUserId ? 'sent' : 'received';
            const messageHtml = `
                <div class="message-bubble ${messageClass}">
                    <div class="message-content">${message.message}</div>
                    <div class="message-time">${new Date(message.created_at).toLocaleTimeString()}</div>
                </div>
            `;
            chatBody.append(messageHtml);
            chatBody.scrollTop(chatBody.prop('scrollHeight'));
        }
    });
</script>
@endsection