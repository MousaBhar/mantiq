<p align="center">
  <img src="public/images/brand/logo.svg" width="320" alt="Mantiq">
</p>

<p align="center"><strong>منصة منطق للتعلّم التقني والمهني</strong><br>Laravel 13 · Blade · Tailwind/Vite · Sanctum</p>

---

## المزايا

| الميزة | الوصف |
|---|---|
| مساران تعليميان | تقني وإداري، مع بودكاست مستقل |
| دروس غنية | فيديو مضمَّن (YouTube / Vimeo) + نص Markdown + درس معاينة مجاني + السابق/التالي |
| تتبّع التقدّم | إكمال الدروس ونسب الإنجاز و«تابع التعلّم» في لوحة المتعلّم |
| شهادات | تُصدر تلقائيًا عند إكمال كل دروس الدورة، قابلة للطباعة، وصفحة تحقق عامة `/certificates/verify/{code}` |
| بحث | بحث في الدورات والدروس `/search` |
| مرشد منطق | مساعد تعليمي عائم + صفحة كاملة `/assistant` يعرف كتالوج المنصة وتقدّم المتعلّم والدرس المفتوح |
| API للتطبيق | كتالوج، بحث، تقدّم، ومحادثات المرشد عبر Sanctum |
| الأمان | Rate limiting، ترويسات أمان، فحص صلاحيات الدروس المدفوعة، تعقيم محتوى Markdown |
| الهوية | شعار، favicon، أيقونات PWA، ومعاينة رابط (Open Graph) |

## التشغيل السريع (Docker)

```bash
docker compose up --build
# افتح http://localhost:8080
```

حسابات تجريبية (تُنشأ لأن `MANTIQ_SEED_DEMO=true`):

| الدور | البريد | كلمة المرور |
|---|---|---|
| مدير | `admin@mantiq.test` | `password` |
| متعلّم | `student@mantiq.test` | `password` |

> **مهم:** عند الإطلاق الفعلي اجعل `MANTIQ_SEED_DEMO=false`، وحدّد `MANTIQ_ADMIN_EMAIL` ببريدك، وغيّر `DEMO_PASSWORD`.

## التشغيل المحلي (بدون Docker)

```bash
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
# فعّل المحتوى التجريبي (اختياري): MANTIQ_SEED_DEMO=true في .env
php artisan migrate --seed
npm install && npm run build      # أو npm run dev أثناء التطوير
php artisan serve
```

## مرشد منطق

يعمل فورًا دون أي مفتاح (`ASSISTANT_DRIVER=local`): يجيب من كتالوج المنصة ويقترح الدورات المناسبة.
للحصول على إجابات مولَّدة اختر مزوّدًا في `.env`:

```env
# Anthropic
ASSISTANT_DRIVER=anthropic
ANTHROPIC_API_KEY=...
ASSISTANT_MODEL=claude-sonnet-5-5

# أو أي خدمة متوافقة مع OpenAI (OpenAI / Groq / Ollama ...)
ASSISTANT_DRIVER=openai
OPENAI_API_KEY=...
OPENAI_BASE_URL=https://api.openai.com/v1
OPENAI_MODEL=gpt-4o-mini
```

- إن فشل المزوّد لأي سبب يعود المرشد تلقائيًا للإجابة المحلية فلا يبقى المتعلّم بلا رد.
- لا يُرسل محتوى درس مدفوع للمزوّد إلا إن كان للمستخدم صلاحية عليه.
- الحد الافتراضي 20 رسالة/دقيقة لكل مستخدم (`ASSISTANT_RATE_LIMIT`).
- لإضافة مزوّد جديد: نفّذ `App\Services\Assistant\Contracts\AssistantDriver` وسجّله في `AssistantService::driver()`.

## API

| الطريقة | المسار | ملاحظات |
|---|---|---|
| GET | `/api/contents?track=tech\|business` | الدورات المنشورة |
| GET | `/api/contents/{id}` | تفاصيل ودروس (الفيديو حسب الصلاحية) |
| GET | `/api/search?q=` | بحث |
| GET | `/api/me/progress` | تقدّمي (Sanctum) |
| POST | `/api/assistant/messages` | `message`, `conversation_id?`, `lesson_id?` (Sanctum) |
| GET/DELETE | `/api/assistant/conversations[/{id}]` | سجل المحادثات (Sanctum) |

بالإضافة إلى مسارات المصادقة وكلمة المرور (OTP) الموجودة سابقًا.

## الاختبارات

```bash
php artisan test
```

## إدارة المحتوى

من لوحة الإدارة `/admin` يمكنك لكل درس: رابط فيديو، نصًّا بصيغة Markdown، وتحديده «معاينة مجانية».
فيديوهات العرض التجريبي مرتبطة بفيديوهات عامة من قناة freeCodeCamp على YouTube (مضمَّنة وليست مستضافة هنا).
