<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseEnrollmentController extends Controller
{
    /**
     * Enroll an authenticated user in a free published course.
     */
    public function enroll(Request $request, Content $content): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user, 401);

        // Only published courses can accept new enrollments.
        abort_unless(
            $content->type === 'course' &&
            $content->status === 'published',
            404
        );

        // Self-enrollment is allowed only for free courses.
        abort_if(
            (float) $content->price > 0,
            403,
            'Self-enrollment is not allowed for paid courses.'
        );

        // Create enrollment only if it does not already exist.
        $enrollment = $user->courseEnrollments()->firstOrCreate(
            [
                'content_id' => $content->id,
            ],
            [
                'enrolled_at' => now(),
            ]
        );

        $message = $enrollment->wasRecentlyCreated
            ? 'You have successfully enrolled in this course.'
            : 'You are already enrolled in this course.';

        return redirect()
            ->route('learning.show', $content)
            ->with('success', $message);
    }
}
