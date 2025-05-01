<!-- Left Sidebar -->
<div class="sidebar">
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" height="100">
    </div>

    <nav>
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('explore') }}" class="nav-item {{ request()->routeIs('explore') ? 'active' : '' }}">
            <i class="fas fa-compass"></i>
            <span>Explore</span>
        </a>
        <a href="{{ route('messages') }}" class="nav-item {{ request()->routeIs('messages') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i>
            <span>Messages</span>
        </a>
        <!-- <a href="{{ route('notifications') }}" class="nav-item {{ request()->routeIs('notifications') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span>Notifications</span>
        </a> -->
        <a href="{{ route('wallet') }}" class="nav-item {{ request()->routeIs('wallet') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i>
            <span>Wallet</span>
        </a>
        <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <!-- <a href="{{ route('settings') }}" class="nav-item {{ request()->routeIs('settings') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a> -->
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="nav-item border-0 bg-transparent w-100 text-start">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</div> 