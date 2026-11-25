<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Profile\UpdateAvatarRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the admin profile page.
     */
    public function edit(Request $request)
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the admin's basic profile information.
     */
    public function update(
        UpdateProfileRequest $request,
        ProfileService $profileService
    ): RedirectResponse {
        $data = $request->safe()->only([
            'name',
            'phone',
        ]);

        $profileService->updateProfile(
            $request->user(),
            $data
        );

        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }
    /**
     * Update the admin's profile avatar.
     */
    public function updateAvatar(
        UpdateAvatarRequest $request,
        ProfileService $profileService
    ): RedirectResponse {
        $profileService->updateAvatar(
            $request->user(),
            $request->file('avatar')
        );

        return back()->with(
            'success',
            'Profile picture updated successfully.'
        );
    }
}
