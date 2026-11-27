<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $publishedContents = Content::where('status', 'published')
            ->orderByDesc('published_at')
            ->get();

        $user = Auth::user();

        // Courses the learner is enrolled in, with progress.
        $continueLearning = $user->courseEnrollments()
            ->with(['content' => fn ($q) => $q->withCount('lessons')])
            ->latest('enrolled_at')
            ->limit(4)
            ->get()
            ->filter(fn ($e) => $e->content && $e->content->status !== 'draft')
            ->map(function ($e) use ($user) {
                $total = $e->content->lessons_count;
                $done = $total
                    ? $user->lessonProgress()->whereNotNull('completed_at')
                        ->whereIn('lesson_id', $e->content->lessons()->pluck('id'))->count()
                    : 0;

                return (object) [
                    'content' => $e->content,
                    'done' => $done,
                    'total' => $total,
                    'percent' => $total ? (int) round($done / $total * 100) : 0,
                ];
            })->values();

        return view('dashboard.index', [
            'user' => $user,
            'continueLearning' => $continueLearning,
            'certificatesCount' => $user->certificates()->count(),

            // Technical courses
            'techContents' => $publishedContents
                ->where('track', 'tech')
                ->values(),

            // Business courses
            'businessContents' => $publishedContents
                ->where('track', 'business')
                ->values(),

            // Standalone podcasts
            'podcasts' => $publishedContents
                ->whereNull('track')
                ->where('type', 'podcast')
                ->values(),
        ]);
    }
}
