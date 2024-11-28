<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes,viewport-fit=cover">
<meta name="format-detection" content="telephone=no, address=no, email=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<title>{{ config('site.common.info.name') }}</title>
<link rel="icon" href="/assets/image/favicon.ico">
<link href="https://hangeul.pstatic.net/hangeul_static/css/nanum-square-neo.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.5/dist/web/variable/pretendardvariable.css" rel="stylesheet">
<link href="/assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="/assets/css/slick.css" rel="stylesheet">
<link href="/assets/css/reset.css" rel="stylesheet">
<link href="/assets/css/base.css" rel="stylesheet">
<link href="/assets/css/layout.css" rel="stylesheet">
<!-- popup css -->
<link href="/assets/css/popup.css" rel="stylesheet">
<script src="/assets/js/jquery-1.12.4.min.js"></script>
<script src="/assets/js/jquery-ui.min.js"></script>
<script src="/assets/js/slick.min.js"></script>
</head>
<body>
	<div class="win-popup-wrap popup-wrap type{{ $data['popup_skin'] }} font-fre">
        <div class="popup-contents">
			@if( $data['popup_skin'] == '1' )
            <div class="popup-tit-wrap">
                <img src="/assets/image/board/pop_logo.png" alt="KNA 급성 뇌졸중 인증의">
            </div>
			@endif
            <div class="popup-conbox">
                <div class="popup-contit-wrap">
                    <h2 class="popup-contit">{{ $data['subject'] }}</h2>
                </div>
                <div class="popup-con">
                    {!! $data['popup_select'] == '1' ? $data['content'] : $data['popup_content'] !!}
                </div>
                {{-- <div class="popup-attach-con">
                    <a href="#n" target="_blank">파일명.jpg (다운로드 : N회)</a>
                    <a href="#n" target="_blank">파일명.jpg (다운로드 : N회)</a>
                    <a href="#n" target="_blank">파일명.jpg (다운로드 : N회)</a>
                </div> --}}
				@if( ( $data['popup_detail'] ?? '' ) == 'Y' )
                <div class="btn-wrap jc-c">
                    <a href="{{ $data['popup_linkurl'] }}" target="_blank" class="btn btn-pop-more">자세히보기</a>
                    <!-- <a href="#n" class="btn btn-pop-link">바로가기</a> -->
                </div>
				@endif
            </div>
            <div class="popup-footer">
                오늘하루 그만보기 
                <a href="#n" class="btn full-right">닫기</a>
            </div>
        </div>
    </div>
</body>
</html>