@extends('admin.layouts.app')

@section('title', 'Mantiq - Admin Dashboard')

@section('admin-content')

    {{-- Header --}}
    <header class="admin-header">

        <div>
            <h1>Dashboard Overview</h1>
            <p>Welcome back, {{ auth()->user()->name }}!</p>
        </div>

        <div class="admin-header-actions">
            <div class="admin-profile">

                @if (auth()->user()->avatar)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(auth()->user()->avatar) }}"
                        alt="Profile picture"
                        class="profile-avatar-image">
                @else
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>Administrator</span>
                </div>

            </div>
        </div>

    </header>

    {{-- Welcome Banner --}}
    <section class="admin-welcome">

        <div>
            <span>ADMINISTRATION PANEL</span>
            <h2>Welcome to Mantiq!</h2>
            <p>Manage your platform, content and community from one place.</p>
        </div>

        <div class="welcome-decoration">M</div>

    </section>

    {{-- Statistics --}}
    <section class="admin-stats">

        <article class="stat-card">
            <div class="stat-icon purple">♙</div>
            <div>
                <p>Total Users</p>
                <h3>{{ number_format($totalUsers) }}</h3>
                <span>Registered accounts</span>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon pink">▣</div>
            <div>
                <p>Total Content</p>
                <h3>{{ number_format($totalContent) }}</h3>
                <span>All content materials</span>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon blue">✓</div>
            <div>
                <p>Published Content</p>
                <h3>{{ number_format($publishedContent) }}</h3>
                <span>Published materials</span>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon orange">◷</div>
            <div>
                <p>Draft Content</p>
                <h3>{{ number_format($draftContent) }}</h3>
                <span>Waiting for publication</span>
            </div>
        </article>

    </section>

    {{-- Analytics --}}
    <section class="management-section">

        <div class="section-heading">
            <div>
                <h2>Platform Analytics</h2>
                <p>An overview of your platform data.</p>
            </div>
        </div>

        <div class="dashboard-analytics">

            {{-- Content Status Chart --}}
            <article class="analytics-card">

                <div class="analytics-card-header">
                    <div>
                        <h3>Content Status</h3>
                        <p>Distribution by publication status</p>
                    </div>
                </div>

                @php
                    $contentTotal = max($totalContent, 1);

                    $publishedPercentage = ($publishedContent / $contentTotal) * 100;
                    $draftPercentage = ($draftContent / $contentTotal) * 100;
                    $archivedPercentage = ($archivedContent / $contentTotal) * 100;
                @endphp

                <div class="status-chart">
                    <div class="status-donut"
                        style="--published: {{ $publishedPercentage }}%; --draft: {{ $draftPercentage }}%; --archived: {{ $archivedPercentage }}%;">

                        <div class="status-donut-center">
                            <strong>{{ $totalContent }}</strong>
                            <span>Total Content</span>
                        </div>

                    </div>
                </div>

                <div class="chart-legend">

                    <div>
                        <span class="legend-dot published-dot"></span>
                        Published
                        <strong>{{ $publishedContent }}</strong>
                    </div>

                    <div>
                        <span class="legend-dot draft-dot"></span>
                        Draft
                        <strong>{{ $draftContent }}</strong>
                    </div>

                    <div>
                        <span class="legend-dot archived-dot"></span>
                        Archived
                        <strong>{{ $archivedContent }}</strong>
                    </div>

                </div>

            </article>

            {{-- Content Types --}}
            <article class="analytics-card">

                <div class="analytics-card-header">
                    <div>
                        <h3>Content Overview</h3>
                        <p>Content by management section</p>
                    </div>
                </div>

                <div class="analytics-bars">

                    <div class="analytics-bar-item">

                        <div class="analytics-bar-label">
                            <span>Technical Content</span>
                            <strong>{{ $techContent }}</strong>
                        </div>

                        <div class="analytics-bar-track">
                            <div class="analytics-bar-fill purple-fill"
                                style="width: {{ $totalContent > 0 ? ($techContent / $totalContent) * 100 : 0 }}%">
                            </div>
                        </div>

                    </div>

                    <div class="analytics-bar-item">

                        <div class="analytics-bar-label">
                            <span>Business Content</span>
                            <strong>{{ $businessContent }}</strong>
                        </div>

                        <div class="analytics-bar-track">
                            <div class="analytics-bar-fill pink-fill"
                                style="width: {{ $totalContent > 0 ? ($businessContent / $totalContent) * 100 : 0 }}%">
                            </div>
                        </div>

                    </div>

                    <div class="analytics-bar-item">

                        <div class="analytics-bar-label">
                            <span>Podcast Library</span>
                            <strong>{{ $podcastContent }}</strong>
                        </div>

                        <div class="analytics-bar-track">
                            <div class="analytics-bar-fill blue-fill"
                                style="width: {{ $totalContent > 0 ? ($podcastContent / $totalContent) * 100 : 0 }}%">
                            </div>
                        </div>

                    </div>

                </div>

            </article>

            {{-- Platform Summary --}}
            <article class="analytics-card">

                <div class="analytics-card-header">
                    <div>
                        <h3>Platform Summary</h3>
                        <p>Current platform statistics</p>
                    </div>
                </div>

                <div class="summary-metrics">

                    <div class="summary-metric">
                        <span class="summary-metric-icon">♙</span>
                        <div>
                            <span>Total Users</span>
                            <strong>{{ number_format($totalUsers) }}</strong>
                        </div>
                    </div>

                    <div class="summary-metric">
                        <span class="summary-metric-icon">▤</span>
                        <div>
                            <span>Categories</span>
                            <strong>{{ number_format($totalCategories) }}</strong>
                        </div>
                    </div>

                    <div class="summary-metric">
                        <span class="summary-metric-icon">▣</span>
                        <div>
                            <span>Total Content</span>
                            <strong>{{ number_format($totalContent) }}</strong>
                        </div>
                    </div>

                </div>

            </article>

        </div>

    </section>

    {{-- Recent Activity --}}
    <section class="management-section">

        <div class="section-heading">
            <div>
                <h2>Recent Activity</h2>
                <p>Latest updates from your platform.</p>
            </div>
        </div>

        <div class="recent-activity-card">

            @forelse($recentActivity as $activity)

                <div class="activity-item">

                    <div class="activity-icon {{ $activity['color'] }}">
                        {{ $activity['icon'] }}
                    </div>

                    <div class="activity-details">
                        <strong>{{ $activity['title'] }}</strong>
                        <p>{{ $activity['description'] }}</p>
                    </div>

                    <time>
                        {{ $activity['date']->diffForHumans() }}
                    </time>

                </div>

            @empty

                <div class="activity-empty">
                    <p>No recent activity available.</p>
                </div>

            @endforelse

        </div>

    </section>

    {{-- Management Sections --}}
    <section class="management-section">

        <div class="section-heading">
            <div>
                <h2>Platform Management</h2>
                <p>Manage the main sections of Mantiq.</p>
            </div>
        </div>

        <div class="management-grid">

            <article class="management-card">
                <span class="management-icon">♙</span>
                <h3>Users</h3>
                <p>Manage registered users and their accounts.</p>

                <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                    Manage Users →
                </a>
            </article>

            <article class="management-card">
                <span class="management-icon">▤</span>
                <h3>Categories</h3>
                <p>Manage platform categories and organize content.</p>

                <a href="{{ route('admin.categories.index') }}" class="sidebar-link">
                    Manage Categories →
                </a>
            </article>

            <article class="management-card">
                <span class="management-icon">⌘</span>
                <h3>Technical Content</h3>
                <p>Manage technical courses and learning resources.</p>

                <a href="{{ route('admin.contents.index', ['track' => 'tech']) }}"
                    class="sidebar-link">
                    Manage Technical Content →
                </a>
            </article>

            <article class="management-card">
                <span class="management-icon">▣</span>
                <h3>Business Content</h3>
                <p>Manage business courses, books and articles.</p>

                <a href="{{ route('admin.contents.index', ['track' => 'business']) }}"
                    class="sidebar-link">
                    Manage Business Content →
                </a>
            </article>

            <article class="management-card">
                <span class="management-icon">♫</span>
                <h3>Podcast Library</h3>
                <p>Manage podcast episodes and the library.</p>

                <a href="{{ route('admin.contents.index', ['track' => 'podcast_library']) }}"
                    class="sidebar-link">
                    Manage Podcast Library →
                </a>
            </article>

            <article class="management-card">
                <span class="management-icon">◉</span>
                <h3>Community</h3>
                <p>Manage community interactions and comments.</p>
                <span class="coming-soon">Coming Soon</span>
            </article>

        </div>

    </section>

@endsection
