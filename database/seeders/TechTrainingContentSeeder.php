<?php

namespace Database\Seeders;

use App\Models\Content;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TechTrainingContentSeeder extends Seeder
{
    public function run(): void
    {
        // Verify that user ID 1 is an administrator.
        $creatorId = DB::table('users')
            ->where('id', 1)
            ->where('role_id', 1)
            ->value('id');

        if (!$creatorId) {
            throw new RuntimeException(
                'Administrator account with ID 1 was not found.'
            );
        }

        $items = [
            [
                'title' => 'Capable Graduates - Training Tasks',
                'description' => 'الدليل الرئيسي للمهام والتكاليف العملية الخاصة بالمساق.',
                'category_id' => 1,
                'media_url' => 'https://aamairia-org.gitbook.io/capable-graduates',
            ],
            [
                'title' => 'What is a Design Pattern?',
                'description' => 'شرح مفهوم Design Patterns وأهميتها في بناء تطبيقات قابلة للصيانة والتطوير.',
                'category_id' => 9,
                'media_url' => 'https://refactoring.guru/design-patterns/what-is-pattern',
            ],
            [
                'title' => 'PHP Standards Recommendations (PSR)',
                'description' => 'المعايير المعتمدة لكتابة وتنظيم مشاريع PHP بطريقة احترافية.',
                'category_id' => 1,
                'media_url' => 'https://www.php-fig.org/psr/',
            ],
            [
                'title' => 'PSR-4 Autoloader',
                'description' => 'فهم آلية التحميل التلقائي للكلاسات وتنظيم ملفات المشروع.',
                'category_id' => 1,
                'media_url' => 'https://www.php-fig.org/psr/psr-4/',
            ],
            [
                'title' => 'Introduction Session',
                'description' => 'الجلسة التعريفية الأولى للمساق وشرح الخطة والأدوات المستخدمة.',
                'category_id' => 1,
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZCna77ZcCHKJGxfUdLClMC9mPn0kL446T07',
            ],
            [
                'title' => 'Design Pattern Session',
                'description' => 'شرح عملي لأنماط التصميم البرمجية وكيفية استخدامها.',
                'category_id' => 9,
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZFTYNZW8nTkd6OW0yHOteF7dwHzXfasb8y',
            ],
            [
                'title' => 'Git Session',
                'description' => 'تعلم أساسيات Git وGitHub وإدارة الإصدارات والعمل ضمن فريق.',
                'category_id' => 8,
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZvixUZXiugaL3CzSzTtgVDuHSIVB7J4anV',
            ],
            [
                'title' => 'PSR-16 Cache System',
                'description' => 'التعرف على نظام التخزين المؤقت Cache وفق معيار PSR-16.',
                'category_id' => 1,
                'media_url' => 'https://aamairia-org.gitbook.io/capable-graduates/psr-16-cache-system',
            ],
            [
                'title' => 'Linux Commands',
                'description' => 'دليل لأهم أوامر Linux المستخدمة في بيئات التطوير والخوادم.',
                'category_id' => 8,
                'media_url' => 'https://www.digitalocean.com/community/tutorials/linux-commands',
            ],
            [
                'title' => 'Laravel Routing',
                'description' => 'تعلم إنشاء المسارات Routes وتنظيم تدفق الطلبات داخل Laravel.',
                'category_id' => 2,
                'media_url' => 'https://laravel.com/framework/docs/routing',
            ],
            [
                'title' => 'Docker Session',
                'description' => 'جلسة تدريبية للتعرف على Docker وتشغيل التطبيقات داخل الحاويات.',
                'category_id' => 8,
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZV2vAZD24w60FfCjbLTwvaFJqTFQbhmSt7',
            ],
            [
                'title' => 'Laravel Facades',
                'description' => 'شرح آلية عمل Facades واستخدامها للوصول إلى خدمات Laravel.',
                'category_id' => 2,
                'media_url' => 'https://laravel.com/framework/docs/13.x/facades#main-content',
            ],
            [
                'title' => 'Facade Design Pattern',
                'description' => 'شرح نمط Facade البرمجي مع أمثلة لفهم الفكرة الأساسية.',
                'category_id' => 9,
                'media_url' => 'https://refactoring.guru/design-patterns/facade',
            ],
            [
                'title' => 'Docker Compose',
                'description' => 'التعرف على إدارة وتشغيل عدة خدمات Docker باستخدام Compose.',
                'category_id' => 8,
                'media_url' => 'https://docs.docker.com/compose/',
            ],
            [
                'title' => 'HTTP Lifecycle and Laravel Configuration',
                'description' => 'فهم دورة حياة الطلب داخل Laravel وهيكلية المشروع والإعدادات.',
                'category_id' => 2,
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZXzVrZMjYMbkSm0LjHRzlhu5slURADcM70',
            ],
            [
                'title' => 'Routes, Controllers and APIs',
                'description' => 'التعامل مع Routes وControllers وبناء REST APIs.',
                'category_id' => 2,
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZyzVrZIUbV3l0weEFFbGcefE0SnphXU0Ny',
            ],
            [
                'title' => 'Areisto Store - Figma Design',
                'description' => 'تصميم واجهات مشروع Areisto Store المستخدمة أثناء التطوير.',
                'category_id' => 10,
                'media_url' => 'https://www.figma.com/design/bz0ZZMw8xODrq4tP2gcOyI/Areisto-Store?node-id=35-77&p=f',
            ],
            [
                'title' => 'Tinker, Migrations and Models',
                'description' => 'إدارة قواعد البيانات باستخدام Migrations وModels وأداة Tinker.',
                'category_id' => 4,
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZHuVrZ2DTcekawQJ51nC4lTitzOS5HvfMk',
            ],
            [
                'title' => 'Postman and API Testing',
                'description' => 'اختبار REST APIs باستخدام Postman وتنفيذ عمليات GET وPOST.',
                'category_id' => 2,
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZuuVrZHTXUhOqEwCh5bjDXVIBFQfMRj2ek',
            ],
            [
                'title' => 'Sprint 1 - Project Plan',
                'description' => 'خطة العمل والمتطلبات الخاصة بالمرحلة الأولى من المشروع.',
                'category_id' => 9,
                'media_url' => 'https://docs.google.com/spreadsheets/d/1podHJzfN86iO0voQ9cym8alz_6xjWOEm/edit?usp=sharing&ouid=106560700703743759337&rtpof=true&sd=true',
            ],
            [
                'title' => 'PStore GitHub Repository',
                'description' => 'المستودع الرئيسي للمشروع العملي الذي سيتم تطويره خلال المساق.',
                'category_id' => 9,
                'media_url' => 'https://github.com/aamairia-org/pstore',
            ],
            [
                'title' => 'PHP Security - PHP RCE',
                'description' => 'مقال أمني يشرح مخاطر ثغرات Remote Code Execution في تطبيقات PHP.',
                'category_id' => 6,
                'media_url' => 'https://www.wiz.io/blog/php2shell-php-rce',
            ],
            [
                'title' => 'How to Create a Pull Request',
                'description' => 'شرح خطوات إنشاء Pull Request ورفع المهام للمراجعة.',
                'category_id' => 8,
                'media_url' => 'https://e.pcloud.link/publink/show?code=XZM8hX7ZdUi9okAN8ik4cNemuuKt5XP6BBBV',
            ],
            [
                'title' => 'Session 7 - Week 4 Recording',
                'description' => 'التسجيل الكامل للجلسة التدريبية للأسبوع الرابع.',
                'category_id' => 1,
                'media_url' => 'https://e.pcloud.link/publink/show?code=VZn1hX7Z9YBGYLTP9GuduCA5AMRwljfiTjsk',
            ],
        ];

        DB::transaction(function () use ($items, $creatorId) {
            foreach ($items as $item) {

                // Verify that the category is active and belongs to Tech.
                $categoryExists = DB::table('categories')
                    ->where('id', $item['category_id'])
                    ->where('track', 'tech')
                    ->where('is_active', true)
                    ->exists();

                if (!$categoryExists) {
                    throw new RuntimeException(
                        "Invalid or inactive category ID: {$item['category_id']}"
                    );
                }

                // Avoid creating duplicate records.
                Content::firstOrCreate(
                    [
                        'title' => $item['title'],
                        'type' => 'course',
                        'track' => 'tech',
                    ],
                    [
                        'category_id' => $item['category_id'],
                        'created_by' => $creatorId,
                        'description' => $item['description'],
                        'cover' => null,
                        'price' => 0,
                        'status' => 'draft',
                        'published_at' => null,
                        'is_featured' => false,
                        'views_count' => 0,
                        'level' => null,
                        'pages' => null,
                        'read_minutes' => null,
                        'duration_minutes' => null,
                        'media_url' => $item['media_url'],
                        'file_path' => null,
                    ]
                );
            }
        });

        $this->command->info(
            'Tech training content seeding completed successfully.'
        );
    }
}
