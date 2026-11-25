@extends('admin.layouts.app')

@section('title', 'Mantiq - Categories Management')

@section('admin-content')

<style>
    .category-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .category-actions a,
    .category-actions button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 65px;
        padding: 8px 13px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .category-view-button {
        border: 1px solid #d4dff5;
        background: #eff4ff;
        color: #3563b5;
    }

    .category-view-button:hover {
        background: #3563b5;
        border-color: #3563b5;
        color: #ffffff;
    }

    .category-edit-button {
        border: 1px solid #f3dfb2;
        background: #fff8e8;
        color: #a66b0b;
    }

    .category-edit-button:hover {
        background: #a66b0b;
        border-color: #a66b0b;
        color: #ffffff;
    }

    .category-delete-button {
        border: 1px solid #f2caca;
        background: #fff0f0;
        color: #c0392b;
    }

    .category-delete-button:hover {
        background: #c0392b;
        border-color: #c0392b;
        color: #ffffff;
    }

    .category-actions form {
        margin: 0;
    }
</style>

<header class="admin-header">

    <div>
        <h1>Categories Management</h1>
        <p>Manage your platform categories.</p>
    </div>

    <div class="admin-profile">
        <div class="profile-avatar">M</div>

        <div>
            <strong>{{ auth()->user()->name }}</strong>
            <span>Administrator</span>
        </div>
    </div>

</header>

<section class="categories-section">

    <div class="section-heading">

        <div>
            <h2>All Categories</h2>
            <p>View and manage existing categories.</p>
        </div>

        <a href="{{ route('admin.categories.create') }}"
           class="category-add-button">
            + Add Category
        </a>

    </div>

    @if(session('success'))
        <div class="category-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Track Filter -->
    <form method="GET"
          action="{{ route('admin.categories.index') }}"
          style="margin-bottom:20px;">

        <label for="track" style="font-weight:600; margin-right:10px;">
            Filter by Track:
        </label>

        <select
            name="track"
            id="track"
            onchange="this.form.submit()"
            style="padding:10px; border:1px solid #ddd; border-radius:8px;"
        >
            <option value="">All Tracks</option>

            <option value="tech"
                {{ request('track') === 'tech' ? 'selected' : '' }}>
                Tech
            </option>

            <option value="business"
                {{ request('track') === 'business' ? 'selected' : '' }}>
                Business
            </option>
        </select>

    </form>

    <!-- Categories Table -->
    <div class="category-table-wrapper">

        <table class="category-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>English Name</th>
                    <th>Arabic Name</th>
                    <th>Track</th>
                    <th>Sort Order</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($categories as $category)

                    <tr>
                        <td>{{ $category->id }}</td>

                        <td>
                            <strong>{{ $category->name }}</strong>
                        </td>

                        <td dir="rtl">
                            {{ $category->name_ar ?: '—' }}
                        </td>

                        <td>
                            {{ ucfirst($category->track) }}
                        </td>

                        <td>
                            {{ $category->sort_order }}
                        </td>

                        <td>
                            {{ $category->description ?: 'No description' }}
                        </td>

                        <td>
                            @if($category->is_active)
                                <span class="category-status active">
                                    Active
                                </span>
                            @else
                                <span class="category-status inactive">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="category-actions">

                                <a href="{{ route('admin.categories.show', $category) }}"
                                   class="category-view-button">
                                    View
                                </a>

                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="category-edit-button">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this category?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="category-delete-button">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="category-empty">
                            No categories found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    <div class="category-pagination">
        {{ $categories->appends(request()->query())->links() }}
    </div>

</section>

@endsection
