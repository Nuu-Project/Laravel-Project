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
                    'name' => ['en' => 'Introduction to Computer Science', 'zh_TW' => '計算機概論'],
                    'slug' => ['en' => 'introduction-to-computer-science', 'zh_TW' => 'introduction-to-computer-science'],
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
                    'name' => ['en' => 'Economics', 'zh_TW' => '經濟學'],
                    'slug' => ['en' => 'economics', 'zh_TW' => 'economics'],
                    'type' => '科目',
                    'order_column' => 3,
                ],
                [
                    'name' => ['en' => 'Calculus', 'zh_TW' => '微積分'],
                    'slug' => ['en' => 'calculus', 'zh_TW' => 'calculus'],
                    'type' => '科目',
                    'order_column' => 4,
                ],
                [
                    'name' => ['en' => 'Business Data Communications', 'zh_TW' => '商業資料通訊'],
                    'slug' => ['en' => 'business-data-communications', 'zh_TW' => 'business-data-communications'],
                    'type' => '科目',
                    'order_column' => 5,
                ],
                [
                    'name' => ['en' => 'Management Mathematics', 'zh_TW' => '管理數學'],
                    'slug' => ['en' => 'management-mathematics', 'zh_TW' => 'management-mathematics'],
                    'type' => '科目',
                    'order_column' => 6,
                ],
                [
                    'name' => ['en' => 'Introduction to Information Management', 'zh_TW' => '資訊管理導論'],
                    'slug' => ['en' => 'introduction-to-information-management', 'zh_TW' => 'introduction-to-information-management'],
                    'type' => '科目',
                    'order_column' => 7,
                ],
                [
                    'name' => ['en' => 'Introduction to Business', 'zh_TW' => '企業概論'],
                    'slug' => ['en' => 'introduction-to-business', 'zh_TW' => 'introduction-to-business'],
                    'type' => '科目',
                    'order_column' => 8,
                ],
                [
                    'name' => ['en' => 'Programming', 'zh_TW' => '程式設計'],
                    'slug' => ['en' => 'programming', 'zh_TW' => 'programming'],
                    'type' => '科目',
                    'order_column' => 9,
                ],
                [
                    'name' => ['en' => 'Statistics', 'zh_TW' => '統計學'],
                    'slug' => ['en' => 'statistics', 'zh_TW' => 'statistics'],
                    'type' => '科目',
                    'order_column' => 10,
                ],
                [
                    'name' => ['en' => 'Business English Practice', 'zh_TW' => '商用英文實務'],
                    'slug' => ['en' => 'business-english-practice', 'zh_TW' => 'business-english-practice'],
                    'type' => '科目',
                    'order_column' => 11,
                ],
                [
                    'name' => ['en' => 'Management', 'zh_TW' => '管理學'],
                    'slug' => ['en' => 'management', 'zh_TW' => 'management'],
                    'type' => '科目',
                    'order_column' => 12,
                ],
                [
                    'name' => ['en' => 'Database System Management', 'zh_TW' => '資料庫系統管理'],
                    'slug' => ['en' => 'database-system-management', 'zh_TW' => 'database-system-management'],
                    'type' => '科目',
                    'order_column' => 13,
                ],
                [
                    'name' => ['en' => 'Data Structures', 'zh_TW' => '資料結構'],
                    'slug' => ['en' => 'data-structures', 'zh_TW' => 'data-structures'],
                    'type' => '科目',
                    'order_column' => 14,
                ],
                [
                    'name' => ['en' => 'Database Programming', 'zh_TW' => '資料庫程式設計'],
                    'slug' => ['en' => 'database-programming', 'zh_TW' => 'database-programming'],
                    'type' => '科目',
                    'order_column' => 15,
                ],
                [
                    'name' => ['en' => 'Business English Correspondence Practice', 'zh_TW' => '商用英文書信實務'],
                    'slug' => ['en' => 'business-english-correspondence-practice', 'zh_TW' => 'business-english-correspondence-practice'],
                    'type' => '科目',
                    'order_column' => 18,
                ],
                [
                    'name' => ['en' => 'Software Project Management', 'zh_TW' => '軟體專案管理'],
                    'slug' => ['en' => 'software-project-management', 'zh_TW' => 'software-project-management'],
                    'type' => '科目',
                    'order_column' => 19,
                ],
                [
                    'name' => ['en' => 'Operations Research', 'zh_TW' => '作業研究'],
                    'slug' => ['en' => 'operations-research', 'zh_TW' => 'operations-research'],
                    'type' => '科目',
                    'order_column' => 20,
                ],
                [
                    'name' => ['en' => 'Commercial Law', 'zh_TW' => '商事法'],
                    'slug' => ['en' => 'commercial-law', 'zh_TW' => 'commercial-law'],
                    'type' => '科目',
                    'order_column' => 21,
                ],
                [
                    'name' => ['en' => 'Management Information Systems', 'zh_TW' => '管理資訊系統'],
                    'slug' => ['en' => 'management-information-systems', 'zh_TW' => 'management-information-systems'],
                    'type' => '科目',
                    'order_column' => 22,
                ],
                [
                    'name' => ['en' => 'Others', 'zh_TW' => '其他'],
                    'slug' => ['en' => 'others', 'zh_TW' => 'others'],
                    'type' => '科目',
                    'order_column' => 23,
                ],
                [
                    'name' => ['en' => 'English', 'zh_TW' => '英文'],
                    'slug' => ['en' => 'english', 'zh_TW' => 'english'],
                    'type' => '科目',
                    'order_column' => 23,
                ],
                [
                    'name' => ['en' => 'Chinese', 'zh_TW' => '國文'],
                    'slug' => ['en' => 'chinese', 'zh_TW' => 'chinese'],
                    'type' => '科目',
                    'order_column' => 24,
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
