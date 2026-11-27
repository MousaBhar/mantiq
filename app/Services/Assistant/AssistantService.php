<?php

namespace App\Services\Assistant;

use App\Models\AssistantConversation;
use App\Models\Lesson;
use App\Models\User;
use App\Services\Assistant\Contracts\AssistantDriver;
use App\Services\Assistant\Drivers\AnthropicDriver;
use App\Services\Assistant\Drivers\LocalDriver;
use App\Services\Assistant\Drivers\OpenAiDriver;
use App\Services\SearchService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class AssistantService
{
    public function __construct(
        private ContextBuilder $context,
        private SearchService $search,
    ) {
    }

    /**
     * Answer a learner's message and persist the exchange.
     *
     * @return array{conversation: AssistantConversation, reply: string, sources: array}
     */
    public function ask(
        User $user,
        string $message,
        ?AssistantConversation $conversation = null,
        ?Lesson $lesson = null,
    ): array {
        $message = trim($message);

        $history = $conversation
            ? $conversation->messages()
                ->latest('id')
                ->limit(config('assistant.history_messages'))
                ->get(['role', 'content'])
                ->reverse()
                ->map(fn ($m) => ['role' => $m->role, 'content' => $m->content])
                ->values()
                ->all()
            : [];

        // Providers require the first turn to come from the user.
        while ($history !== [] && $history[0]['role'] !== 'user') {
            array_shift($history);
        }

        $messages = array_merge($history, [['role' => 'user', 'content' => $message]]);

        $sources = $this->sources($message);
        $system = $this->context->systemPrompt($user, $lesson);

        $reply = $this->generate($system, $messages, $sources);

        $conversation = DB::transaction(function () use ($user, $conversation, $lesson, $message, $reply) {
            $conversation ??= AssistantConversation::create([
                'user_id' => $user->id,
                'content_id' => $lesson?->content_id,
                'lesson_id' => $lesson?->id,
                'title' => Str::limit($message, 60, '…'),
            ]);

            $conversation->messages()->createMany([
                ['role' => 'user', 'content' => $message],
                ['role' => 'assistant', 'content' => $reply],
            ]);

            $conversation->touch();

            return $conversation;
        });

        return ['conversation' => $conversation, 'reply' => $reply, 'sources' => $sources];
    }

    /**
     * Relevant catalog entries to show under the answer.
     *
     * @return array<int, array{title: string, url: string}>
     */
    public function sources(string $message): array
    {
        $found = $this->search->search($message, 3);

        $items = $found['contents']->map(fn ($c) => [
            'title' => $c->title,
            'url' => route('learning.show', $c),
        ])->all();

        foreach ($found['lessons'] as $lesson) {
            $items[] = [
                'title' => $lesson->title . ' — ' . $lesson->content->title,
                'url' => route('learning.show', $lesson->content_id),
            ];
        }

        return array_slice($items, 0, 3);
    }

    private function generate(string $system, array $messages, array $sources): string
    {
        $driver = $this->driver();

        try {
            return $driver->reply($system, $messages, $sources);
        } catch (Throwable $e) {
            report($e);

            // Never leave the learner without an answer.
            return (new LocalDriver())->reply($system, $messages, $sources);
        }
    }

    private function driver(): AssistantDriver
    {
        return match (config('assistant.driver')) {
            'anthropic' => new AnthropicDriver(),
            'openai' => new OpenAiDriver(),
            default => new LocalDriver(),
        };
    }
}
