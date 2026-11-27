<?php

namespace App\Services\Assistant\Drivers;

use App\Services\Assistant\AssistantException;
use App\Services\Assistant\Contracts\AssistantDriver;
use Illuminate\Support\Facades\Http;

class OpenAiDriver implements AssistantDriver
{
    public function reply(string $system, array $messages, array $sources = []): string
    {
        $cfg = config('assistant.openai');

        if (blank($cfg['key'])) {
            throw new AssistantException('OPENAI_API_KEY is not configured.');
        }

        $response = Http::withToken($cfg['key'])
            ->timeout(config('assistant.timeout'))
            ->retry(1, 400, throw: false)
            ->post(rtrim($cfg['base_url'], '/') . '/chat/completions', [
                'model' => $cfg['model'],
                'max_tokens' => config('assistant.max_tokens'),
                'messages' => array_merge(
                    [['role' => 'system', 'content' => $system]],
                    $messages
                ),
            ]);

        if ($response->failed()) {
            throw new AssistantException('Provider returned HTTP ' . $response->status());
        }

        $text = $response->json('choices.0.message.content');

        if (blank($text)) {
            throw new AssistantException('Provider returned an empty answer.');
        }

        return trim($text);
    }
}
