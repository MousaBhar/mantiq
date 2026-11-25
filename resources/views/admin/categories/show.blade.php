@extends('admin.layouts.app')

@section('title', 'Mantiq - View Category')

@section('admin-content')

<header class="admin-header">

    <div>
        <h1>View Category</h1>
        <p>View the details of this category.</p>
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

    <!-- Section Heading -->
    <div class="section-heading">

        <div>
            <h2>Category #{{ $category->id }}</h2>
            <p>Category information and details.</p>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">

            <a href="{{ route('admin.categories.index') }}"
               class="category-edit-button">
                ← Back to Categories
            </a>

            <a href="{{ route('admin.categories.edit', $category) }}"
               class="category-add-button">
                Edit Category
            </a>

        </div>

    </div>

    <!-- Category Details -->
    <div class="category-table-wrapper" style="padding:25px;">

        <!-- English Name -->
        <div style="margin-bottom:25px;">
            <label style="display:block; margin-bottom:8px; font-weight:600;">
                Category Name (English)
            </label>

            <p style="padding:12px; background:#f8f9fa; border-radius:8px; margin:0;">
                {{ $category->name }}
            </p>
        </div>

        <!-- Arabic Name -->
        <div style="margin-bottom:25px;">
            <label style="display:block; margin-bottom:8px; font-weight:600;">
                Category Name (Arabic)
            </label>

            <p dir="rtl"
               style="padding:12px; background:#f8f9fa; border-radius:8px; margin:0;">
                {{ $category->name_ar ?: 'Not provided' }}
            </p>
        </div>

        <!-- Track -->
        <div style="margin-bottom:25px;">
            <label style="display:block; margin-bottom:8px; font-weight:600;">
                Track
            </label>

            <p style="padding:12px; background:#f8f9fa; border-radius:8px; margin:0;">
                {{ ucfirst($category->track) }}
            </p>
        </div>

        <!-- Sort Order -->
        <div style="margin-bottom:25px;">
            <label style="display:block; margin-bottom:8px; font-weight:600;">
                Sort Order
            </label>

            <p style="padding:12px; background:#f8f9fa; border-radius:8px; margin:0;">
                {{ $category->sort_order }}
            </p>
        </div>

        <!-- Description -->
        <div style="margin-bottom:25px;">
            <label style="display:block; margin-bottom:8px; font-weight:600;">
                Description
            </label>

            <p style="padding:12px; background:#f8f9fa; border-radius:8px; margin:0; min-height:70px;">
                {{ $category->description ?: 'No description provided.' }}
            </p>
        </div>

        <!-- Image -->
        <div style="margin-bottom:25px;">
            <label style="display:block; margin-bottom:8px; font-weight:600;">
                Image
            </label>

            @if($category->image)
                <div>
                    <p style="margin-bottom:10px;">
                        {{ $category->image }}
                    </p>

                    <img src="{{ $category->image }}"
                         alt="{{ $category->name }}"
                         style="max-width:200px; max-height:200px; object-fit:contain; border-radius:8px;">
                </div>
            @else
                <p style="padding:12px; background:#f8f9fa; border-radius:8px; margin:0;">
                    No image provided.
                </p>
            @endif
        </div>

        <!-- Status -->
        <div style="margin-bottom:25px;">
            <label style="display:block; margin-bottom:8px; font-weight:600;">
                Category Status
            </label>

            @if($category->is_active)
                <span class="category-status active">
                    Active
                </span>
            @else
                <span class="category-status inactive">
                    Inactive
                </span>
            @endif
        </div>

        <!-- Actions -->
        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">

            <a href="{{ route('admin.categories.edit', $category) }}"
               class="category-add-button">
                Edit Category
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="category-edit-button">
                Back to Categories
            </a>

        </div>

    </div>

</section>

@endsection
