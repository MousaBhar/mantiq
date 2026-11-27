<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Assistant (Mantiq Mentor)
    |--------------------------------------------------------------------------
    | The in-app learning mentor. The "local" driver needs no external service
    | and answers from the platform's own catalog, so the feature always works
    | out of the box. Switch to "anthropic" or "openai" for generated answers.
    */

    'name' => env('ASSISTANT_NAME', 'مرشد منطق'),

    // local | anthropic | openai
    'driver' => env('ASSISTANT_DRIVER', 'local'),

    'timeout' => (int) env('ASSISTANT_TIMEOUT', 25),
    'max_tokens' => (int) env('ASSISTANT_MAX_TOKENS', 900),
    'max_input_chars' => (int) env('ASSISTANT_MAX_INPUT', 1500),
    'history_messages' => (int) env('ASSISTANT_HISTORY', 12),
    'rate_limit_per_minute' => (int) env('ASSISTANT_RATE_LIMIT', 20),

    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
        'model' => env('ASSISTANT_MODEL', 'claude-sonnet-5-5'),
        'base_url' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com'),
        'version' => '2023-06-01',
    ],

    // Any OpenAI-compatible endpoint (OpenAI, Gemini compat, Groq, Ollama...).
    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
    ],

];
