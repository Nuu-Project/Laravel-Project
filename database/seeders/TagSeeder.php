<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;

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
                'name' => ['en' => 'Freshman-FirstSemester', 'zh' => '大一上'],
                'slug' => ['en' => 'Freshman-FirstSemester', 'zh' => 'Freshman-FirstSemester'],
                'type' => '年級',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Freshman-SecondSemester', 'zh' => '大一下'],
                'slug' => ['en' => 'Freshman-SecondSemester', 'zh' => 'Freshman-SecondSemester'],
                'type' => '年級',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'Sophomore-FirstSemester', 'zh' => '大二上'],
                'slug' => ['en' => 'Sophomore-FirstSemester', 'zh' => 'Sophomore-FirstSemester'],
                'type' => '年級',
                'order_column' => 3,
            ],
            [
                'name' => ['en' => 'Sophomore-SecondSemester', 'zh' => '大二下'],
                'slug' => ['en' => 'Sophomore-SecondSemester', 'zh' => 'Sophomore-SecondSemester'],
                'type' => '年級',
                'order_column' => 4,
            ],
            [
                'name' => ['en' => 'Junior-FirstSemester', 'zh' => '大三上'],
                'slug' => ['en' => 'Junior-FirstSemester', 'zh' => 'Junior-FirstSemester'],
                'type' => '年級',
                'order_column' => 5,
            ],
            [
                'name' => ['en' => 'Junior-SecondSemester', 'zh' => '大三下'],
                'slug' => ['en' => 'Junior-SecondSemester', 'zh' => 'Junior-SecondSemester'],
                'type' => '年級',
                'order_column' => 6,
            ],
            [
                'name' => ['en' => 'senior-FirstSemester', 'zh' => '大四上'],
                'slug' => ['en' => 'senior-FirstSemester', 'zh' => 'senior-FirstSemester'],
                'type' => '年級',
                'order_column' => 7,
            ],
            [
                'name' => ['en' => 'senior-SecondSemester', 'zh' => '大四下'],
                'slug' => ['en' => 'senior-SecondSemester', 'zh' => 'senior-SecondSemester'],
                'type' => '年級',
                'order_column' => 8,
            ],
            [
                'name' => ['en' => 'senior-SecondSemester', 'zh' => '大四下'],
                'slug' => ['en' => 'senior-SecondSemester', 'zh' => 'senior-SecondSemester'],
                'type' => '年級',
                'order_column' => 8,
            ],
            [
                'name' => ['en' => 'other-grades', 'zh' => '其他年級'],
                'slug' => ['en' => 'other-grades', 'zh' => 'other-grades'],
                'type' => '年級',
                'order_column' => 9,
            ],

            //課程
            [
                'name' => ['en' => 'Compulsory-course', 'zh' => '必修課'],
                'slug' => ['en' => 'Compulsory-course', 'zh' => 'Compulsory-course'],
                'type' => '課程',
                'order_column' => 1,
            ],
            [
                'name' => ['en' => 'Elective-courses', 'zh' => '選修課'],
                'slug' => ['en' => 'Elective-courses', 'zh' => 'Elective-courses'],
                'type' => '課程',
                'order_column' => 2,
            ],
            [
                'name' => ['en' => 'Other-courses', 'zh' => '其他課程'],
                'slug' => ['en' => 'Other-courses', 'zh' => 'Other-courses'],
                'type' => '課程',
                'order_column' => 3,
            ],

        ];

        foreach ($tags as $tagData) {
            $tag = Tag::findOrCreate($tagData['name'], $tagData['type'], $tagData['slug'], $tagData['order_column']);
        }
    }
}
