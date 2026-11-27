<?php

namespace App\Http\Controllers;

use App\Models\AssistantConversation;
use App\Models\Lesson;
use App\Services\Assistant\AssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Serves both the web UI (session auth) and the mobile API (Sanctum).
 */
class AssistantController extends Controller
{
    /**
     * Full-page chat.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversations = $user->assistantConversations()
            ->latest('updated_at')
            ->limit(30)
            ->get(['id', 'title', 'updated_at']);

        $active = $request->integer('c')
            ? $user->assistantConversations()->with('messages')->find($request->integer('c'))
            : null;

        return view('assistant.index', [
            'conversations' => $conversations,
            'active' => $active,
        ]);
    }

    /**
     * Send a message and receive the answer.
     */
    public function send(Request $request, AssistantService $assistant): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'message' => ['required', 'string', 'max:' . config('assistant.max_input_chars')],
            'conversation_id' => ['nullable', 'integer'],
            'lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
        ]);

        $conversation = isset($data['conversation_id'])
            ? $user->assistantConversations()->findOrFail($data['conversation_id'])
            : null;

        $lesson = isset($data['lesson_id'])
            ? Lesson::with('content')->find($data['lesson_id'])
            : null;

        $result = $assistant->ask($user, $data['message'], $conversation, $lesson);

        return response()->json([
            'conversation_id' => $result['conversation']->id,
            'message' => ['role' => 'assistant', 'content' => $result['reply']],
            'sources' => $result['sources'],
        ]);
    }

    /**
     * Messages of one conversation.
     */
    public function show(Request $request, int $conversation): JsonResponse
    {
        $conversation = $request->user()
            ->assistantConversations()
            ->findOrFail($conversation);

        return response()->json([
            'id' => $conversation->id,
            'title' => $conversation->title,
            'messages' => $conversation->messages()
                ->orderBy('id')
                ->get(['role', 'content', 'created_at']),
        ]);
    }

    /**
     * List the user's conversations (API).
     */
    public function conversations(Request $request): JsonResponse
    {
        return response()->json(
            $request->user()->assistantConversations()
                ->latest('updated_at')
                ->limit(50)
                ->get(['id', 'title', 'updated_at'])
        );
    }

    public function destroy(Request $request, int $conversation): JsonResponse
    {
        $request->user()->assistantConversations()->findOrFail($conversation)->delete();

        return response()->json(['deleted' => true]);
    }
}
