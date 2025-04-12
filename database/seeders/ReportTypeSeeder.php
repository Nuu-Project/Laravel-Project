<?php

namespace Database\Seeders;

use App\Models\ReportType;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [
            // 商品
            [
                'name' => ['zh_TW' => '圖片盜用', 'en' => 'Image theft'],  // 添加英文翻譯
                'type' => '商品',
                'order_column' => 1,
            ],
            [
                'name' => ['zh_TW' => '違法商品', 'en' => 'Illegal product'],
                'type' => '商品',
                'order_column' => 2,
            ],
            [
                'name' => ['zh_TW' => '其他', 'en' => 'Other'],
                'type' => '商品',
                'order_column' => 3,
            ],

            // 留言
            [
                'name' => ['zh_TW' => '辱罵或騷擾', 'en' => 'Abuse or harassment'],
                'type' => '留言',
                'order_column' => 1,
            ],
            [
                'name' => ['zh_TW' => '重複留言', 'en' => 'Duplicate message'],
                'type' => '留言',
                'order_column' => 2,
            ],

            // 用戶
            [
                'name' => ['zh_TW' => '帳號冒用', 'en' => 'Account impersonation'],
                'type' => '用戶',
                'order_column' => 1,
            ],
            [
                'name' => ['zh_TW' => '用戶名稱', 'en' => 'Username'],
                'type' => '用戶',
                'order_column' => 2,
            ],
        ];

        foreach ($reports as $reportData) {
            ReportType::updateOrCreate(
                [
                    'type' => $reportData['type'],
                    'order_column' => $reportData['order_column'],
                ],
                [
                    'name' => $reportData['name'],  // 直接存儲為 JSON 格式
                ]
            );
        }
    }
}
