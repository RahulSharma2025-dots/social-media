@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Users List -->
        <div class=" border-r border-gray-200 bg-gray-50">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Chats</h2>
            </div>
            <div class="overflow-y-auto h-full">
                @foreach($users as $user)
                    <a href="{{ route('chat.show', $user) }}" 
                       class="block p-4 hover:bg-gray-100 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-600 font-medium">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">Click to start chatting</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Chat Window -->
        <div class="w-3/4 flex flex-col">
            <div class="flex-1 p-4 bg-gray-50">
                <div class="text-center text-gray-500">
                    Select a user to start chatting
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 