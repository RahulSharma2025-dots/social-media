@extends('layouts.main')

@section('title', 'Edit Profile')

@section('styles')
<style>
    .image-preview-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin-bottom: 1rem;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .image-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .image-preview:hover {
        transform: scale(1.05);
    }

    .image-upload-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        text-align: center;
        padding: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-upload-label:hover {
        background: rgba(0, 0, 0, 0.7);
    }

    .image-upload-input {
        display: none;
    }
</style>
@endsection

@section('main-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Profile</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            <div class="image-preview-container">
                                <img src="{{ $user->getProfileImageUrl() }}" alt="Current profile image" class="image-preview" id="imagePreview">
                                <label for="profile_picture" class="image-upload-label">
                                    <i class="fas fa-camera"></i> Change Photo
                                </label>
                                <input type="file" name="profile_picture" id="profile_picture" class="image-upload-input" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>

                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>

                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>

                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>

                        </div>
                        
                        <!-- @if($user->isInfluencer()) -->
                        <div class="mb-3">
                            <label for="category" class="form-label">{{ __('Category/Niche') }}</label>
                            <select class="form-control @error('preferences') is-invalid @enderror" name="preferences" id="preferences">
                                <option value="">Select Category</option>
                                @foreach($availablePreferences['categories'] as $category)
                                <option value="{{ $category['id'] }}" {{ $user->preferences->preference_key == $category['id'] ? 'selected' : '' }}>
                                    {{ $category['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- @endif -->

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const $imageInput = $('#profile_picture');
    const $imagePreview = $('#imagePreview');

    $imageInput.on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $imagePreview.attr('src', e.target.result).css('opacity', '1');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection