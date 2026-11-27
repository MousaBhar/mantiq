<?php

namespace App\Services\Assistant\Drivers;

use App\Services\Assistant\AssistantException;
use App\Services\Assistant\Contracts\AssistantDriver;
use Illuminate\Support\Facades\Http;

class AnthropicDriver implements AssistantDriver
{
    public function reply(string $system, array $messages, array $sources = []): string
    {
        $cfg = config('assistant.anthropic');

        if (blank($cfg['key'])) {
            throw new AssistantException('ANTHROPIC_API_KEY is not configured.');
        }

        $response = Http::withHeaders([
            'x-api-key' => $cfg['key'],
            'anthropic-version' => $cfg['version'],
        ])
            ->timeout(config('assistant.timeout'))
            ->retry(1, 400, throw: false)
            ->post(rtrim($cfg['base_url'], '/') . '/v1/messages', [
                'model' => $cfg['model'],
                'max_tokens' => config('assistant.max_tokens'),
                'system' => $system,
                'messages' => $messages,
            ]);

        if ($response->failed()) {
            throw new AssistantException('Provider returned HTTP ' . $response->status());
        }

        $text = collect($response->json('content', []))
            ->where('type', 'text')
            ->pluck('text')
            ->implode("\n");

        if (blank($text)) {
            throw new AssistantException('Provider returned an empty answer.');
        }

        return trim($text);
    }
}
