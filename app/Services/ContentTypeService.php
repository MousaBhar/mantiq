<?php

namespace App\Services;

class ContentTypeService
{
    /**
     * Check whether a content type is allowed for a track.
     */
    public function isAllowed(?string $track, string $type): bool
    {
        $standaloneTypes = config('mantiq.standalone_types', []);

        // Standalone content must not belong to a track.
        if (in_array($type, $standaloneTypes, true)) {
            return $track === null;
        }

        // Regular content must belong to a valid track.
        if ($track === null) {
            return false;
        }

        $allowedTypes = config("mantiq.tracks.{$track}", []);

        return in_array($type, $allowedTypes, true);
    }

    /**
     * Get allowed content types for a track.
     */
    public function getAllowedTypes(?string $track): array
    {
        if ($track === null) {
            return config('mantiq.standalone_types', []);
        }

        return config("mantiq.tracks.{$track}", []);
    }

    /**
     * Get all available tracks.
     */
    public function getTracks(): array
    {
        return array_keys(config('mantiq.tracks', []));
    }

    /**
     * Get standalone content types.
     */
    public function getStandaloneTypes(): array
    {
        return config('mantiq.standalone_types', []);
    }
}
