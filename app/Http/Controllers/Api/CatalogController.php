<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Services\ContentAccess;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Published courses, optionally filtered by track.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate(['track' => ['nullable', 'in:tech,business']]);

        $contents = Content::where('status', 'published')
            ->where('type', 'course')
            ->when($request->track, fn ($q, $track) => $q->where('track', $track))
            ->with('category:id,name,name_ar')
            ->withCount('lessons')
            ->orderByDesc('published_at')
            ->paginate(15);

        return response()->json($contents->through(fn ($c) => $this->card($c)));
    }

    /**
     * Course detail. Lesson media is only included when the user has access.
     */
    public function show(Request $request, Content $content, ContentAccess $access): JsonResponse
    {
        abort_unless($content->type === 'course' && $content->status === 'published', 404);

        $user = $request->user('sanctum');
        $canView = $access->canView($user, $content);

        $content->load('category:id,name,name_ar')->loadCount('lessons');

        return response()->json($this->card($content) + [
            'can_view' => $canView,
            'lessons' => $content->lessons()->orderBy('sort_order')->get()
                ->map(fn ($l) => [
                    'id' => $l->id,
                    'title' => $l->title,
                    'order' => $l->sort_order,
                    'duration_minutes' => $l->duration_minutes,
                    'is_preview' => $l->is_preview,
                    'video_url' => ($canView || $l->is_preview) ? $l->embed_url : null,
                ]),
        ]);
    }

    public function search(Request $request, SearchService $search): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        $found = $search->search(mb_substr($q, 0, 80), 10);

        return response()->json([
            'courses' => $found['contents']->map(fn ($c) => $this->card($c))->values(),
            'lessons' => $found['lessons']->map(fn ($l) => [
                'id' => $l->id,
                'title' => $l->title,
                'course_id' => $l->content_id,
                'course_title' => $l->content->title,
            ])->values(),
        ]);
    }

    /**
     * The signed-in user's enrolled courses with progress.
     */
    public function progress(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $user->courseEnrollments()
            ->with(['content' => fn ($q) => $q->withCount('lessons')])
            ->get()
            ->filter(fn ($e) => $e->content)
            ->map(function ($e) use ($user) {
                $total = $e->content->lessons_count;
                $done = $total
                    ? $user->lessonProgress()->whereNotNull('completed_at')
                        ->whereIn('lesson_id', $e->content->lessons()->pluck('id'))->count()
                    : 0;

                return [
                    'course_id' => $e->content_id,
                    'title' => $e->content->title,
                    'completed_lessons' => $done,
                    'total_lessons' => $total,
                    'percent' => $total ? (int) round($done / $total * 100) : 0,
                ];
            })->values();

        return response()->json($data);
    }

    private function card(Content $c): array
    {
        return [
            'id' => $c->id,
            'title' => $c->title,
            'description' => $c->description,
            'track' => $c->track,
            'level' => $c->level,
            'price' => (float) $c->price,
            'category' => $c->category?->name_ar ?? $c->category?->name,
            'lessons_count' => $c->lessons_count ?? null,
        ];
    }
}
