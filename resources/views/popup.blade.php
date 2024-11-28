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

<script>
function setCookiePopup( name, value, expiredays ){
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";";
	self.close();
}	
</script>
</head>
<body>
	<div class="win-popup-wrap popup-wrap type{{ $popup['skin'] }} font-fre">
        <div class="popup-contents">
			@if( $popup['skin'] == '1' )
            <div class="popup-tit-wrap">
                <img src="/assets/image/board/pop_logo.png" alt="KNA 급성 뇌졸중 인증의">
            </div>
			@endif
            <div class="popup-conbox">
                <div class="popup-contit-wrap">
                    <h2 class="popup-contit">{{ $board['subject'] }}</h2>
                </div>
                <div class="popup-con">
                    {!! $popup['popup_select'] == '1' ? $board['content'] : $popup['popup_content'] !!}
                </div>
				@if( $board->files->count() > 0 )				
                <div class="popup-attach-con">
					@foreach( $board->files as $key => $f )
                    <a href="{{ route('download', ['type'=>'only', 'tbl'=>'boardFile', 'sid'=>base64_encode($f['sid'])]) }}">{{ $f['filename'] }} (다운로드 : {{ $f['download'] }}회)</a>
					@endforeach
                </div>
				@endif
				@if( ( $popup['popup_detail'] ?? '' ) == 'Y' )
                <div class="btn-wrap jc-c">
                    <a href="{{ $popup['popup_linkurl'] }}" target="_blank" class="btn btn-pop-more">자세히보기</a>
                    <!-- <a href="#n" class="btn btn-pop-link">바로가기</a> -->
                </div>
				@endif
            </div>
            <div class="popup-footer">
                오늘하루 그만보기 
                <a href="#n" onclick="setCookiePopup('{{ 'popup_'.$board->sid }}','done','1')" class="btn full-right">닫기</a>
            </div>
        </div>
    </div>
</body>
</html>