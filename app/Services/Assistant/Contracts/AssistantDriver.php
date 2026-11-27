<?php

namespace App\Services\Assistant\Contracts;

interface AssistantDriver
{
    /**
     * Produce the assistant's reply.
     *
     * @param  string  $system    System prompt (persona + grounded context).
     * @param  array<int, array{role: string, content: string}>  $messages
     *         Conversation so far; always starts and ends with a "user" turn.
     * @param  array<int, array{title: string, url: string}>  $sources
     *         Relevant catalog entries (used by the local driver).
     */
    public function reply(string $system, array $messages, array $sources = []): string;
}
