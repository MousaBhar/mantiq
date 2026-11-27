<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Content;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function courseWithLessons(int $count = 2): array
    {
        $course = Content::create([
            'type' => 'course', 'track' => 'tech', 'title' => 'Git Course',
            'price' => 0, 'status' => 'published',
        ]);

        $lessons = collect(range(1, $count))->map(fn ($i) => Lesson::create([
            'content_id' => $course->id, 'title' => "Lesson {$i}", 'sort_order' => $i,
        ]));

        return [$course, $lessons];
    }

    public function test_certificate_is_issued_only_after_all_lessons_are_completed(): void
    {
        $user = User::factory()->create();
        [$course, $lessons] = $this->courseWithLessons(2);

        $this->actingAs($user)->post(route('learning.lessons.complete', $lessons[0]));
        $this->assertDatabaseCount('certificates', 0);

        $this->actingAs($user)->post(route('learning.lessons.complete', $lessons[1]));
        $this->assertDatabaseCount('certificates', 1);

        // Completing again never duplicates the certificate.
        $this->actingAs($user)->post(route('learning.lessons.complete', $lessons[1]));
        $this->assertDatabaseCount('certificates', 1);
    }

    public function test_owner_can_view_certificate_and_others_cannot(): void
    {
        $owner = User::factory()->create(['name' => 'Layla Ahmad']);
        $other = User::factory()->create();
        [$course] = $this->courseWithLessons(1);

        $certificate = Certificate::create([
            'user_id' => $owner->id, 'content_id' => $course->id,
            'code' => 'MNQ-TEST-0001', 'issued_at' => now(),
        ]);

        $this->actingAs($owner)->get(route('certificates.show', $certificate))
            ->assertOk()->assertSee('Layla Ahmad')->assertSee('Git Course');

        $this->actingAs($other)->get(route('certificates.show', $certificate))->assertForbidden();
    }

    public function test_public_verification_page(): void
    {
        $owner = User::factory()->create();
        [$course] = $this->courseWithLessons(1);

        Certificate::create([
            'user_id' => $owner->id, 'content_id' => $course->id,
            'code' => 'MNQ-ABCD-1234', 'issued_at' => now(),
        ]);

        $this->get(route('certificates.verify', 'mnq-abcd-1234'))
            ->assertOk()->assertSee('Valid certificate');

        $this->get(route('certificates.verify', 'MNQ-NOPE-0000'))
            ->assertOk()->assertSee('Certificate not found');
    }
}
