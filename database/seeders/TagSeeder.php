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
                'name' => ['en' => 'Freshman', 'zh' => '一年級'],
                'slug' => ['en' => 'freshman', 'zh' => 'freshman'],
                'type' => '年級',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Sophomore', 'zh' => '二年級'],
                'slug' => ['en' => 'sophomore', 'zh' => 'sophomore'],
                'type' => '年級',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'Junior', 'zh' => '三年級'],
                'slug' => ['en' => 'junior', 'zh' => 'junior'],
                'type' => '年級',
                'order_column' => 3,
            ],
            [
                'name' => ['en' => 'Senior', 'zh' => '四年級'],
                'slug' => ['en' => 'senior', 'zh' => 'senior'],
                'type' => '年級',
                'order_column' => 4,
            ],
            [
                'name' => ['en' => 'Other-grades', 'zh' => '其他年級'],
                'slug' => ['en' => 'other-grades', 'zh' => 'other-grades'],
                'type' => '年級',
                'order_column' => 5,
            ],

            //學期
            [
                'name' => ['en' => 'Firstsemester', 'zh' => '上學期'],
                'slug' => ['en' => 'firstsemester', 'zh' => 'firstsemester'],
                'type' => '學期',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Secondsemester', 'zh' => '下學期'],
                'slug' => ['en' => 'secondsemester', 'zh' => 'secondsemester'],
                'type' => '學期',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'other', 'zh' => '其他'],
                'slug' => ['en' => 'other', 'zh' => 'other'],
                'type' => '學期',
                'order_column' => 3,
            ],

            //課程
            [
                'name' => ['en' => 'Compulsory-course', 'zh' => '必修課'],
                'slug' => ['en' => 'compulsory-course', 'zh' => 'compulsory-course'],
                'type' => '課程',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Elective-courses', 'zh' => '選修課'],
                'slug' => ['en' => 'elective-courses', 'zh' => 'elective-courses'],
                'type' => '課程',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'Other-courses', 'zh' => '其他課程'],
                'slug' => ['en' => 'other-courses', 'zh' => 'other-courses'],
                'type' => '課程',
                'order_column' => 3,
            ],

            //科目
            [
                'name' => ['en' => 'Statistics', 'zh' => '統計學'],
                'slug' => ['en' => 'statistics', 'zh' => 'statistics'],
                'type' => '科目',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Accounting', 'zh' => '會計學'],
                'slug' => ['en' => 'accounting', 'zh' => 'accounting'],
                'type' => '科目',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'Introduction-to-Computer-Science', 'zh' => '計算機概論'],
                'slug' => ['en' => 'introduction-to-computer-science', 'zh' => 'introduction-to-computer-science'],
                'type' => '科目',
                'order_column' => 3,
            ],

        ];

        foreach ($tags as $tagData) {
            $tag = Tag::updateOrCreate(
                ['slug->en' => $tagData['slug']['en'],
                    'slug->zh' => $tagData['slug']['zh'], ],
                ['name' => $tagData['name'],
                    'type' => $tagData['type'],
                    'order_column' => $tagData['order_column']],
            );
        }
    }
}
