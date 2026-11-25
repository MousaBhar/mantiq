<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Tech Categories
            ['track' => 'tech', 'name' => 'Programming', 'name_ar' => 'البرمجة', 'sort_order' => 1],
            ['track' => 'tech', 'name' => 'Web Development', 'name_ar' => 'تطوير الويب', 'sort_order' => 2],
            ['track' => 'tech', 'name' => 'Mobile Development', 'name_ar' => 'تطوير تطبيقات الهاتف المحمول', 'sort_order' => 3],
            ['track' => 'tech', 'name' => 'Database Management', 'name_ar' => 'إدارة قواعد البيانات', 'sort_order' => 4],
            ['track' => 'tech', 'name' => 'Artificial Intelligence', 'name_ar' => 'الذكاء الاصطناعي', 'sort_order' => 5],
            ['track' => 'tech', 'name' => 'Cybersecurity', 'name_ar' => 'الأمن السيبراني', 'sort_order' => 6],
            ['track' => 'tech', 'name' => 'Cloud Computing', 'name_ar' => 'الحوسبة السحابية', 'sort_order' => 7],
            ['track' => 'tech', 'name' => 'DevOps', 'name_ar' => 'تطوير وإدارة العمليات', 'sort_order' => 8],
            ['track' => 'tech', 'name' => 'Software Engineering', 'name_ar' => 'هندسة البرمجيات', 'sort_order' => 9],
            ['track' => 'tech', 'name' => 'UI/UX Design', 'name_ar' => 'تصميم واجهة وتجربة المستخدم', 'sort_order' => 10],

            // Business Categories
            ['track' => 'business', 'name' => 'Entrepreneurship', 'name_ar' => 'ريادة الأعمال', 'sort_order' => 1],
            ['track' => 'business', 'name' => 'Business Management', 'name_ar' => 'إدارة الأعمال', 'sort_order' => 2],
            ['track' => 'business', 'name' => 'Marketing', 'name_ar' => 'التسويق', 'sort_order' => 3],
            ['track' => 'business', 'name' => 'Digital Marketing', 'name_ar' => 'التسويق الرقمي', 'sort_order' => 4],
            ['track' => 'business', 'name' => 'Project Management', 'name_ar' => 'إدارة المشاريع', 'sort_order' => 5],
            ['track' => 'business', 'name' => 'Financial Management', 'name_ar' => 'الإدارة المالية', 'sort_order' => 6],
            ['track' => 'business', 'name' => 'Human Resources', 'name_ar' => 'الموارد البشرية', 'sort_order' => 7],
            ['track' => 'business', 'name' => 'Leadership', 'name_ar' => 'القيادة', 'sort_order' => 8],
            ['track' => 'business', 'name' => 'Business Strategy', 'name_ar' => 'استراتيجية الأعمال', 'sort_order' => 9],
            ['track' => 'business', 'name' => 'Communication Skills', 'name_ar' => 'مهارات التواصل', 'sort_order' => 10],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                [
                    'track' => $category['track'],
                    'name' => $category['name'],
                ],
                [
                    'name_ar' => $category['name_ar'],
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
