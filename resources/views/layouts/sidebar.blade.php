<aside class="admin-sidebar">

    {{-- MANTIQ Logo: Click to return to Dashboard --}}
    <a href="{{ route('admin.dashboard') }}" class="admin-brand">
        <img src="{{ asset('images/brand/logo-mark.svg') }}" alt="" width="34" height="34" style="border-radius:9px;">
        <span>MANTIQ</span>
    </a>

    {{-- Main Menu --}}
    <div class="sidebar-section">
        <p class="sidebar-label">MAIN MENU</p>

        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span>▦</span> Dashboard
        </a>

        <a href="{{ route('admin.users.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span>♙</span> Users
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span>▤</span> Categories
        </a>
    </div>

    {{-- Content Management --}}
    <div class="sidebar-section">
        <p class="sidebar-label">CONTENT MANAGEMENT</p>

        <a href="{{ route('admin.contents.index', ['track' => 'tech']) }}"
            class="sidebar-link {{ request('track') === 'tech' ? 'active' : '' }}">
            <span>⌘</span> Technical Content
        </a>

        <a href="{{ route('admin.contents.index', ['track' => 'business']) }}"
            class="sidebar-link {{ request('track') === 'business' ? 'active' : '' }}">
            <span>▣</span> Business Content
        </a>

        <a href="{{ route('admin.contents.index', ['track' => 'podcast_library']) }}"
            class="sidebar-link {{ request('track') === 'podcast_library' ? 'active' : '' }}">
            <span>♫</span> Podcast Library
        </a>

        <a href="#" class="sidebar-link">
            <span>◉</span> Community
        </a>
    </div>

    {{-- System --}}
    <div class="sidebar-section">
        <p class="sidebar-label">SYSTEM</p>

        <a href="{{ route('admin.profile.edit') }}"
            class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <span>⚙</span> My Profile
        </a>

        <a href="#" class="sidebar-link">
            <span>⚙</span> Settings
        </a>
    </div>

    {{-- Logout --}}
    <div class="sidebar-bottom">
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="sidebar-link logout-button">
                <span>↪</span> Logout
            </button>
        </form>
    </div>

</aside>
