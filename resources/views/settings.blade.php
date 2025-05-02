@extends('layouts.main')

@section('title', 'Settings')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-md-3">
            <div class="left-sidebar">
                <div class="sidebar-header">
                    <h5>Settings</h5>
                </div>
                <div class="sidebar-content">
                    <div class="settings-nav">
                        <a href="#profile" class="settings-nav-item active">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                        <a href="#account" class="settings-nav-item">
                            <i class="fas fa-lock"></i>
                            <span>Account</span>
                        </a>
                        <a href="#notifications" class="settings-nav-item">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </a>
                        <a href="#privacy" class="settings-nav-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Privacy</span>
                        </a>
                        <a href="#security" class="settings-nav-item">
                            <i class="fas fa-key"></i>
                            <span>Security</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-6">
            <div class="settings-container">
                <div class="settings-section" id="profile">
                    <h4>Profile Settings</h4>
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="form-label">Profile Picture</label>
                            <div class="profile-picture-upload">
                                <img src="{{ asset('storage/'.auth()->user()->profile_picture)??asset('images/default-avatar.png') }}" alt="Profile Picture" class="current-picture">
                                <div class="upload-overlay">
                                    <i class="fas fa-camera"></i>
                                    <span>Change Photo</span>
                                </div>
                                <input type="file" name="profile_picture" class="d-none" accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3">{{ auth()->user()->bio }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

                <div class="settings-section" id="account">
                    <h4>Account Settings</h4>
                    <form action="{{ route('account.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>

                <div class="settings-section" id="notifications">
                    <h4>Notification Settings</h4>
                    <form action="{{ route('notifications.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="email_notifications" class="form-check-input" id="emailNotifications" {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                                <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="push_notifications" class="form-check-input" id="pushNotifications" {{ auth()->user()->push_notifications ? 'checked' : '' }}>
                                <label class="form-check-label" for="pushNotifications">Push Notifications</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Preferences</button>
                    </form>
                </div>

                <div class="settings-section" id="privacy">
                    <h4>Privacy Settings</h4>
                    <form action="{{ route('privacy.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Profile Visibility</label>
                            <select name="profile_visibility" class="form-select">
                                <option value="public" {{ auth()->user()->profile_visibility === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ auth()->user()->profile_visibility === 'private' ? 'selected' : '' }}>Private</option>
                                <option value="followers" {{ auth()->user()->profile_visibility === 'followers' ? 'selected' : '' }}>Followers Only</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="show_online_status" class="form-check-input" id="showOnlineStatus" {{ auth()->user()->show_online_status ? 'checked' : '' }}>
                                <label class="form-check-label" for="showOnlineStatus">Show Online Status</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Privacy Settings</button>
                    </form>
                </div>

                <div class="settings-section" id="security">
                    <h4>Security Settings</h4>
                    <form action="{{ route('security.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="two_factor_auth" class="form-check-input" id="twoFactorAuth" {{ auth()->user()->two_factor_auth ? 'checked' : '' }}>
                                <label class="form-check-label" for="twoFactorAuth">Two-Factor Authentication</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="login_notifications" class="form-check-input" id="loginNotifications" {{ auth()->user()->login_notifications ? 'checked' : '' }}>
                                <label class="form-check-label" for="loginNotifications">Login Notifications</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Security Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-md-3">
            <div class="right-sidebar">
                <div class="sidebar-header">
                    <h5>Help & Support</h5>
                </div>
                <div class="sidebar-content">
                    <div class="help-section">
                        <h6>Need Help?</h6>
                        <p class="text-muted">Check our FAQ or contact support for assistance.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">View FAQ</a>
                        <a href="#" class="btn btn-outline-primary btn-sm">Contact Support</a>
                    </div>
                    <div class="help-section mt-4">
                        <h6>Account Status</h6>
                        <div class="account-status">
                            <div class="status-item">
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Email Verified</span>
                            </div>
                            <div class="status-item">
                                <i class="fas fa-shield-alt text-primary"></i>
                                <span>Two-Factor Auth: {{ auth()->user()->two_factor_auth ? 'Enabled' : 'Disabled' }}</span>
                            </div>
                            <div class="status-item">
                                <i class="fas fa-clock text-warning"></i>
                                <span>Last Login: {{ auth()->user()->last_login_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.left-sidebar, .right-sidebar {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    height: calc(100vh - 200px);
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.sidebar-content {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

.settings-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    padding: 1.5rem;
}

.settings-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-color);
}

.settings-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.settings-nav {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.settings-nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    color: var(--text-color);
    text-decoration: none;
    transition: all 0.3s ease;
}

.settings-nav-item:hover {
    background: var(--bg-light);
}

.settings-nav-item.active {
    background: var(--primary-color);
    color: white;
}

.settings-nav-item i {
    margin-right: 0.75rem;
    width: 20px;
    text-align: center;
}

.profile-picture-upload {
    position: relative;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
}

.current-picture {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.upload-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-picture-upload:hover .upload-overlay {
    opacity: 1;
}

.upload-overlay i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.help-section {
    background: var(--bg-light);
    border-radius: 12px;
    padding: 1rem;
}

.account-status {
    margin-top: 1rem;
}

.status-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.status-item i {
    margin-right: 0.5rem;
    width: 20px;
    text-align: center;
}
</style>
@endsection