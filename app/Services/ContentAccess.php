<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Lesson;
use App\Models\User;

class ContentAccess
{
    /**
     * Determine whether a user can access content.
     */
    public function canView(?User $user, Content $content): bool
    {
        // Only courses are handled by this service.
        if ($content->type !== 'course') {
            return false;
        }

        // Draft courses are never accessible.
        if ($content->status === 'draft') {
            return false;
        }

        // Archived courses remain accessible only to existing enrollees.
        if ($content->status === 'archived') {
            return $user !== null
                && $this->isEnrolled($user, $content);
        }

        // Deny any other status that is not published.
        if ($content->status !== 'published') {
            return false;
        }

        // Guests cannot access course materials.
        if ($user === null) {
            return false;
        }

        // Free published courses are available to authenticated users.
        if ((float) $content->price <= 0) {
            return true;
        }

        // Paid courses require an existing enrollment.
        return $this->isEnrolled($user, $content);
    }

    /**
     * Determine whether a user can open a specific lesson.
     * Free preview lessons of published courses are open to signed-in users.
     */
    public function canViewLesson(?User $user, Lesson $lesson): bool
    {
        $content = $lesson->content;

        if (!$content) {
            return false;
        }

        if ($user && $lesson->is_preview
            && $content->type === 'course'
            && $content->status === 'published') {
            return true;
        }

        return $this->canView($user, $content);
    }

    /**
     * Check whether the user is enrolled in the course.
     */
    private function isEnrolled(User $user, Content $content): bool
    {
        return $user->courseEnrollments()
            ->where('content_id', $content->id)
            ->exists();
    }
}
