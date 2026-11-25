<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a test course.
     */
    private function createCourse(
        float $price = 0,
        string $status = 'published'
    ): Content {
        return Content::create([
            'type' => 'course',
            'track' => 'tech',
            'title' => 'Test Course',
            'price' => $price,
            'status' => $status,
        ]);
    }

    /**
     * Authenticated users can enroll in free published courses.
     */
    public function test_user_can_enroll_in_free_published_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse();

        $response = $this->actingAs($user)
            ->post(route('learning.enroll', $course));

        $response->assertRedirect(route('learning.show', $course));

        $this->assertDatabaseHas('course_enrollments', [
            'user_id' => $user->id,
            'content_id' => $course->id,
        ]);
    }

    /**
     * Users cannot self-enroll in paid courses.
     */
    public function test_user_cannot_enroll_in_paid_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(price: 100);

        $response = $this->actingAs($user)
            ->post(route('learning.enroll', $course));

        $response->assertForbidden();

        $this->assertDatabaseMissing('course_enrollments', [
            'user_id' => $user->id,
            'content_id' => $course->id,
        ]);
    }

    /**
     * Users cannot enroll in archived courses.
     */
    public function test_user_cannot_enroll_in_archived_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(
            price: 0,
            status: 'archived'
        );

        $response = $this->actingAs($user)
            ->post(route('learning.enroll', $course));

        $response->assertNotFound();

        $this->assertDatabaseMissing('course_enrollments', [
            'user_id' => $user->id,
            'content_id' => $course->id,
        ]);
    }

    /**
     * Repeated enrollment requests must not create duplicate records.
     */
    public function test_user_cannot_create_duplicate_enrollment(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse();

        $this->actingAs($user)
            ->post(route('learning.enroll', $course));

        $this->actingAs($user)
            ->post(route('learning.enroll', $course));

        $this->assertDatabaseCount('course_enrollments', 1);
    }
}
