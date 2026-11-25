<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\User;
use App\Services\ContentAccess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentAccessTest extends TestCase
{
    use RefreshDatabase;

    private ContentAccess $contentAccess;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contentAccess = app(ContentAccess::class);
    }

    public function test_guest_cannot_access_free_published_course(): void
    {
        $course = $this->createCourse();

        $this->assertFalse(
            $this->contentAccess->canView(null, $course)
        );
    }

    public function test_authenticated_user_can_access_free_published_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse();

        $this->assertTrue(
            $this->contentAccess->canView($user, $course)
        );
    }

    public function test_unenrolled_user_cannot_access_paid_published_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(price: 100);

        $this->assertFalse(
            $this->contentAccess->canView($user, $course)
        );
    }

    public function test_enrolled_user_can_access_paid_published_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(price: 100);

        $user->courseEnrollments()->create([
            'content_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        $this->assertTrue(
            $this->contentAccess->canView($user, $course)
        );
    }

    public function test_draft_course_cannot_be_accessed(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(status: 'draft');

        $this->assertFalse(
            $this->contentAccess->canView($user, $course)
        );
    }

    public function test_previously_enrolled_user_can_access_archived_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(
            price: 100,
            status: 'archived'
        );

        $user->courseEnrollments()->create([
            'content_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        $this->assertTrue(
            $this->contentAccess->canView($user, $course)
        );
    }

    public function test_unenrolled_user_cannot_access_archived_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(
            price: 100,
            status: 'archived'
        );

        $this->assertFalse(
            $this->contentAccess->canView($user, $course)
        );
    }

    private function createCourse(
        float $price = 0,
        string $status = 'published'
    ): Content {
        return Content::create([
            'type' => 'course',
            'title' => 'Test Course',
            'price' => $price,
            'status' => $status,
        ]);
    }
}
