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
                'name' => ['zh_TW' => '圖片盜用'],
                'type' => '商品',
                'order_column' => 1,
            ],
            [
                'name' => ['zh_TW' => '違法商品'],
                'type' => '商品',
                'order_column' => 2,
            ],
            [
                'name' => ['zh_TW' => '其他'],
                'type' => '商品',
                'order_column' => 3,
            ],

            //留言
            [
                'name' => ['zh_TW' => '辱罵或騷擾'],
                'type' => '留言',
                'order_column' => 1,
            ],
            [
                'name' => ['zh_TW' => '重複留言'],
                'type' => '留言',
                'order_column' => 2,
            ],

            //用戶
            [
                'name' => ['zh_TW' => '帳號冒用'],
                'type' => '用戶',
                'order_column' => 1,
            ],
            [
                'name' => ['zh_TW' => '用戶名稱'],
                'type' => '用戶',
                'order_column' => 2,
            ],

        ];

        foreach ($reports as $reportData) {
            Report::updateOrCreate(
                [
                    'type' => $reportData['type'],
                    'order_column' => $reportData['order_column'],
                ],
                [
                    'name' => json_encode($reportData['name']),
                ]
            );
        }
    }
}
