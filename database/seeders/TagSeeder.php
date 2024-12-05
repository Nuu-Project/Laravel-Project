<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [

            //年級
            [
                'name' => ['en' => 'Freshman', 'zh_TW' => '一年級'],
                'slug' => ['en' => 'freshman', 'zh_TW' => 'freshman'],
                'type' => '年級',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Sophomore', 'zh_TW' => '二年級'],
                'slug' => ['en' => 'sophomore', 'zh_TW' => 'sophomore'],
                'type' => '年級',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'Junior', 'zh_TW' => '三年級'],
                'slug' => ['en' => 'junior', 'zh_TW' => 'junior'],
                'type' => '年級',
                'order_column' => 3,
            ],
            [
                'name' => ['en' => 'Senior', 'zh_TW' => '四年級'],
                'slug' => ['en' => 'senior', 'zh_TW' => 'senior'],
                'type' => '年級',
                'order_column' => 4,
            ],
            [
                'name' => ['en' => 'Other-grades', 'zh_TW' => '其他年級'],
                'slug' => ['en' => 'other-grades', 'zh_TW' => 'other-grades'],
                'type' => '年級',
                'order_column' => 5,
            ],

            //學期
            [
                'name' => ['en' => 'Firstsemester', 'zh_TW' => '上學期'],
                'slug' => ['en' => 'firstsemester', 'zh_TW' => 'firstsemester'],
                'type' => '學期',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Secondsemester', 'zh_TW' => '下學期'],
                'slug' => ['en' => 'secondsemester', 'zh_TW' => 'secondsemester'],
                'type' => '學期',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'other', 'zh_TW' => '其他'],
                'slug' => ['en' => 'other', 'zh_TW' => 'other'],
                'type' => '學期',
                'order_column' => 3,
            ],

            //課程
            [
                'name' => ['en' => 'Compulsory-course', 'zh_TW' => '必修課'],
                'slug' => ['en' => 'compulsory-course', 'zh_TW' => 'compulsory-course'],
                'type' => '課程',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Elective-courses', 'zh_TW' => '選修課'],
                'slug' => ['en' => 'elective-courses', 'zh_TW' => 'elective-courses'],
                'type' => '課程',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'Other-courses', 'zh_TW' => '其他課程'],
                'slug' => ['en' => 'other-courses', 'zh_TW' => 'other-courses'],
                'type' => '課程',
                'order_column' => 3,
            ],

            //科目
            [
                'name' => ['en' => 'Statistics', 'zh_TW' => '統計學'],
                'slug' => ['en' => 'statistics', 'zh_TW' => 'statistics'],
                'type' => '科目',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Accounting', 'zh_TW' => '會計學'],
                'slug' => ['en' => 'accounting', 'zh_TW' => 'accounting'],
                'type' => '科目',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'Introduction-to-Computer-Science', 'zh_TW' => '計算機概論'],
                'slug' => ['en' => 'introduction-to-computer-science', 'zh_TW' => 'introduction-to-computer-science'],
                'type' => '科目',
                'order_column' => 3,
            ],

        ];

        foreach ($tags as $tagData) {
            $tag = Tag::updateOrCreate(
                [
                    'type' => $tagData['type'],
                    'order_column' => $tagData['order_column'],
                ],
                [
                    'name' => $tagData['name'],
                    'slug' => $tagData['slug'],
                ]
            );
        }
    }
}
