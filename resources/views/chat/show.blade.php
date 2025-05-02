@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex h-[600px] bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Users List -->
        <div class="w-1/4 border-r border-gray-200 bg-gray-50">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Chats</h2>
            </div>
            <div class="overflow-y-auto h-full">
                @foreach($users as $chatUser)
                    <a href="{{ route('chat.show', $chatUser) }}" 
                       class="block p-4 hover:bg-gray-100 border-b border-gray-200 {{ $chatUser->id === $user->id ? 'bg-gray-100' : '' }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-600 font-medium">
                                        {{ substr($chatUser->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $chatUser->name }}</p>
                                <p class="text-xs text-gray-500">Click to start chatting</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Chat Window -->
        <div class="w-3/4 flex flex-col">
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-200 bg-white">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600 font-medium">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500" id="typing-indicator" style="display: none;">typing...</p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 p-4 overflow-y-auto bg-gray-50" 
                 id="messages-container"
                 data-user-id="{{ auth()->id() }}"
                 data-auth-id="{{ auth()->id() }}"
                 data-mark-read-url="{{ route('chat.markAsRead', $user) }}">
                @foreach($messages as $message)
                    <div class="mb-4 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                        <div class="inline-block p-3 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                            <p class="text-sm">{{ $message->message }}</p>
                            <p class="text-xs mt-1 {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                                {{ $message->created_at->format('H:i') }}
                                @if($message->sender_id === auth()->id())
                                    <span class="ml-1">
                                        @if($message->is_read)
                                            ✓✓
                                        @else
                                            ✓
                                        @endif
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-gray-200 bg-white">
                <form id="message-form" 
                      class="flex space-x-4"
                      data-url="{{ route('chat.store', $user) }}">
                    <input type="text" 
                           name="message" 
                           class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                           placeholder="Type your message..."
                           required>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    const $messagesContainer = $('#messages-container');
    const $messageForm = $('#message-form');
    const $messageInput = $messageForm.find('input[name="message"]');
    const $typingIndicator = $('#typing-indicator');
    let typingTimeout;

    // Scroll to bottom of messages
    $messagesContainer.scrollTop($messagesContainer[0].scrollHeight);

    // Handle message submission
    $messageForm.on('submit', function(e) {
        e.preventDefault();
        const message = $messageInput.val().trim();
        if (!message) return;

        $.ajax({
            url: $messageForm.data('url'),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({ message }),
            contentType: 'application/json',
            success: function(response) {
                $messageInput.val('');
                appendMessage(response.message);
            },
            error: function(error) {
                console.error('Error sending message:', error);
            }
        });
    });

    // Listen for new messages
    Echo.private(`chat.${$messagesContainer.data('user-id')}`)
        .listen('NewMessage', function(e) {
            appendMessage(e.message);
            // Mark message as read
            $.ajax({
                url: $messagesContainer.data('mark-read-url'),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

    // Handle typing indicator
    $messageInput.on('input', function() {
        clearTimeout(typingTimeout);
        $typingIndicator.show();

        typingTimeout = setTimeout(function() {
            $typingIndicator.hide();
        }, 1000);
    });

    // Append a new message to the container
    function appendMessage(message) {
        const messageHtml = `
            <div class="mb-4 ${message.sender_id === $messagesContainer.data('auth-id') ? 'text-right' : 'text-left'}">
                <div class="inline-block p-3 rounded-lg ${message.sender_id === $messagesContainer.data('auth-id') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'}">
                    <p class="text-sm">${message.message}</p>
                    <p class="text-xs mt-1 ${message.sender_id === $messagesContainer.data('auth-id') ? 'text-blue-100' : 'text-gray-500'}">
                        ${new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                        ${message.sender_id === $messagesContainer.data('auth-id') ? `
                            <span class="ml-1">
                                ${message.is_read ? '✓✓' : '✓'}
                            </span>
                        ` : ''}
                    </p>
                </div>
            </div>
        `;
        $messagesContainer.append(messageHtml);
        $messagesContainer.scrollTop($messagesContainer[0].scrollHeight);
    }
});
</script>
@endpush 