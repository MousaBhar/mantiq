<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a test course.
     */
    private function createCourse(float $price = 0): Content
    {
        return Content::create([
            'type' => 'course',
            'track' => 'tech',
            'title' => 'Test Course',
            'price' => $price,
            'status' => 'published',
        ]);
    }

    /**
     * Create a lesson for the given course.
     */
    private function createLesson(Content $course): Lesson
    {
        return Lesson::create([
            'content_id' => $course->id,
            'title' => 'Test Lesson',
            'description' => 'Lesson description',
            'sort_order' => 1,
            'media_url' => 'https://example.com/lesson',
            'duration_minutes' => 10,
        ]);
    }

    public function test_authenticated_user_can_open_lesson_in_free_course(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);

        $response = $this->actingAs($user)
            ->get(route('learning.lessons.show', $lesson));

        $response->assertOk();
        $response->assertSee('Test Lesson');
        $response->assertSee('https://example.com/lesson');
    }

    public function test_user_cannot_open_paid_course_lesson_without_enrollment(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(price: 100);
        $lesson = $this->createLesson($course);

        $response = $this->actingAs($user)
            ->get(route('learning.lessons.show', $lesson));

        $response->assertForbidden();
        $response->assertDontSee('https://example.com/lesson');
    }

    public function test_enrolled_user_can_open_paid_course_lesson(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(price: 100);
        $lesson = $this->createLesson($course);

        $user->courseEnrollments()->create([
            'content_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get(route('learning.lessons.show', $lesson));

        $response->assertOk();
        $response->assertSee('Test Lesson');
        $response->assertSee('https://example.com/lesson');
    }

    public function test_user_can_mark_accessible_lesson_as_completed(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);

        $response = $this->actingAs($user)
            ->post(route('learning.lessons.complete', $lesson));

        $response->assertRedirect(
            route('learning.lessons.show', $lesson)
        );

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);
    }

    public function test_user_cannot_complete_lesson_in_paid_course_without_enrollment(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse(price: 100);
        $lesson = $this->createLesson($course);

        $response = $this->actingAs($user)
            ->post(route('learning.lessons.complete', $lesson));

        $response->assertForbidden();

        $this->assertDatabaseMissing('lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);
    }

    public function test_completing_same_lesson_twice_does_not_create_duplicate_progress(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);

        $this->actingAs($user)
            ->post(route('learning.lessons.complete', $lesson));

        $this->actingAs($user)
            ->post(route('learning.lessons.complete', $lesson));

        $this->assertDatabaseCount('lesson_progress', 1);
    }

    /**
     * A user can see course progress without formal enrollment
     * after completing a lesson in a free course.
     */
    public function test_user_can_see_progress_in_free_course_without_enrollment(): void
    {
        $user = User::factory()->create();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);

        // Complete the lesson.
        $this->actingAs($user)
            ->post(route('learning.lessons.complete', $lesson));

        // Verify that no formal enrollment was created.
        $this->assertDatabaseMissing('course_enrollments', [
            'user_id' => $user->id,
            'content_id' => $course->id,
        ]);

        // Open the course details page.
        $response = $this->actingAs($user)
            ->get(route('learning.show', $course));

        $response->assertOk();

        // Verify course progress is displayed correctly.
        $response->assertSee('Course Progress');
        $response->assertSee('100%');
        $response->assertSee('1 of 1');
        $response->assertSee('✓ Completed');
    }
}
