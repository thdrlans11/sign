<?

return [

    'select' => [
        'setting' => [ 'Y' => '설정함', 'N' => '설정안함' ],
        'hide' => [ 'Y' => '비공개', 'N' => '공개' ],
        'skin' => [ '1' => '스킨1', '2' => '스킨2' ],
        'content' => [ '1' => '공지 내용과 동일', '2' => '팝업 내용 새로 작성' ]
    ],

    'notice' => [
        'MAIN_NUM' => '3',
        'SUB_NUM' =>'1',
        'LoginCheck' => false, //회원만 접근
        'Skin' => 'basic',        
        'PermitList' => [ 'N','1','X' ],
        'PermitView' => [ 'N','1','X' ],
        'PermitPost' => [ 'X' ],
        'PermitReply' => [ 'X' ],
        'PermitComment' => [ 'X' ],
        'UseNotice' => true,
        'UseHide' => true,
        'UsePopup' => true,
        'UseMain' => true,
        'UseReply' => true,
        'UseLink' => true,
        'UseFile' => true,
        'UseCategory' => false,
        'Category' => [ '1' => '라라벨', '2' => 'CI' ],
        'UseComment' => true,
        'Search' => [ 'subject'=>'제목', 'content'=>'내용', 'name'=>'작성자' ]
    ],

    'schedule' => [
        'MAIN_NUM' => '3',
        'SUB_NUM' =>'2',        
        'LoginCheck' => false, //회원만 접근
        'Skin' => 'schedule',        
        'PermitList' => [ 'N','1','X' ],
        'PermitView' => [ 'N','1','X' ],
        'PermitPost' => [ 'X' ],
        'PermitReply' => [ 'X' ],
        'PermitComment' => [ 'X' ],
        'UseNotice' => false,
        'UseHide' => false,
        'UsePopup' => false,
        'UseMain' => false,
        'UseReply' => false,
        'UseLink' => true,
        'UseFile' => true,
        'UseCategory' => true,
        'Category' => [ '1' => '국내', '2' => '국외' ],
        'UseComment' => false,
        'Search' => [ 'subject'=>'행사명', 'place'=>'장소' ],
        'DateType' => [ 'D'=>'하루행사', 'L'=>'장기행사' ]
    ]

]

?>