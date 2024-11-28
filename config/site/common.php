<?
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
        'siteName' => 'Default',
        'name' => 'Default',
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
]
?>