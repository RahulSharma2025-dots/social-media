@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users"></i> User Management
                </h5>
                <form action="{{ route('admin.users') }}" method="GET" class="d-flex gap-2">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="user" {{ request('type') === 'user' ? 'selected' : '' }}>Users</option>
                        <option value="influencer" {{ request('type') === 'influencer' ? 'selected' : '' }}>Influencers</option>
                    </select>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search users..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Reports</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->user_type) }}</td>
                            <td>
                                @if($user->is_banned)
                                    <span class="badge bg-danger">Banned</span>
                                @elseif($user->is_verified)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($user->reports_count > 0)
                                    <span class="badge bg-danger">{{ $user->reports_count }}</span>
                                @else
                                    <span class="badge bg-success">0</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    @if($user->user_type === 'influencer' && !$user->is_verified)
                                        <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Verify
                                            </button>
                                        </form>
                                    @endif
                                    @if(!$user->is_banned)
                                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-ban"></i> Ban
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning">
                                                <i class="fas fa-unlock"></i> Unban
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection 