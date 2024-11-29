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
<meta name="Author" content="{{ config('site.common.info.name') }}">
<meta name="Keywords" content="{{ config('site.common.info.name') }}">
<meta name="description" content="{{ config('site.common.info.name') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>관리자 | {{ config('site.common.info.name') }}</title>
<link rel="icon" href="/assets/image/favicon.ico">
<link type="text/css" rel="stylesheet" href="/devAdmin/css/jquery-ui.min.css">
<link type="text/css" rel="stylesheet" href="/devAdmin/css/admin.css">
<link type="text/css" rel="stylesheet" href="/devScript/colorbox/example3/colorbox.css" />
@stack('css')

<script type="text/javascript" src="/devAdmin/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="/devAdmin/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/devAdmin/js/admin.js"></script>
<script type="text/javascript" src="/devScript/common.js"></script>
<script type="text/javascript" src="/devScript/colorbox/jquery.colorbox-min.js"></script>
@stack('scripts')

</head>
<body>
    <div class="wrap admin">
        <div id="dim" class="js-dim"></div>
        <header id="header" class="js-header">
            <div class="header-wrap inner-layer wide">
                <h1 class="header-logo">
                    <a href="{{ route('main') }}"><img src="/assets/image/common/h1_logo.png" alt="{{ config('site.common.info.name') }}"></a>
                </h1>
                <div class="util-menu-wrap">
                    <p class="user-info">
                    	 접속 계정 : {{ auth('admin')->user()->id ?? '' }}
                    </p>
                    <ul class="util-menu">
                        <li><a href="/" target="_blank" class="btn btn-util color-type4">Main</a></li>
                        <li><a href="{{ route('logoutProcess') }}" class="btn btn-util color-type3">Logout</a></li>
                        <li><button type="button" class="btn btn-util btn-line color-type3 js-btn-wide on">와이드화면 전환</button></li>
                    </ul>
                </div>
            </div>
            {{-- <nav id="gnb" class="wide">
                <div class="gnb-wrap inner-layer wide">
                    <ul class="gnb">
                    	@foreach( config('site.menu.admin_menu') as $key => $val )
                        <li><a href="{{ route($val['route_target'], $val['route_param']) }}">{{ $val['name'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </nav> --}}
        </header>

        <section id="container" class="inner-layer wide">
            
            @if( !isset($wrapClass) )
        	<div class="sub-contents">
            @endif

                @yield('content')
                
            @if( !isset($wrapClass) )    
        	</div>
            @endif
		</section>
    
        <footer id="footer" class="wide">
            <button type="button" class="btn-top js-btn-top">
                <img src="/devAdmin/image/common/ic_top.png" alt="">
                TOP
            </button>
            <div class="footer-wrap inner-layer wide">
                <div class="footer-con">
                    <span class="footer-logo">{{ config('site.common.info.name') }}</span>
                    <ul>
                        <li><strong>E-Mail</strong>. <a href="mailto:info@icop2025.org " target="_blank">info@icop2025.org </a></li>
                        <li><strong>TEL</strong>. <a href="tel:02-6959-5333 " target="_blank"> 02-6959-5333</a></li>
                    </ul>
                </div>
            </div>
            <p class="copy">COPYRIGHT(C) 2025 ICOP ALL RIGHTS RESERVED.</p>
        </footer>
    </div>

    @include('sweetalert::alert')

</body>
</html>