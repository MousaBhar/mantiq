<?php

namespace App\Services\Assistant\Drivers;

use App\Services\Assistant\Contracts\AssistantDriver;

/**
 * Works with no external service: answers from the platform catalog.
 * Used as the default driver and as the fallback when a provider fails.
 */
class LocalDriver implements AssistantDriver
{
    public function reply(string $system, array $messages, array $sources = []): string
    {
        $last = end($messages)['content'] ?? '';

        if (mb_strlen($last) <= 25 && preg_match('/^\s*(مرحبا|اهلا|أهلا|السلام|هلا|hi|hello|hey)(?=[\s!.,،؟?]|$)/iu', $last)) {
            return 'أهلًا بك! أنا ' . config('assistant.name')
                . '. اسألني عن أي مهارة تريد تعلّمها، أو اطلب مني ترشيح دورة أو شرح درس، وسأوجّهك للمسار المناسب.';
        }

        if (empty($sources)) {
            return 'لم أجد مصدرًا مطابقًا تمامًا لسؤالك داخل المنصة. جرّب كلمات أبسط، '
                . 'مثل اسم التقنية أو المهارة (Laravel، Git، التسويق…)، أو تصفّح المسار التقني والمسار الإداري من لوحتك.';
        }

        $lines = ['وجدت لك هذه المصادر القريبة من سؤالك:'];
        foreach (array_slice($sources, 0, 3) as $i => $source) {
            $lines[] = ($i + 1) . '. ' . $source['title'];
        }
        $lines[] = 'ابدأ بالأول، وإن أردت اشرح لي ما يعيقك فيه وسأساعدك خطوة بخطوة.';

        return implode("\n", $lines);
    }
}
