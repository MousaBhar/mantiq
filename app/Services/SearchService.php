<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Lesson;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SearchService
{
    /**
     * Search published courses and their lessons.
     *
     * @return array{contents: Collection, lessons: Collection}
     */
    public function search(string $query, int $limit = 8): array
    {
        $tokens = $this->tokens($query);

        if ($tokens === []) {
            return ['contents' => collect(), 'lessons' => collect()];
        }

        $contents = Content::where('status', 'published')
            ->where('type', 'course')
            ->where(function ($q) use ($tokens) {
                foreach ($tokens as $t) {
                    $like = '%' . $this->escapeLike($t) . '%';
                    $q->orWhere('title', 'like', $like)
                        ->orWhere('description', 'like', $like);
                }
            })
            ->limit(40)
            ->get()
            ->map(fn (Content $c) => tap($c, fn ($c) => $c->setAttribute(
                'score',
                $this->score($tokens, $c->title, $c->description)
            )))
            ->sortByDesc('score')
            ->take($limit)
            ->values();

        $lessons = Lesson::query()
            ->whereHas('content', fn ($q) => $q->where('status', 'published')->where('type', 'course'))
            ->with('content:id,title')
            ->where(function ($q) use ($tokens) {
                foreach ($tokens as $t) {
                    $like = '%' . $this->escapeLike($t) . '%';
                    $q->orWhere('title', 'like', $like)
                        ->orWhere('description', 'like', $like);
                }
            })
            ->limit(40)
            ->get()
            ->map(fn (Lesson $l) => tap($l, fn ($l) => $l->setAttribute(
                'score',
                $this->score($tokens, $l->title, $l->description)
            )))
            ->sortByDesc('score')
            ->take($limit)
            ->values();

        return ['contents' => $contents, 'lessons' => $lessons];
    }

    /**
     * Split a query into meaningful lowercase tokens.
     *
     * @return array<int, string>
     */
    public function tokens(string $query): array
    {
        $stop = ['من', 'في', 'على', 'الى', 'إلى', 'عن', 'ما', 'هو', 'هي', 'كيف', 'ماذا', 'the', 'and', 'for', 'how', 'what', 'with'];

        return collect(preg_split('/[\s\p{P}]+/u', Str::lower($query), -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn ($t) => mb_strlen($t) >= 2 && !in_array($t, $stop, true))
            ->unique()
            ->take(6)
            ->values()
            ->all();
    }

    private function score(array $tokens, ?string $title, ?string $description): int
    {
        $title = Str::lower((string) $title);
        $description = Str::lower((string) $description);
        $score = 0;

        foreach ($tokens as $t) {
            $score += str_contains($title, $t) ? 3 : 0;
            $score += str_contains($description, $t) ? 1 : 0;
        }

        return $score;
    }

    private function escapeLike(string $value): string
    {
        return addcslashes($value, '\\%_');
    }
}
