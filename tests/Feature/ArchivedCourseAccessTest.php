<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchivedCourseAccessTest extends TestCase
{
    use RefreshDatabase;

    private function createArchivedCourse(): Content
    {
        return Content::create([
            'type' => 'course',
            'track' => 'tech',
            'title' => 'Archived Test Course',
            'price' => 0,
            'status' => 'archived',
        ]);
    }

    public function test_guest_cannot_open_archived_course(): void
    {
        $course = $this->createArchivedCourse();

        $response = $this->get(route('learning.show', $course));

        $response->assertNotFound();
    }

    public function test_unenrolled_user_cannot_open_archived_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createArchivedCourse();

        $response = $this->actingAs($user)
            ->get(route('learning.show', $course));

        $response->assertNotFound();
    }

    public function test_previously_enrolled_user_can_open_archived_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createArchivedCourse();

        $user->courseEnrollments()->create([
            'content_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get(route('learning.show', $course));

        $response->assertOk();
        $response->assertSee('Archived Test Course');
    }
}
