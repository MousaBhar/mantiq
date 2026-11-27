# ما الذي أُضيف / تغيّر

## جديد
- **الهوية:** `public/images/brand/*` (شعار، أيقونات، صورة OG)، `favicon.svg/.ico`، `apple-touch-icon.png`، `site.webmanifest`، وملف `layouts/partials/head.blade.php` (عنوان، وصف، Open Graph، Twitter Card).
- **مرشد منطق:** `config/assistant.php`، `app/Services/Assistant/*`، `AssistantController`، جدولا `assistant_conversations` و`assistant_messages`، ومكوّن `x-assistant-widget` + صفحة `/assistant`.
- **شهادات:** جدول `certificates`، `CertificateService`، `CertificateController`، وصفحات العرض والتحقق.
- **بحث:** `SearchService` + `/search` + `/api/search`.
- **API الكتالوج:** `Api\CatalogController`.
- **الدروس:** أعمدة `body` و`video_url` و`is_preview`، ومشغّل فيديو مضمَّن (`App\Support\VideoEmbed`)، وتنقّل سابق/تالي.
- **لوحة المتعلّم:** بحث، «تابع التعلّم»، شهاداتي، وروابط المرشد.
- **محتوى تجريبي:** `DemoContentSeeder` (5 دورات و15 درسًا بالعربية) + حسابا تجربة.
- **النشر:** `Dockerfile` و`docker-compose.yml` و`docker/entrypoint.sh` و`.dockerignore` و`.github/workflows/ci.yml`.
- **اختبارات:** `AssistantTest`, `CertificateTest`, `LessonPreviewAndSearchTest`, `VideoEmbedTest`.

## تعديلات على ملفات موجودة
- `routes/web.php`, `routes/api.php`: مسارات جديدة + `throttle` على الدخول والتسجيل وإعادة كلمة المرور.
- `bootstrap/app.php`: `trustProxies` وترويسات الأمان `SecurityHeaders`.
- `AuthController`: إصلاح قراءة `email_verified` من Google عند غيابه.
- `LearningController`, `LessonProgressController`, `DashboardController`, `ContentAccess`: دروس المعاينة، التنقّل، إصدار الشهادة، بيانات اللوحة.
- `Admin\LessonController` + نماذج الدروس: حقول الفيديو والنص والمعاينة.
- `DatabaseSeeder`: صار يستدعي المحتوى التجريبي عند `MANTIQ_SEED_DEMO=true` بدل إنشاء `test@example.com`.
- `.env.example`, `config/mantiq.php`, `README.md`.
