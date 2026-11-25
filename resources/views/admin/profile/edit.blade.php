@extends('admin.layouts.app')

@section('title', 'Mantiq - Profile Settings')

@section('admin-content')

    <!-- Header -->
    <header class="admin-header">

        <div>
            <h1>Profile Settings</h1>
            <p>Manage your personal information and profile picture.</p>
        </div>

        <div class="admin-header-actions">
            <div class="admin-profile">

                @if ($user->avatar)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar) }}"
                        alt="Profile picture"
                        class="profile-avatar-image"
                    >
                @else
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <strong>{{ $user->name }}</strong>
                    <span>Administrator</span>
                </div>

            </div>
        </div>

    </header>

    <!-- Success Message -->
    @if (session('success'))
        <div class="category-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="category-errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Information -->
    <section class="management-section">

        <div class="section-heading">
            <div>
                <h2>Personal Information</h2>
                <p>Update your name and contact information.</p>
            </div>
        </div>

        <div class="management-card profile-card">

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="profile-input"
                        value="{{ old('name', $user->name) }}"
                        required
                        maxlength="255"
                    >
                    @error('name')
                        <p class="profile-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        class="profile-input"
                        value="{{ $user->email }}"
                        readonly
                        disabled
                    >
                    <small class="profile-help-text">
                        Email address cannot be changed from this page.
                    </small>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        class="profile-input"
                        value="{{ old('phone', $user->phone) }}"
                        maxlength="30"
                        placeholder="Enter your phone number"
                    >
                    @error('phone')
                        <p class="profile-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-form-actions">
                    <button type="submit" class="category-save-button">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>

    </section>

    <!-- Profile Picture -->
    <section class="management-section">

        <div class="section-heading">
            <div>
                <h2>Profile Picture</h2>
                <p>Upload a new picture for your account.</p>
            </div>
        </div>

        <div class="management-card profile-card">

            <div class="profile-avatar-section">

                @if ($user->avatar)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar) }}"
                        alt="Current profile picture"
                        class="profile-avatar-large"
                    >
                @else
                    <div class="profile-avatar profile-avatar-large">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <strong>{{ $user->name }}</strong>
                    <p>JPG, JPEG, PNG or WEBP. Maximum size: 2 MB.</p>
                </div>

            </div>

            <form
                action="{{ route('admin.profile.avatar.update') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="form-group">
                    <label for="avatar">Choose Profile Picture</label>
                    <input
                        type="file"
                        id="avatar"
                        name="avatar"
                        class="profile-input"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        required
                    >
                    @error('avatar')
                        <p class="profile-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-form-actions">
                    <button type="submit" class="category-save-button">
                        Upload Picture
                    </button>
                </div>

            </form>

        </div>

    </section>

@endsection
