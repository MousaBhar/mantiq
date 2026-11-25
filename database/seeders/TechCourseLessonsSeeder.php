<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class TechCourseLessonsSeeder extends Seeder
{
    public function run(): void
    {
        $lessons = [
            // Course 1: Capable Graduates
            [
                'content_id' => 1,
                'title' => 'Training Tasks and Practical Assignments',
                'description' => 'الدليل الرئيسي للمهام والتكاليف العملية الخاصة بالمساق.',
                'media_url' => 'https://aamairia-org.gitbook.io/capable-graduates',
            ],

            // Course 2: Design Patterns
            [
                'content_id' => 2,
                'title' => 'Introduction to Design Patterns',
                'description' => 'شرح مفهوم Design Patterns وأهميتها في بناء تطبيقات قابلة للصيانة والتطوير.',
                'media_url' => 'https://refactoring.guru/design-patterns/what-is-pattern',
            ],
            [
                'content_id' => 2,
                'title' => 'Design Pattern Practical Session',
                'description' => 'شرح عملي لأنماط التصميم البرمجية وكيفية استخدامها.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZFTYNZW8nTkd6OW0yHOteF7dwHzXfasb8y',
            ],
            [
                'content_id' => 2,
                'title' => 'Facade Design Pattern',
                'description' => 'شرح نمط Facade البرمجي مع أمثلة لفهم الفكرة الأساسية.',
                'media_url' => 'https://refactoring.guru/design-patterns/facade',
            ],

            // Course 3: PHP Standards
            [
                'content_id' => 3,
                'title' => 'PHP Standards Recommendations',
                'description' => 'المعايير المعتمدة لكتابة وتنظيم مشاريع PHP بطريقة احترافية.',
                'media_url' => 'https://www.php-fig.org/psr/',
            ],
            [
                'content_id' => 3,
                'title' => 'PSR-4 Autoloader',
                'description' => 'فهم آلية التحميل التلقائي للكلاسات وتنظيم ملفات المشروع.',
                'media_url' => 'https://www.php-fig.org/psr/psr-4/',
            ],
            [
                'content_id' => 3,
                'title' => 'PSR-16 Cache System',
                'description' => 'التعرف على نظام التخزين المؤقت Cache وفق معيار PSR-16.',
                'media_url' => 'https://aamairia-org.gitbook.io/capable-graduates/psr-16-cache-system',
            ],

            // Course 5: Introduction
            [
                'content_id' => 5,
                'title' => 'Introduction Session Recording',
                'description' => 'الجلسة التعريفية الأولى للمساق وشرح الخطة والأدوات المستخدمة.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZCna77ZcCHKJGxfUdLClMC9mPn0kL446T07',
            ],

            // Course 7: Git
            [
                'content_id' => 7,
                'title' => 'Git Session',
                'description' => 'تعلم أساسيات Git وGitHub وإدارة الإصدارات والعمل ضمن فريق.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZvixUZXiugaL3CzSzTtgVDuHSIVB7J4anV',
            ],
            [
                'content_id' => 7,
                'title' => 'How to Create a Pull Request',
                'description' => 'شرح خطوات إنشاء Pull Request ورفع المهام للمراجعة.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZM8hX7ZdUi9okAN8ik4cNemuuKt5XP6BBBV',
            ],

            // Course 9: Linux
            [
                'content_id' => 9,
                'title' => 'Essential Linux Commands',
                'description' => 'دليل لأهم أوامر Linux المستخدمة في بيئات التطوير والخوادم.',
                'media_url' => 'https://www.digitalocean.com/community/tutorials/linux-commands',
            ],

            // Course 10: Laravel Routing
            [
                'content_id' => 10,
                'title' => 'Laravel Routing',
                'description' => 'تعلم إنشاء المسارات Routes وتنظيم تدفق الطلبات داخل Laravel.',
                'media_url' => 'https://laravel.com/framework/docs/routing',
            ],

            // Course 11: Docker
            [
                'content_id' => 11,
                'title' => 'Docker Introduction Session',
                'description' => 'جلسة تدريبية للتعرف على Docker وتشغيل التطبيقات داخل الحاويات.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZV2vAZD24w60FfCjbLTwvaFJqTFQbhmSt7',
            ],
            [
                'content_id' => 11,
                'title' => 'Docker Compose',
                'description' => 'التعرف على إدارة وتشغيل عدة خدمات Docker باستخدام Compose.',
                'media_url' => 'https://docs.docker.com/compose/',
            ],

            // Course 12: Laravel Facades
            [
                'content_id' => 12,
                'title' => 'Laravel Facades',
                'description' => 'شرح آلية عمل Facades واستخدامها للوصول إلى خدمات Laravel.',
                'media_url' => 'https://laravel.com/framework/docs/13.x/facades#main-content',
            ],

            // Course 15: Laravel Core
            [
                'content_id' => 15,
                'title' => 'HTTP Request Lifecycle and Configuration',
                'description' => 'فهم دورة حياة الطلب داخل Laravel وهيكلية المشروع والإعدادات.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZXzVrZMjYMbkSm0LjHRzlhu5slURADcM70',
            ],

            // Course 16: Routes, Controllers and APIs
            [
                'content_id' => 16,
                'title' => 'Routes, Controllers and REST APIs',
                'description' => 'التعامل مع Routes وControllers وبناء REST APIs.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZyzVrZIUbV3l0weEFFbGcefE0SnphXU0Ny',
            ],

            // Course 18: Database and Models
            [
                'content_id' => 18,
                'title' => 'Tinker, Migrations and Models',
                'description' => 'إدارة قواعد البيانات باستخدام Migrations وModels وأداة Tinker.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZHuVrZ2DTcekawQJ51nC4lTitzOS5HvfMk',
            ],

            // Course 19: Postman
            [
                'content_id' => 19,
                'title' => 'Postman and API Testing',
                'description' => 'اختبار REST APIs باستخدام Postman وتنفيذ عمليات GET وPOST.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZuuVrZHTXUhOqEwCh5bjDXVIBFQfMRj2ek',
            ],

            // Course 24: Week 4
            [
                'content_id' => 24,
                'title' => 'Session 7 - Week 4',
                'description' => 'التسجيل الكامل للجلسة التدريبية للأسبوع الرابع.',
                'media_url' => 'https://e.pcloud.link/publink/show?code=VZn1hX7Z9YBGYLTP9GuduCA5AMRwljfiTjsk',
            ],
        ];

        DB::transaction(function () use ($lessons) {
            foreach ($lessons as $index => $lesson) {
                $lesson['sort_order'] = Lesson::where(
                    'content_id',
                    $lesson['content_id']
                )->max('sort_order') + 1;

                $lesson['duration_minutes'] = null;

                Lesson::firstOrCreate(
                    [
                        'content_id' => $lesson['content_id'],
                        'title' => $lesson['title'],
                    ],
                    $lesson
                );
            }
        });
    }
}
