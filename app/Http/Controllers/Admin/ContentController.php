<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use App\Services\ContentTypeService;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of the contents.
     */
    public function index(Request $request)
    {
        $contents = Content::with(['category', 'creator'])
            ->when($request->filled('track'), function ($query) use ($request) {

                // Podcast Library contains standalone podcasts.
                if ($request->track === 'podcast_library') {
                    $query->whereNull('track')
                        ->where('type', 'podcast');

                    return;
                }

                // Regular content tracks.
                $query->where('track', $request->track);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.contents.index', [
            'contents' => $contents,

            // Include Podcast Library as a standalone filter.
            'tracks' => [
                'tech',
                'business',
                'podcast_library',
            ],

            'statuses' => ['draft', 'published', 'archived'],
        ]);
    }

    /**
     * Show the form for creating a new content.
     */
    public function create(ContentTypeService $contentTypeService)
    {
        $tracks = $contentTypeService->getTracks();

        $trackTypes = config('mantiq.tracks', []);

        $standaloneTypes = $contentTypeService->getStandaloneTypes();

        $availableCategories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'track']);

        return view('admin.contents.create', compact(
            'tracks',
            'trackTypes',
            'standaloneTypes',
            'availableCategories'
        ));
    }

    /**
     * Store a newly created content.
     */
    public function store(
        Request $request,
        ContentTypeService $contentTypeService
    ) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'track' => ['nullable', 'in:tech,business'],
            'type' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['nullable', 'boolean'],
            'level' => ['nullable', 'string', 'max:100'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'read_minutes' => ['nullable', 'integer', 'min:1'],
            'cover' => ['nullable', 'string', 'max:255'],
            'media_url' => ['nullable', 'url', 'max:2048'],
            'file_path' => ['nullable', 'string', 'max:255'],
        ]);

        $track = $validated['track'] ?? null;

        // Validate the selected content type.
        if (!$contentTypeService->isAllowed($track, $validated['type'])) {
            return back()
                ->withErrors([
                    'type' => 'The selected content type is not available for this track.',
                ])
                ->withInput();
        }

        // Validate category only for regular tracks.
        if (!empty($validated['category_id'])) {
            $categoryExists = $track !== null
                && Category::where('id', $validated['category_id'])
                    ->where('track', $track)
                    ->where('is_active', true)
                    ->exists();

            if (!$categoryExists) {
                return back()
                    ->withErrors([
                        'category_id' => 'The selected category does not belong to this track or is inactive.',
                    ])
                    ->withInput();
            }
        }

        $validated = $this->clearIrrelevantFields($validated);

        $validated['track'] = $track;
        $validated['created_by'] = auth()->id();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['views_count'] = 0;
        $validated['price'] = $validated['price'] ?? 0;

        $validated['published_at'] = $validated['status'] === 'published'
            ? now()
            : null;

        Content::create($validated);

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content created successfully.');
    }

    /**
     * Display the specified content.
     */
    public function show(Content $content)
    {
        $content->load([
            'category',
            'creator',
            'lessons' => function ($query) {
                $query->orderBy('sort_order')
                    ->orderBy('id');
            },
            'articleDetail',
        ]);

        return view('admin.contents.show', compact('content'));
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(
        Content $content,
        ContentTypeService $contentTypeService
    ) {
        $tracks = $contentTypeService->getTracks();

        $trackTypes = config('mantiq.tracks', []);

        $standaloneTypes = $contentTypeService->getStandaloneTypes();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'track']);

        return view('admin.contents.edit', compact(
            'content',
            'tracks',
            'trackTypes',
            'standaloneTypes',
            'categories'
        ));
    }

    /**
     * Update the specified content.
     */
    public function update(
        Request $request,
        Content $content,
        ContentTypeService $contentTypeService
    ) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'track' => ['nullable', 'in:tech,business'],
            'type' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['nullable', 'boolean'],
            'level' => ['nullable', 'string', 'max:100'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'read_minutes' => ['nullable', 'integer', 'min:1'],
            'cover' => ['nullable', 'string', 'max:255'],
            'media_url' => ['nullable', 'url', 'max:2048'],
            'file_path' => ['nullable', 'string', 'max:255'],
        ]);

        $track = $validated['track'] ?? null;

        // Validate the selected content type.
        if (!$contentTypeService->isAllowed($track, $validated['type'])) {
            return back()
                ->withErrors([
                    'type' => 'The selected content type is not available for this track.',
                ])
                ->withInput();
        }

        // Validate the selected category.
        if (!empty($validated['category_id'])) {
            $categoryExists = $track !== null
                && Category::where('id', $validated['category_id'])
                    ->where('track', $track)
                    ->where('is_active', true)
                    ->exists();

            if (!$categoryExists) {
                return back()
                    ->withErrors([
                        'category_id' => 'The selected category does not belong to this track or is inactive.',
                    ])
                    ->withInput();
            }
        }

        $validated = $this->clearIrrelevantFields($validated);

        $validated['track'] = $track;

        $validated['published_at'] = $validated['status'] === 'published'
            ? ($content->published_at ?? now())
            : null;

        $validated['price'] = $validated['price'] ?? 0;
        $validated['is_featured'] = $request->boolean('is_featured');

        $content->update($validated);

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content updated successfully.');
    }

    /**
     * Clear fields that do not belong to the selected content type.
     */
    private function clearIrrelevantFields(array $validated): array
    {
        // Level is only used for courses.
        if ($validated['type'] !== 'course') {
            $validated['level'] = null;
        }

        // Duration is used for courses and podcasts.
        if (!in_array($validated['type'], ['course', 'podcast'], true)) {
            $validated['duration_minutes'] = null;
        }

        // Pages is only used for books.
        if ($validated['type'] !== 'book') {
            $validated['pages'] = null;
        }

        // Reading time is used for books and articles.
        if (!in_array($validated['type'], ['book', 'article'], true)) {
            $validated['read_minutes'] = null;
        }

        return $validated;
    }

    /**
     * Remove the specified content.
     */
    public function destroy(Content $content)
    {
        $content->delete();

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content deleted successfully.');
    }
}
