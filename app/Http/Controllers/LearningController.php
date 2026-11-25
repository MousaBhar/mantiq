<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\ContentAccess;
use Illuminate\View\View;

class LearningController extends Controller
{
    /**
     * Display published courses for a specific track.
     */
    public function index(string $track): View
    {
        $contents = Content::where('status', 'published')
            ->where('track', $track)
            ->with('category')
            ->withCount('lessons')
            ->orderByDesc('published_at')
            ->get();

        return view('learning.index', [
            'contents' => $contents,
            'track' => $track,
        ]);
    }

    /**
     * Display published podcasts.
     */
    public function podcasts(): View
    {
        $podcasts = Content::where('status', 'published')
            ->whereNull('track')
            ->where('type', 'podcast')
            ->orderByDesc('published_at')
            ->get();

        return view('learning.podcasts', [
            'podcasts' => $podcasts,
        ]);
    }

    /**
     * Display course details and lesson titles.
     */
    public function show(
        Content $content,
        ContentAccess $contentAccess
    ): View {
        $user = auth()->user();

        abort_unless($content->type === 'course', 404);

        if ($content->status === 'archived') {
            abort_unless(
                $contentAccess->canView($user, $content),
                404
            );
        } else {
            abort_unless($content->status === 'published', 404);
        }

        $content->load([
            'category',
            'lessons' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);

        $isEnrolled = $user
            ? $user->courseEnrollments()
                ->where('content_id', $content->id)
                ->exists()
            : false;

        $canView = $contentAccess->canView($user, $content);

        $totalLessons = $content->lessons->count();

        $completedLessons = 0;

        if ($user && $canView && $totalLessons > 0) {
            $completedLessons = LessonProgress::where(
                'user_id',
                $user->id
            )
                ->whereIn('lesson_id', $content->lessons->pluck('id'))
                ->whereNotNull('completed_at')
                ->count();
        }

        $progressPercentage = $totalLessons > 0
            ? (int) round(($completedLessons / $totalLessons) * 100)
            : 0;

        $completedLessonIds = ($user && $canView)
            ? $user->lessonProgress()
                ->whereNotNull('completed_at')
                ->whereIn('lesson_id', $content->lessons->pluck('id'))
                ->pluck('lesson_id')
            : collect();

        return view('learning.show', [
            'content' => $content,
            'isEnrolled' => $isEnrolled,
            'canView' => $canView,
            'totalLessons' => $totalLessons,
            'completedLessons' => $completedLessons,
            'progressPercentage' => $progressPercentage,
            'completedLessonIds' => $completedLessonIds,
        ]);
    }

    /**
     * Display a lesson only if the user has access to its course.
     */
    public function lesson(
        Lesson $lesson,
        ContentAccess $contentAccess
    ): View {
        $lesson->load('content');

        abort_unless($lesson->content, 404);

        abort_unless(
            $contentAccess->canView(auth()->user(), $lesson->content),
            403
        );

        $isCompleted = $lesson->progressRecords()
            ->where('user_id', auth()->id())
            ->whereNotNull('completed_at')
            ->exists();

        return view('learning.lesson', [
            'lesson' => $lesson,
            'isCompleted' => $isCompleted,
        ]);
    }
}
