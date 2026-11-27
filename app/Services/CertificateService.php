<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Content;
use App\Models\User;
use Illuminate\Support\Str;

class CertificateService
{
    /**
     * Issue a certificate once every lesson of the course is completed.
     * Safe to call repeatedly: a user gets at most one certificate per course.
     */
    public function issueIfCompleted(User $user, Content $content): ?Certificate
    {
        $lessonIds = $content->lessons()->pluck('id');

        if ($lessonIds->isEmpty()) {
            return null;
        }

        $completed = $user->lessonProgress()
            ->whereIn('lesson_id', $lessonIds)
            ->whereNotNull('completed_at')
            ->count();

        if ($completed < $lessonIds->count()) {
            return null;
        }

        return Certificate::firstOrCreate(
            ['user_id' => $user->id, 'content_id' => $content->id],
            ['code' => $this->newCode(), 'issued_at' => now()]
        );
    }

    private function newCode(): string
    {
        do {
            $code = 'MNQ-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (Certificate::where('code', $code)->exists());

        return $code;
    }
}
