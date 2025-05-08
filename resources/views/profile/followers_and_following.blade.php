@extends('layouts.main')

@section('title', $title)

@section('main-content')

<div class="container py-4">
    <h4>{{ $title }}</h4>
    <ul class="list-group">
        @forelse ($users as $user)
            <li class="list-group-item d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.png') }}" alt="{{ $user->name }}" class="rounded-circle me-3" style="width: 40px; height: 40px;">
                    <div>
                        <a href="{{ route('profile.show', $user->id) }}" class="fw-bold">{{ $user->name }}</a>
                        <div class="text-muted">{{ '@' . $user->username }}</div>
                    </div>
                </div>
                @if ($type === 'following')
                    <a href="{{ route('users.unfollow',$user->id) }}" class="btn btn-danger btn-sm unfollow-btn">Unfollow</a>
                @endif
            </li>
        @empty
            <li class="list-group-item">No {{ $type }} yet.</li>
        @endforelse
    </ul>
    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function () {
        $('.unfollow-btn').on('click', function (e) {
            e.preventDefault();

            const url = $(this).attr('href');
            if (confirm('Are you sure you want to unfollow this user?')) {
                window.location.href = url;
            }
        });
    });
</script>
@endsection