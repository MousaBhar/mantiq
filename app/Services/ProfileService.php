<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;
use RuntimeException;

class ProfileService
{
    /**
     * Update the user's basic profile information.
     */
    public function updateProfile(User $user, array $data): User
    {
        $attributes = [
            'name' => $data['name'],
        ];

        if (array_key_exists('phone', $data)) {
            $attributes['phone'] = $data['phone'];
        }

        $user->update($attributes);

        return $user->refresh();
    }

    /**
     * Update the user's profile avatar.
     */
    public function updateAvatar(User $user, UploadedFile $avatar): User
    {
        $disk = Storage::disk('public');

        $newAvatar = $avatar->store('avatars', 'public');

        if ($newAvatar === false) {
            throw new RuntimeException('Unable to store the avatar.');
        }

        $oldAvatar = $user->avatar;

        try {
            $user->update([
                'avatar' => $newAvatar,
            ]);
        } catch (Throwable $exception) {
            $disk->delete($newAvatar);

            throw $exception;
        }

        if ($oldAvatar) {
            $disk->delete($oldAvatar);
        }

        return $user->refresh();
    }
}
