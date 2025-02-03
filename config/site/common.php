<?php
return [

    // ================= api =================
    'api' => [
        'url' => env('APP_URL') . '/api',
    ],

    // ================= admin =================
    'admin' => [
        'url' => env('APP_URL') . '/admin',
    ],

    // ================= web =================
    'web' => [
        'url' => env('APP_URL'),
    ],

    'info' => [                
        'siteName' => '전자서명 관리시스템',
        'name' => '전자서명 관리시스템',
        'email' => 'Default@Default.co.kr',
        'url' => env('APP_URL'),
        'ecareNum' => env('ECARE_NUMBER'),
    ],

    'dayOfWeek' => [
        '0' => '일',
        '1' => '월',
        '2' => '화',
        '3' => '수',
        '4' => '목',
        '5' => '금',
        '6' => '토'
    ],

    'selectYn' => [
        'Y' => 'Y',
        'N' => 'N'
    ]
]
?>