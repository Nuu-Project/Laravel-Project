<?php

return [
    'custom' => [
        'name' => [
            'required' => '請輸入書名',
            'max' => '書名不可超過 50 個字',
        ],
        'price' => [
            'required' => '請輸入價格',
            'numeric' => '價格必須為數字',
            'min' => '價格不能小於 0',
            'max' => '價格不能大於 9999',
        ],
        'description' => [
            'required' => '請輸入商品介紹',
        ],
        'grade' => [
            'required' => '請選擇適用的年級',
            'not_in' => '請選擇適用的年級',
        ],
        'semester' => [
            'required' => '請選擇學期',
            'not_in' => '請選擇學期',
        ],
        'category' => [
            'required' => '請選擇課程類別',
            'not_in' => '請選擇課程類別',
        ],
        'images' => [
            'required' => '請至少上傳一張商品圖片',
            'min' => '請至少上傳一張商品圖片',
            'max' => '最多只能上傳 5 張圖片',
        ],
        'images.*' => [
            'image' => '請上傳有效的圖片',
            'dimensions' => '圖片尺寸不可超過 3200x3200 像素',
            'max' => '圖片大小不可超過 2MB',
            'mimes' => '只接受 PNG、JPG、JPEG 或 GIF 格式的圖片',
        ],
        'subject' => [
            'required' => '請選擇科目',
            'not_in' => '請選擇科目',
        ],
    ],
];
