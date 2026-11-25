@extends('admin.layouts.app')

@section('title', 'Mantiq - Edit Category')

@section('admin-content')

<header class="admin-header">

    <div>
        <h1>Edit Category</h1>
        <p>Update the information of this category.</p>
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
            <h2>Edit Category #{{ $category->id }}</h2>
            <p>Modify the category details below.</p>
        </div>

        <a href="{{ route('admin.categories.index') }}"
           class="category-edit-button">
            ← Back to Categories
        </a>
    </div>

    @if(session('success'))
        <div class="category-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="category-success" style="background:#fee2e2; color:#991b1b;">
            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="category-table-wrapper" style="padding:25px;">

        <form action="{{ route('admin.categories.update', $category) }}"
              method="POST">

            @csrf
            @method('PUT')

            <!-- English Name -->
            <div style="margin-bottom:20px;">
                <label for="name"
                       style="display:block; margin-bottom:8px; font-weight:600;">
                    Category Name (English) *
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    required
                    maxlength="255"
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;"
                >

                @error('name')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Arabic Name -->
            <div style="margin-bottom:20px;">
                <label for="name_ar"
                       style="display:block; margin-bottom:8px; font-weight:600;">
                    Category Name (Arabic) *
                </label>

                <input
                    type="text"
                    id="name_ar"
                    name="name_ar"
                    value="{{ old('name_ar', $category->name_ar) }}"
                    dir="rtl"
                    required
                    maxlength="255"
                    placeholder="أدخل اسم التصنيف بالعربية"
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;"
                >

                @error('name_ar')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Track -->
            <div style="margin-bottom:20px;">
                <label for="track"
                       style="display:block; margin-bottom:8px; font-weight:600;">
                    Track *
                </label>

                <select
                    id="track"
                    name="track"
                    required
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;"
                >
                    <option value="">Select Track</option>

                    <option value="tech"
                        {{ old('track', $category->track) === 'tech' ? 'selected' : '' }}>
                        Tech
                    </option>

                    <option value="business"
                        {{ old('track', $category->track) === 'business' ? 'selected' : '' }}>
                        Business
                    </option>
                </select>

                @error('track')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Sort Order -->
            <div style="margin-bottom:20px;">
                <label for="sort_order"
                       style="display:block; margin-bottom:8px; font-weight:600;">
                    Sort Order *
                </label>

                <input
                    type="number"
                    id="sort_order"
                    name="sort_order"
                    value="{{ old('sort_order', $category->sort_order) }}"
                    min="0"
                    required
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;"
                >

                @error('sort_order')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Description -->
            <div style="margin-bottom:20px;">
                <label for="description"
                       style="display:block; margin-bottom:8px; font-weight:600;">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; resize:vertical;"
                >{{ old('description', $category->description) }}</textarea>

                @error('description')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Image -->
            <div style="margin-bottom:20px;">
                <label for="image"
                       style="display:block; margin-bottom:8px; font-weight:600;">
                    Image URL
                </label>

                <input
                    type="text"
                    id="image"
                    name="image"
                    value="{{ old('image', $category->image) }}"
                    maxlength="255"
                    placeholder="Enter image URL"
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;"
                >

                @error('image')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Status -->
            <div style="margin-bottom:25px;">
                <label for="is_active"
                       style="display:block; margin-bottom:8px; font-weight:600;">
                    Category Status
                </label>

                <select
                    id="is_active"
                    name="is_active"
                    required
                    style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;"
                >
                    <option value="1"
                        {{ (string) old('is_active', (int) $category->is_active) === '1' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ (string) old('is_active', (int) $category->is_active) === '0' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>

                @error('is_active')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Actions -->
            <div style="display:flex; gap:12px; align-items:center;">

                <button type="submit"
                        class="category-add-button"
                        style="border:none; cursor:pointer;">
                    Save Changes
                </button>

                <a href="{{ route('admin.categories.index') }}"
                   class="category-edit-button">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</section>

@endsection
