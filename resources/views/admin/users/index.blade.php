@extends('layouts.app')

@section('title', 'Mantiq - User Management')

@section('content')

<style>
    .users-section {
        padding: 25px;
    }

    .users-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .users-stat-card {
        background: #fff;
        padding: 24px;
        border-radius: 14px;
        border: 1px solid #edf0f5;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .users-stat-card span {
        display: block;
        color: #777;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .users-stat-card h2 {
        font-size: 30px;
        color: #242b3d;
        margin: 0;
    }

    .users-table-card {
        background: #fff;
        border-radius: 14px;
        padding: 25px;
        border: 1px solid #edf0f5;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .users-table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 22px;
    }

    .users-table-heading h2 {
        color: #242b3d;
        font-size: 20px;
        margin: 0 0 6px;
    }

    .users-table-heading p {
        color: #888;
        font-size: 13px;
        margin: 0;
    }

    .users-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 650px;
    }

    .users-table thead {
        background: #f6f7fb;
    }

    .users-table th {
        padding: 15px;
        color: #687086;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .users-table td {
        padding: 16px 15px;
        border-bottom: 1px solid #f0f1f5;
        color: #50576b;
        font-size: 14px;
    }

    .users-table tbody tr:hover {
        background: #fafbff;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #ecebff;
        color: #6558d3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .user-name {
        color: #292f40;
        font-weight: 600;
    }

    .user-role {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #edf0f7;
        color: #596579;
    }

    .user-role.admin {
        background: #eeeaff;
        color: #6753c8;
    }

    .user-role.user {
        background: #e8f6ef;
        color: #218653;
    }

    .users-empty {
        text-align: center;
        padding: 35px !important;
        color: #999 !important;
    }

    .users-pagination {
        margin-top: 22px;
    }

    .users-back-button {
        display: inline-block;
        padding: 10px 17px;
        border-radius: 8px;
        background: #eeeaff;
        color: #6558d3;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: 0.2s;
    }

    .users-back-button:hover {
        background: #6558d3;
        color: #fff;
    }

    @media (max-width: 768px) {
        .users-section {
            padding: 15px;
        }

        .users-table-card {
            padding: 15px;
        }

        .users-table-heading {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="admin-layout">

    <!-- Sidebar -->
    <aside class="admin-sidebar">

        <div class="admin-brand">
            <span class="brand-icon">M</span>
            <span>MANTIQ</span>
        </div>

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

        <div class="sidebar-section">
            <p class="sidebar-label">CONTENT MANAGEMENT</p>

            <a href="{{ route('admin.contents.index', ['track' => 'tech']) }}"
               class="sidebar-link">
                <span>⌘</span> Tech Track
            </a>

            <a href="{{ route('admin.contents.index', ['track' => 'business']) }}"
               class="sidebar-link">
                <span>▣</span> B2B Route
            </a>

            <a href="{{ route('admin.contents.index', ['type' => 'podcast']) }}"
               class="sidebar-link">
                <span>♫</span> BuildCast
            </a>

            <a href="#" class="sidebar-link">
                <span>◉</span> Community
            </a>
        </div>

        <div class="sidebar-section">
            <p class="sidebar-label">SYSTEM</p>

            <a href="#" class="sidebar-link">
                <span>♧</span> Notifications
            </a>

            <a href="#" class="sidebar-link">
                <span>⚙</span> Settings
            </a>
        </div>

        <div class="sidebar-bottom">
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="sidebar-link logout-button">
                    <span>↪</span> Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="admin-main">

        <!-- Header -->
        <header class="admin-header">

            <div>
                <h1>User Management</h1>
                <p>Manage and view all registered users.</p>
            </div>

            <div class="admin-profile">
                <div class="profile-avatar">M</div>

                <div>
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>Administrator</span>
                </div>
            </div>

        </header>

        <!-- Users Content -->
        <section class="users-section">

            <!-- Statistics -->
            <div class="users-stats">

                <div class="users-stat-card">
                    <span>Total Registered Users</span>
                    <h2>{{ $totalUsers }}</h2>
                </div>

            </div>

            <!-- Users Table -->
            <div class="users-table-card">

                <div class="users-table-heading">

                    <div>
                        <h2>Registered Users</h2>
                        <p>View all users registered on the platform.</p>
                    </div>

                    <a href="{{ route('admin.dashboard') }}"
                       class="users-back-button">
                        ← Back to Dashboard
                    </a>

                </div>

                <div class="users-table-wrapper">

                    <table class="users-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Email Address</th>
                                <th>Role</th>
                                <th>Joined Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($users as $user)

                                <tr>

                                    <td>{{ $user->id }}</td>

                                    <td>
                                        <div class="user-info">

                                            <div class="user-avatar">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>

                                            <span class="user-name">
                                                {{ $user->name }}
                                            </span>

                                        </div>
                                    </td>

                                    <td>{{ $user->email }}</td>

                                    <td>
                                        @php
                                            $roleName = $user->role?->name
                                                ?? $user->role?->slug
                                                ?? 'User';

                                            $roleClass = strtolower($user->role?->slug ?? 'user');
                                        @endphp

                                        <span class="user-role {{ $roleClass === 'admin' ? 'admin' : 'user' }}">
                                            {{ $roleName }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $user->created_at?->format('d M Y') ?? '—' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="users-empty">
                                        No users found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <!-- Pagination -->
                <div class="users-pagination">
                    {{ $users->links() }}
                </div>

            </div>

        </section>

    </main>

</div>

@endsection
