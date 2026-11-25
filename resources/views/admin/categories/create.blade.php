@extends('admin.layouts.app')

@section('title', 'Mantiq - Add Category')

@section('admin-content')

<header class="admin-header">
    <div>
        <h1>Add New Category</h1>
        <p>Create a new category for your platform.</p>
    </div>
</header>

<section class="category-form-card">

    <div class="section-heading">
        <div>
            <h2>Category Information</h2>
            <p>Enter the category details below.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="category-errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.store') }}"
          method="POST">

        @csrf

        <!-- English Name -->
        <div class="category-form-group">
            <label for="name">Category Name (English)</label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Example: Artificial Intelligence"
                required
            >
        </div>

        <!-- Arabic Name -->
        <div class="category-form-group">
            <label for="name_ar">Category Name (Arabic)</label>

            <input
                type="text"
                id="name_ar"
                name="name_ar"
                value="{{ old('name_ar') }}"
                placeholder="مثال: الذكاء الاصطناعي"
                dir="rtl"
                required
            >
        </div>

        <!-- Track -->
        <div class="category-form-group">
            <label for="track">Track</label>

            <select id="track" name="track" required>
                <option value="">Select Track</option>

                <option value="tech" {{ old('track') == 'tech' ? 'selected' : '' }}>
                    Tech
                </option>

                <option value="business" {{ old('track') == 'business' ? 'selected' : '' }}>
                    Business
                </option>
            </select>
        </div>

        <!-- Sort Order -->
        <div class="category-form-group">
            <label for="sort_order">Sort Order</label>

            <input
                type="number"
                id="sort_order"
                name="sort_order"
                value="{{ old('sort_order', 0) }}"
                min="0"
                required
            >
        </div>

        <!-- Description -->
        <div class="category-form-group">
            <label for="description">Description</label>

            <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Enter category description"
            >{{ old('description') }}</textarea>
        </div>

        <!-- Image -->
        <div class="category-form-group">
            <label for="image">Image Path</label>

            <input
                type="text"
                id="image"
                name="image"
                value="{{ old('image') }}"
                placeholder="Enter image path (optional)"
            >
        </div>

        <!-- Status -->
        <div class="category-form-group">
            <label for="is_active">Status</label>

            <select id="is_active" name="is_active" required>
                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

        <div class="category-form-actions">

            <a href="{{ route('admin.categories.index') }}"
               class="category-cancel-button">
                Cancel
            </a>

            <button type="submit" class="category-save-button">
                Save Category
            </button>

        </div>

    </form>

</section>

@endsection
