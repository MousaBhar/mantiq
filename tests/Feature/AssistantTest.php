<?php

namespace Tests\Feature;

use App\Models\AssistantConversation;
use App\Models\Content;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AssistantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['assistant.driver' => 'local']);
    }

    private function course(string $title = 'Laravel Basics', float $price = 0): Content
    {
        return Content::create([
            'type' => 'course', 'track' => 'tech', 'title' => $title,
            'description' => 'Learn Laravel step by step', 'price' => $price, 'status' => 'published',
        ]);
    }

    public function test_guest_cannot_use_the_assistant(): void
    {
        $this->postJson(route('assistant.send'), ['message' => 'hi'])->assertUnauthorized();
    }

    public function test_local_driver_answers_from_the_catalog_and_stores_the_chat(): void
    {
        $user = User::factory()->create();
        $this->course();

        $response = $this->actingAs($user)
            ->postJson(route('assistant.send'), ['message' => 'I want to learn Laravel'])
            ->assertOk()
            ->assertJsonPath('message.role', 'assistant')
            ->assertJsonPath('sources.0.title', 'Laravel Basics');

        $conversation = AssistantConversation::findOrFail($response->json('conversation_id'));
        $this->assertSame($user->id, $conversation->user_id);
        $this->assertCount(2, $conversation->messages);
    }

    public function test_message_is_required_and_length_limited(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('assistant.send'), ['message' => ''])
            ->assertStatus(422);

        $this->actingAs($user)
            ->postJson(route('assistant.send'), ['message' => str_repeat('a', 5000)])
            ->assertStatus(422);
    }

    public function test_user_cannot_read_or_continue_someone_elses_conversation(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $conversation = AssistantConversation::create(['user_id' => $owner->id, 'title' => 'private']);

        $this->actingAs($other)->getJson(route('assistant.show', $conversation->id))->assertNotFound();

        $this->actingAs($other)
            ->postJson(route('assistant.send'), ['message' => 'hi', 'conversation_id' => $conversation->id])
            ->assertNotFound();
    }

    public function test_anthropic_driver_is_called_with_catalog_context(): void
    {
        config(['assistant.driver' => 'anthropic', 'assistant.anthropic.key' => 'test-key']);
        Http::fake(['api.anthropic.com/*' => Http::response([
            'content' => [['type' => 'text', 'text' => 'جواب تجريبي']],
        ])]);

        $user = User::factory()->create();
        $this->course('Design Patterns 101');

        $this->actingAs($user)
            ->postJson(route('assistant.send'), ['message' => 'ما أفضل دورة للمبتدئين؟'])
            ->assertOk()
            ->assertJsonPath('message.content', 'جواب تجريبي');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('x-api-key', 'test-key')
                && str_contains($request['system'], 'Design Patterns 101')
                && $request['messages'][0]['role'] === 'user';
        });
    }

    public function test_it_falls_back_to_local_answers_when_the_provider_fails(): void
    {
        config(['assistant.driver' => 'anthropic', 'assistant.anthropic.key' => 'test-key']);
        Http::fake(['api.anthropic.com/*' => Http::response('boom', 500)]);

        $user = User::factory()->create();
        $this->course();

        $this->actingAs($user)
            ->postJson(route('assistant.send'), ['message' => 'Laravel'])
            ->assertOk()
            ->assertJsonPath('sources.0.title', 'Laravel Basics');
    }

    public function test_paid_lesson_content_is_not_sent_to_the_provider_without_access(): void
    {
        config(['assistant.driver' => 'anthropic', 'assistant.anthropic.key' => 'test-key']);
        Http::fake(['api.anthropic.com/*' => Http::response([
            'content' => [['type' => 'text', 'text' => 'ok']],
        ])]);

        $user = User::factory()->create();
        $paid = $this->course('Paid Course', 100);
        $lesson = Lesson::create([
            'content_id' => $paid->id, 'title' => 'Locked', 'sort_order' => 1,
            'body' => 'SECRET-LESSON-BODY',
        ]);

        $this->actingAs($user)
            ->postJson(route('assistant.send'), ['message' => 'explain', 'lesson_id' => $lesson->id])
            ->assertOk();

        Http::assertSent(fn (Request $r) => !str_contains($r['system'], 'SECRET-LESSON-BODY'));
    }
}
