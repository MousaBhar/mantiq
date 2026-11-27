<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonPreviewAndSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function paidCourse(): Content
    {
        return Content::create([
            'type' => 'course', 'track' => 'tech', 'title' => 'Advanced Laravel',
            'description' => 'Deep dive', 'price' => 99, 'status' => 'published',
        ]);
    }

    public function test_free_preview_lesson_is_open_in_a_paid_course_but_other_lessons_are_not(): void
    {
        $user = User::factory()->create();
        $course = $this->paidCourse();

        $preview = Lesson::create([
            'content_id' => $course->id, 'title' => 'Preview Lesson', 'sort_order' => 1,
            'is_preview' => true, 'video_url' => 'https://youtu.be/RGOj5yH7evk',
            'body' => "## Hello\n\n<script>alert(1)</script>",
        ]);
        $locked = Lesson::create(['content_id' => $course->id, 'title' => 'Locked Lesson', 'sort_order' => 2]);

        $this->actingAs($user)->get(route('learning.lessons.show', $preview))
            ->assertOk()
            ->assertSee('youtube-nocookie.com/embed/RGOj5yH7evk', false)
            ->assertSee('<h2>Hello</h2>', false)
            ->assertDontSee('<script>alert(1)</script>', false);

        $this->actingAs($user)->get(route('learning.lessons.show', $locked))->assertForbidden();
    }

    public function test_search_finds_courses_and_lessons(): void
    {
        $course = $this->paidCourse();
        Lesson::create(['content_id' => $course->id, 'title' => 'Eloquent relations', 'sort_order' => 1]);

        $this->get(route('search', ['q' => 'laravel']))->assertOk()->assertSee('Advanced Laravel');
        $this->get(route('search', ['q' => 'eloquent']))->assertOk()->assertSee('Eloquent relations');
    }

    public function test_public_catalog_api(): void
    {
        $this->paidCourse();

        $this->getJson('/api/contents')->assertOk()->assertJsonPath('data.0.title', 'Advanced Laravel');
    }

    public function test_security_headers_are_present(): void
    {
        $this->get(route('login'))->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}
