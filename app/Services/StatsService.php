<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Content;
use App\Models\User;

class StatsService
{
    public function getDashboardStats(): array
    {
        // Main statistics
        $totalUsers = User::count();

        $totalCategories = Category::count();

        $totalContent = Content::count();

        $publishedContent = Content::where('status', 'published')->count();

        $draftContent = Content::where('status', 'draft')->count();

        $archivedContent = Content::where('status', 'archived')->count();

        // Content distribution
        $techContent = Content::where('track', 'tech')->count();

        $businessContent = Content::where('track', 'business')->count();

        $podcastContent = Content::whereNull('track')
            ->where('type', 'podcast')
            ->count();

        // Latest users
        $recentUsers = User::latest()
            ->take(5)
            ->get(['name', 'created_at'])
            ->map(function ($user) {
                return [
                    'title' => 'New User Registered',
                    'description' => $user->name . ' joined the platform.',
                    'icon' => '♙',
                    'color' => 'purple',
                    'date' => $user->created_at,
                ];
            });

        // Latest content
        $recentContents = Content::latest()
            ->take(5)
            ->get(['title', 'type', 'created_at'])
            ->map(function ($content) {
                return [
                    'title' => 'New Content Added',
                    'description' => $content->title . ' (' . ucfirst($content->type) . ')',
                    'icon' => '▣',
                    'color' => 'blue',
                    'date' => $content->created_at,
                ];
            });

        // Latest categories
        $recentCategories = Category::latest()
            ->take(5)
            ->get(['name', 'created_at'])
            ->map(function ($category) {
                return [
                    'title' => 'New Category Created',
                    'description' => $category->name . ' was added.',
                    'icon' => '▤',
                    'color' => 'pink',
                    'date' => $category->created_at,
                ];
            });

        // Combine and sort recent activity
        $recentActivity = collect()
            ->concat($recentUsers)
            ->concat($recentContents)
            ->concat($recentCategories)
            ->sortByDesc('date')
            ->take(8)
            ->values();

        return [
            'totalUsers' => $totalUsers,
            'totalCategories' => $totalCategories,
            'totalContent' => $totalContent,
            'publishedContent' => $publishedContent,
            'draftContent' => $draftContent,
            'archivedContent' => $archivedContent,

            'techContent' => $techContent,
            'businessContent' => $businessContent,
            'podcastContent' => $podcastContent,

            'recentActivity' => $recentActivity,
        ];
    }
}
