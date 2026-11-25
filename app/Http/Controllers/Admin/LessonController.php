<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    /**
     * Display a listing of lessons for a course.
     */
    public function index(Content $content)
    {
        abort_unless($content->type === 'course', 404);

        $lessons = $content->lessons()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10);

        return view('admin.lessons.index', compact(
            'content',
            'lessons'
        ));
    }

    /**
     * Show the form for creating a new lesson.
     */
    public function create(Content $content)
    {
        abort_unless($content->type === 'course', 404);

        $nextSortOrder = ($content->lessons()->max('sort_order') ?? 0) + 1;

        return view('admin.lessons.create', compact(
            'content',
            'nextSortOrder'
        ));
    }

    /**
     * Store a newly created lesson.
     */
    public function store(Request $request, Content $content)
    {
        abort_unless($content->type === 'course', 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'media_url' => ['nullable', 'url', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
        ]);

        $nextSortOrder = ($content->lessons()->max('sort_order') ?? 0) + 1;

        $validated['sort_order'] = $nextSortOrder;

        $content->lessons()->create($validated);

        return redirect()
            ->route('admin.contents.lessons.index', $content)
            ->with('success', 'Lesson created successfully.');
    }

    /**
     * Display the specified lesson.
     */
    public function show(Content $content, Lesson $lesson)
    {
        abort_unless($content->type === 'course', 404);
        abort_unless($lesson->content_id === $content->id, 404);

        return view('admin.lessons.show', compact(
            'content',
            'lesson'
        ));
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit(Content $content, Lesson $lesson)
    {
        abort_unless($content->type === 'course', 404);
        abort_unless($lesson->content_id === $content->id, 404);

        return view('admin.lessons.edit', compact(
            'content',
            'lesson'
        ));
    }

    /**
     * Update the specified lesson.
     */
    public function update(
        Request $request,
        Content $content,
        Lesson $lesson
    ) {
        abort_unless($content->type === 'course', 404);
        abort_unless($lesson->content_id === $content->id, 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'media_url' => ['nullable', 'url', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
        ]);

        $lesson->update($validated);

        return redirect()
            ->route('admin.contents.lessons.index', $content)
            ->with('success', 'Lesson updated successfully.');
    }

    /**
     * Remove the specified lesson and reorder remaining lessons.
     */
    public function destroy(Content $content, Lesson $lesson)
    {
        abort_unless($content->type === 'course', 404);
        abort_unless($lesson->content_id === $content->id, 404);

        DB::transaction(function () use ($content, $lesson) {

            // Delete the selected lesson.
            $lesson->delete();

            // Get remaining lessons in their current order.
            $lessons = $content->lessons()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            // Reassign sequential order numbers starting from 1.
            foreach ($lessons as $index => $remainingLesson) {
                $remainingLesson->update([
                    'sort_order' => $index + 1,
                ]);
            }
        });

        return redirect()
            ->route('admin.contents.lessons.index', $content)
            ->with('success', 'Lesson deleted and order updated successfully.');
    }
}
