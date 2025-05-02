@extends('layouts.main')

@section('title', 'Notifications')

@section('main-content')
<div class="notifications-container">
    <div class="notifications-header">
        <h4>Notifications</h4>
        <div class="notifications-actions">
            <button class="btn btn-link text-muted">Mark all as read</button>
            <button class="btn btn-link text-muted">Clear all</button>
        </div>
    </div>

    <div class="notifications-list">
        @forelse($notifications as $notification)
            <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                <div class="notification-avatar">
                    <img src="{{ asset('storage/'.$notification->data['user']['profile_picture'])??asset('images/default-avatar.png') }}" alt="{{ $notification->data['user']['name'] }}">
                </div>
                <div class="notification-content">
                    <div class="notification-text">
                        <strong>{{ $notification->data['user']['name'] }}</strong>
                        {{ $notification->data['message'] }}
                    </div>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notification->read_at)
                    <div class="notification-dot"></div>
                @endif
            </div>
        @empty
            <div class="no-notifications">
                <div class="text-center">
                    <i class="fas fa-bell fa-3x mb-3"></i>
                    <h4>No notifications yet</h4>
                    <p class="text-muted">When you get notifications, they'll show up here</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('styles')
<style>
.notifications-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    padding: 1.5rem;
}

.notifications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.notifications-actions {
    display: flex;
    gap: 1rem;
}

.notification-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    position: relative;
}

.notification-item.unread {
    background: var(--bg-light);
}

.notification-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 1rem;
    overflow: hidden;
}

.notification-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.notification-content {
    flex: 1;
}

.notification-text {
    margin-bottom: 0.25rem;
}

.notification-dot {
    width: 8px;
    height: 8px;
    background: var(--primary-color);
    border-radius: 50%;
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
}

.no-notifications {
    padding: 3rem 0;
    color: var(--text-muted);
}
</style>
@endsection 