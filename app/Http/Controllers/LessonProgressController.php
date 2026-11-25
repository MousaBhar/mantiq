<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Services\ContentAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LessonProgressController extends Controller
{
    /**
     * Mark a lesson as completed by the authenticated user.
     */
    public function complete(
        Request $request,
        Lesson $lesson,
        ContentAccess $contentAccess
    ): RedirectResponse {
        $user = $request->user();

        abort_unless($user, 401);

        // Load the parent content.
        $lesson->load('content');

        abort_unless($lesson->content, 404);

        // Check access using the central access service.
        abort_unless(
            $contentAccess->canView($user, $lesson->content),
            403
        );

        // Find or create the progress record.
        $progress = $user->lessonProgress()
            ->firstOrNew([
                'lesson_id' => $lesson->id,
            ]);

        // Save the completion date only if not already completed.
        if (!$progress->completed_at) {
            $progress->completed_at = now();
            $progress->save();
        }

        return redirect()
            ->route('learning.lessons.show', $lesson)
            ->with('success', 'Lesson marked as completed successfully.');
    }
}
