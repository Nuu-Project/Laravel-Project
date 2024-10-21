<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [

            //商品
            [
                'name' => ['zh' => '圖片盜用'],
                'type' => '商品',
            ],
            [
                'name' => ['zh' => '違法商品'],
                'type' => '商品',
            ],
            [
                'name' => ['zh' => '其他'],
                'type' => '商品',
            ],

            //留言
            [
                'name' => ['zh' => '辱罵或騷擾'],
                'type' => '留言',
            ],
            [
                'name' => ['zh' => '重複留言'],
                'type' => '留言',
            ],

            //用戶
            [
                'name' => ['zh' => '帳號冒用'],
                'type' => '用戶',
            ],
            [
                'name' => ['zh' => '用戶名稱'],
                'type' => '用戶',
            ],

        ];

        foreach ($reports as $reportData) {
            Report::updateOrCreate(
                [
                    'name->zh' => $reportData['name']['zh'],
                    'type' => $reportData['type'],
                ],
            );
        }
    }
}
