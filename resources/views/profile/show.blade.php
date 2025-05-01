@extends('layouts.main')

@section('title', 'My Profile')

@section('main-content')
<div class="container py-4" style="max-width: 900px;">
    <!-- Profile Header -->
    <div class="card mb-4 shadow-sm" style="border-radius: 18px; overflow: hidden;">
        <div style="background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%); height: 110px; position: relative;">
            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png') }}"
                alt="{{ Auth::user()->name }}"
                class="rounded-circle border border-3 border-white"
                style="width: 90px; height: 90px; object-fit: cover; position: absolute; left: 32px; bottom: -45px; box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary position-absolute" style="right: 32px; bottom: 16px; z-index: 2;">
                    <i class="fas fa-edit me-1"></i> Edit Profile
                </a>
        </div>
        <div class="card-body pt-5 pb-3 ps-5">
            <h4 class="mb-0">{{ Auth::user()->name ?? 'Emma Davis' }}</h4>
            <div class="text-muted mb-1" style="font-size: 1.05em;">Tech & Lifestyle Influencer</div>
        </div>
    </div>

    <!-- About Me -->
    <div class="card mb-3 shadow-sm" style="border-radius: 14px;">
        <div class="card-body">
            <h6 class="fw-bold mb-2">About Me</h6>
            <div class="text-muted">
                {{ Auth::user()->bio ?? 'Tech enthusiast and lifestyle blogger with 5+ years of experience in digital content creation. Passionate about helping others navigate the intersection of technology and daily life.' }}
            </div>
        </div>
    </div>

    <!-- Areas of Expertise -->
    <div class="card mb-3 shadow-sm" style="border-radius: 14px;">
        <div class="card-body d-flex flex-wrap align-items-center">
            <h6 class="fw-bold mb-0 me-3">Areas of Expertise</h6>
            <span class="badge bg-light text-dark me-2 mb-2">Tech Reviews</span>
            <span class="badge bg-light text-dark me-2 mb-2">Lifestyle</span>
            <span class="badge bg-light text-dark me-2 mb-2">Digital Marketing</span>
            <span class="badge bg-light text-dark me-2 mb-2">Photography</span>
            <button class="btn btn-link ms-auto text-primary" style="font-size: 1.1em;"><i class="fas fa-plus"></i> Add</button>
        </div>
    </div>

    <!-- Create New Content -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card h-100 text-center shadow-sm" style="border-radius: 14px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-pen-nib fa-2x mb-2 text-primary"></i>
                    <h6 class="fw-bold mb-1">Create Post</h6>
                    <div class="text-muted small">Share photos or videos</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center shadow-sm" style="border-radius: 14px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-broadcast-tower fa-2x mb-2 text-primary"></i>
                    <h6 class="fw-bold mb-1">Live Session</h6>
                    <div class="text-muted small">Start streaming</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center shadow-sm" style="border-radius: 14px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-user-friends fa-2x mb-2 text-primary"></i>
                    <h6 class="fw-bold mb-1">One-on-One Session</h6>
                    <div class="text-muted small">Schedule private sessions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Settings -->
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm" style="border-radius: 14px;">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">One-on-One Sessions</h6>
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="text-muted small">Session Price</div>
                            <div class="fw-bold">$50</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Viewer Price</div>
                            <div class="fw-bold">$15</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-muted small">Available Hours</div>
                            <div class="fw-bold">Weekly schedule</div>
                        </div>
                        <a href="#" class="btn btn-link ms-auto text-primary">Configure</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm" style="border-radius: 14px;">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Live Sessions</h6>
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="text-muted small">Session Duration</div>
                            <div class="fw-bold">30 min</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Default length</div>
                            <div class="fw-bold">30 min</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-muted small">Available Hours</div>
                            <div class="fw-bold">Custom length</div>
                        </div>
                        <a href="#" class="btn btn-link ms-auto text-primary">Configure</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 