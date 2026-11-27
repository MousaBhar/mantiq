<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Content;
use App\Models\Lesson;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Demo catalog so the platform is usable the moment it is deployed.
 * Idempotent: running it twice does not duplicate anything.
 *
 * Enable with MANTIQ_SEED_DEMO=true. Video lessons point to public
 * freeCodeCamp videos on YouTube (embedded, not hosted here).
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->call([CategorySeeder::class, RoleSeeder::class]);

            $adminRole = Role::where('slug', 'admin')->firstOrFail();
            $userRole = Role::where('slug', 'user')->firstOrFail();
         $password = env('DEMO_PASSWORD');

if (! is_string($password) || strlen($password) < 12) {
    throw new \RuntimeException(
        'DEMO_PASSWORD must be set and contain at least 12 characters.'
    );
}

            $admin = User::updateOrCreate(
                ['email' => config('mantiq.admin_email') ?: 'admin@mantiq.test'],
                ['name' => 'Mantiq Admin', 'password' => $password, 'role_id' => $adminRole->id]
            );

            User::updateOrCreate(
                ['email' => 'student@mantiq.test'],
                ['name' => 'Demo Student', 'password' => $password, 'role_id' => $userRole->id]
            );

            foreach ($this->courses() as $course) {
                $lessons = $course['lessons'];
                unset($course['lessons']);

                $categoryId = Category::where('track', $course['track'])
                    ->where('name', $course['category'])
                    ->value('id');
                unset($course['category']);

                $content = Content::updateOrCreate(
                    ['type' => 'course', 'title' => $course['title']],
                    $course + [
                        'category_id' => $categoryId,
                        'created_by' => $admin->id,
                        'price' => 0,
                        'status' => 'published',
                        'published_at' => now(),
                    ]
                );

                foreach ($lessons as $i => $lesson) {
                    Lesson::updateOrCreate(
                        ['content_id' => $content->id, 'sort_order' => $i + 1],
                        $lesson
                    );
                }
            }
        });
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function courses(): array
    {
        return [
            [
                'track' => 'tech',
                'category' => 'Software Engineering',
                'title' => 'مدخل إلى Git وGitHub',
                'description' => 'تعلّم إدارة نسخ مشروعك والعمل مع فريق باستخدام Git وGitHub، من أول أمر حتى أول Pull Request.',
                'level' => 'Beginner',
                'is_featured' => true,
                'lessons' => [
                    [
                        'title' => 'ما هو Git ولماذا نستخدمه؟',
                        'description' => 'فهم فكرة التحكم بالنسخ ولماذا لا غنى عنه لأي مطوّر.',
                        'duration_minutes' => 68,
                        'is_preview' => true,
                        'video_url' => 'https://www.youtube.com/watch?v=RGOj5yH7evk',
                        'body' => <<<'MD'
## الفكرة باختصار

**Git** نظام للتحكم بالنسخ: يحفظ تاريخ مشروعك كلقطات (Commits) يمكنك الرجوع إليها في أي وقت.

### لماذا يهمّك؟
- تتراجع عن أي خطأ دون أن تخسر عملك.
- تجرّب أفكارًا جديدة في فروع (Branches) منفصلة.
- تعمل مع فريق دون أن تدوسوا على ملفات بعضكم.

> Git هو الأداة، أما **GitHub** فهو الموقع الذي يستضيف مشاريعك ويسهّل التعاون عليها.

شاهد الفيديو أعلاه للحصول على شرح عملي كامل، ثم انتقل للدرس التالي لتجرّب الأوامر بنفسك.
MD,
                    ],
                    [
                        'title' => 'الأوامر الأساسية: init وadd وcommit',
                        'description' => 'دورة العمل اليومية مع Git خطوة بخطوة.',
                        'duration_minutes' => 20,
                        'body' => <<<'MD'
## دورة العمل اليومية

```bash
git init                      # ابدأ مستودعًا جديدًا
git status                    # ماذا تغيّر؟
git add .                     # جهّز التغييرات
git commit -m "Add login page"  # احفظ لقطة بوصف واضح
git log --oneline             # شاهد التاريخ
```

### نصائح لرسائل Commit جيدة
1. اكتب جملة قصيرة تصف **ماذا** تغيّر ولماذا.
2. اجعل كل Commit يحل أمرًا واحدًا.
3. تجنّب رسائل مثل `fix` أو `update` وحدها.

**تمرين:** أنشئ مجلدًا جديدًا، أضف ملف `README.md`، ثم نفّذ الأوامر أعلاه.
MD,
                    ],
                    [
                        'title' => 'الفروع والعمل مع GitHub',
                        'description' => 'الفروع، الدفع إلى GitHub، وفتح Pull Request.',
                        'duration_minutes' => 25,
                        'body' => <<<'MD'
## الفروع (Branches)

```bash
git switch -c feature/search   # فرع جديد لميزة
git push -u origin feature/search
```

### من الفرع إلى Pull Request
1. ادفع الفرع إلى GitHub.
2. افتح **Pull Request** واشرح التغيير بإيجاز.
3. اطلب مراجعة من زميل، وعدّل بناءً على الملاحظات.
4. ادمج الفرع في `main` بعد الموافقة.

**قاعدة ذهبية:** لا تعمل مباشرة على `main`؛ لكل مهمة فرعها.
MD,
                    ],
                ],
            ],
            [
                'track' => 'tech',
                'category' => 'Web Development',
                'title' => 'أساسيات Laravel للمبتدئين',
                'description' => 'ابنِ تطبيق ويب حقيقيًا بإطار Laravel: المسارات، المتحكمات، قواعد البيانات والمصادقة.',
                'level' => 'Beginner',
                'is_featured' => true,
                'lessons' => [
                    [
                        'title' => 'ما هو Laravel وبنية MVC',
                        'description' => 'نظرة عامة على الإطار وكيف تنظّم المشاريع.',
                        'duration_minutes' => 265,
                        'is_preview' => true,
                        'video_url' => 'https://www.youtube.com/watch?v=ImtZ5yENzgE',
                        'body' => <<<'MD'
## MVC بثلاث جمل

- **Model:** يتعامل مع البيانات وقاعدة البيانات.
- **View:** ما يراه المستخدم (قوالب Blade).
- **Controller:** يستقبل الطلب وينسّق بين النموذج والواجهة.

### مسار الطلب في Laravel
`Route` ← `Controller` ← `Model` ← `View` ← الاستجابة.

الفيديو أعلاه دورة كاملة من freeCodeCamp؛ لا يلزمك مشاهدتها دفعة واحدة، فكل درس لاحق هنا يلخّص جزءًا منها.
MD,
                    ],
                    [
                        'title' => 'المسارات والمتحكمات',
                        'description' => 'تعريف المسارات وربطها بالمتحكمات.',
                        'duration_minutes' => 18,
                        'body' => <<<'MD'
## تعريف مسار

```php
// routes/web.php
Route::get('/courses/{course}', [CourseController::class, 'show'])
    ->name('courses.show');
```

## المتحكم

```php
class CourseController extends Controller
{
    public function show(Course $course)
    {
        return view('courses.show', ['course' => $course]);
    }
}
```

### لاحظ
- **Route Model Binding** يجلب `Course` تلقائيًا من المعرّف.
- سمِّ مساراتك (`name`) لتستخدمها في الروابط بدل كتابتها يدويًا.
MD,
                    ],
                    [
                        'title' => 'Eloquent وقواعد البيانات',
                        'description' => 'الهجرات (Migrations) والنماذج والعلاقات.',
                        'duration_minutes' => 24,
                        'body' => <<<'MD'
## الهجرة (Migration)

```php
Schema::create('lessons', function (Blueprint $table) {
    $table->id();
    $table->foreignId('course_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->timestamps();
});
```

## العلاقة في النموذج

```php
class Course extends Model
{
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
```

### تجنّب مشكلة N+1
استخدم `with('lessons')` عند جلب قائمة دورات لتحميل الدروس باستعلام واحد.
MD,
                    ],
                    [
                        'title' => 'المصادقة والأمان',
                        'description' => 'أساسيات حماية تطبيقك: المصادقة، التحقق، وحماية CSRF.',
                        'duration_minutes' => 22,
                        'body' => <<<'MD'
## ثلاث عادات أمان

1. **تحقّق من كل مُدخل** بـ `$request->validate([...])`.
2. **احمِ المسارات** بـ `middleware('auth')` وصلاحيات الأدوار.
3. **لا تكتب مفاتيحك في الكود**؛ ضعها في `.env` ولا ترفع الملف إلى GitHub.

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // مسارات الإدارة فقط
});
```

نماذج Blade تتضمن `@csrf` لحمايتك من هجمات تزوير الطلبات.
MD,
                    ],
                ],
            ],
            [
                'track' => 'tech',
                'category' => 'Software Engineering',
                'title' => 'كتابة كود نظيف وقابل للصيانة',
                'description' => 'مبادئ عملية لكتابة كود يفهمه فريقك بعد سنة: التسمية، الدوال الصغيرة، ومبادئ SOLID.',
                'level' => 'Intermediate',
                'lessons' => [
                    [
                        'title' => 'التسمية الجيدة والدوال الصغيرة',
                        'description' => 'أسهل طريقة لتحسين أي كود فورًا.',
                        'duration_minutes' => 15,
                        'is_preview' => true,
                        'body' => <<<'MD'
## الاسم يشرح النيّة

```php
// سيّئ
function calc($a, $b) { return $a * $b * 0.15; }

// أفضل
function calculateVat(float $price, int $quantity): float
{
    return $price * $quantity * self::VAT_RATE;
}
```

### قواعد سريعة
- دالة واحدة = مهمة واحدة.
- إن احتجت تعليقًا لشرح *ماذا* يفعل الكود، فغيّر الأسماء بدل التعليق.
- تجنّب الاختصارات الغامضة.
MD,
                    ],
                    [
                        'title' => 'مبادئ SOLID باختصار',
                        'description' => 'الأحرف الخمسة بأمثلة مبسّطة.',
                        'duration_minutes' => 25,
                        'body' => <<<'MD'
## SOLID

| المبدأ | الفكرة |
|---|---|
| **S** مسؤولية واحدة | لكل كلاس سبب واحد للتغيير |
| **O** مفتوح/مغلق | وسّع السلوك دون تعديل الكود القائم |
| **L** استبدال ليسكوف | الكلاس الابن يحل محل الأب دون مفاجآت |
| **I** فصل الواجهات | واجهات صغيرة محددة |
| **D** عكس الاعتماد | اعتمد على التجريد لا على التفاصيل |

ابدأ بمبدأ **S**؛ هو الأكثر فائدة وأقلها كلفة في التطبيق.
MD,
                    ],
                    [
                        'title' => 'مدخل إلى الاختبارات الآلية',
                        'description' => 'لماذا نكتب الاختبارات وكيف نبدأ.',
                        'duration_minutes' => 20,
                        'body' => <<<'MD'
## لماذا الاختبارات؟

تمنحك ثقة لتعدّل الكود دون خوف من كسر شيء آخر.

```php
public function test_guest_cannot_open_paid_lesson(): void
{
    $this->get('/lessons/1')->assertRedirect('/');
}
```

### نمط AAA
1. **Arrange:** جهّز البيانات.
2. **Act:** نفّذ الفعل.
3. **Assert:** تحقّق من النتيجة.

ابدأ باختبار المسارات الحرجة (الدخول، الدفع، الصلاحيات) قبل أي شيء آخر.
MD,
                    ],
                ],
            ],
            [
                'track' => 'business',
                'category' => 'Entrepreneurship',
                'title' => 'أساسيات ريادة الأعمال',
                'description' => 'من الفكرة إلى أول عميل: كيف تتحقق من المشكلة وتختبر فكرتك بأقل تكلفة.',
                'level' => 'Beginner',
                'is_featured' => true,
                'lessons' => [
                    [
                        'title' => 'ابدأ بالمشكلة لا بالفكرة',
                        'description' => 'لماذا تفشل أغلب المشاريع وكيف تتجنب ذلك.',
                        'duration_minutes' => 12,
                        'is_preview' => true,
                        'body' => <<<'MD'
## معظم المشاريع تفشل لسبب واحد

أنها تحلّ مشكلة **لا يهتم بها أحد بما يكفي ليدفع**.

### اختبر المشكلة بثلاثة أسئلة
1. من يعاني منها تحديدًا؟
2. كيف يحلّها اليوم؟ وكم يكلّفه ذلك؟
3. هل سيدفع لحلّ أفضل؟

**تمرين:** تحدّث مع خمسة أشخاص من جمهورك المستهدف هذا الأسبوع، واكتب ما تعلّمته قبل أن تبني أي شيء.
MD,
                    ],
                    [
                        'title' => 'النموذج الأولي (MVP) واختبار الفرضيات',
                        'description' => 'ابنِ أصغر نسخة تثبت صحة فكرتك.',
                        'duration_minutes' => 15,
                        'body' => <<<'MD'
## ما هو الـ MVP؟

أصغر نسخة تتيح لك **التعلّم** من العملاء الحقيقيين، لا نسخة ناقصة من المنتج الكامل.

### أمثلة بسيطة
- صفحة هبوط مع زر «سجّل اهتمامك».
- خدمة تقدّمها يدويًا قبل أن تؤتمتها.
- نموذج Google Form يجمع الطلبات.

### قِس ما يهم
اختر رقمًا واحدًا (تسجيلات، طلبات، مدفوعات) وحدّد مسبقًا ما الذي يعدّ نجاحًا.
MD,
                    ],
                    [
                        'title' => 'نموذج العمل في صفحة واحدة',
                        'description' => 'لخّص مشروعك بخمسة عناصر واضحة.',
                        'duration_minutes' => 18,
                        'body' => <<<'MD'
## الأسئلة الخمسة

1. **العميل:** من هو بدقة؟
2. **المشكلة:** ما الألم الذي تعالجه؟
3. **الحل:** ما الذي تقدّمه فعلًا؟
4. **الإيراد:** كيف تكسب المال؟
5. **القناة:** كيف تصل إلى العميل؟

إن لم تستطع الإجابة عن أحدها بجملة واحدة، فهذا هو الجزء الذي يحتاج مزيدًا من البحث.
MD,
                    ],
                ],
            ],
            [
                'track' => 'business',
                'category' => 'Communication Skills',
                'title' => 'مهارات التواصل المهني',
                'description' => 'اكتب رسائل واضحة، قدّم أفكارك بثقة، وأصغِ جيدًا؛ مهارات ترفع أثرك في أي وظيفة.',
                'level' => 'Beginner',
                'lessons' => [
                    [
                        'title' => 'كتابة بريد مهني فعّال',
                        'description' => 'قاعدة الأسطر الخمسة لرسائل تُقرأ وتُنفّذ.',
                        'duration_minutes' => 10,
                        'is_preview' => true,
                        'body' => <<<'MD'
## هيكل الرسالة

1. **العنوان:** يلخّص المطلوب (مثال: «مراجعة العرض قبل الخميس»).
2. **السطر الأول:** الغرض مباشرة.
3. **التفاصيل:** نقطتان أو ثلاث فقط.
4. **المطلوب:** ما الإجراء ومتى؟
5. **الختام:** شكر قصير.

> الرسالة التي تُقرأ في ثلاثين ثانية أفضل من رسالة مثالية لا يكملها أحد.
MD,
                    ],
                    [
                        'title' => 'تقديم عرض مقنع',
                        'description' => 'كيف ترتّب أفكارك لتصل إلى جمهورك.',
                        'duration_minutes' => 14,
                        'body' => <<<'MD'
## ثلاثية العرض

- **لماذا الآن؟** ابدأ بالمشكلة أو الفرصة.
- **ما الحل؟** فكرة رئيسية واحدة، لا عشر أفكار.
- **ماذا نفعل؟** اختم بخطوة تالية واضحة.

### نصائح
- شريحة واحدة = فكرة واحدة.
- تدرّب بصوت مرتفع مرتين على الأقل.
- ابدأ بقصة قصيرة أو رقم لافت.
MD,
                    ],
                    [
                        'title' => 'الاستماع الفعّال',
                        'description' => 'نصف التواصل إصغاء.',
                        'duration_minutes' => 9,
                        'body' => <<<'MD'
## ثلاث ممارسات

1. **لا تقاطع:** انتظر حتى ينهي محدّثك فكرته.
2. **أعد الصياغة:** «إذا فهمتك جيدًا، تقصد أن…».
3. **اسأل أسئلة مفتوحة:** «كيف» و«ماذا» بدل «هل».

الاستماع الجيد يبني الثقة، ويكشف المشكلة الحقيقية قبل أن تقترح الحل.
MD,
                    ],
                ],
            ],
        ];
    }
}
