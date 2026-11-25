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

        return view('dashboard.index', [
            'user' => Auth::user(),

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
