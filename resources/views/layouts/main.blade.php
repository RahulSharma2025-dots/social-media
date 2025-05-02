@extends('layouts.app')

@php
    $suggestedUsers = app(App\Http\Controllers\Controller::class)->getSuggestedUsers();
    $trendingTopics = app(App\Http\Controllers\Controller::class)->getTrendingTopics();
@endphp

@section('content')
<div class="main-container">
    <!-- Left Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="feed-container">
        @yield('main-content')
    </div>

    <!-- Right Sidebar -->
    <div class="right-sidebar">
        <!-- Suggested Users -->
        <div class="suggested-users">
            <h5 class="mb-3">Suggested Users</h5>
            @foreach($suggestedUsers as $user)
                <div class="suggested-user">
                    <img src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : asset('images/default-avatar.png') }}" 
                         alt="{{ $user->name }}" class="user-avatar">
                    <div class="user-details">
                        <a href="{{ route('profile', $user) }}" class="user-name">{{ $user->name }}</a>
                        <div class="user-bio">{{ $user->bio }}</div>
                    </div>
                    <form action="{{ route('users.follow', $user) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="follow-btn">Follow</button>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Trending Topics -->
        <div class="trending-topics">
            <h5 class="mb-3">Trending Topics</h5>
            @foreach($trendingTopics as $topic)
                <div class="topic-item">
                    <div class="topic-icon">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <div class="topic-info">
                        <div class="topic-name">#{{ $topic->name }}</div>
                        <div class="topic-count">{{ $topic->posts_count }} posts</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 