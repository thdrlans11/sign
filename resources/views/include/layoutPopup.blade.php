<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes,viewport-fit=cover">
<meta name="format-detection" content="telephone=no, address=no, email=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta name="Author" content="{{ config('site.common.info.name') }}">
<meta name="Keywords" content="{{ config('site.common.info.name') }}">
<meta name="description" content="{{ config('site.common.info.name') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('site.common.info.name') }}</title>

<link type="text/css" rel="stylesheet" href="/devScript/colorbox/example3/colorbox.css" />
@stack('css')

<script type="text/javascript" src="/devScript/jquery.min.js"></script>
<script type="text/javascript" src="/devScript/common.js"></script>
<script src="/devScript/colorbox/jquery.colorbox-min.js"></script>
@stack('scripts')

</head>
<body>
    <div style="padding:30px 30px 0px 30px">
        @yield('content')
    </div>

    @include('sweetalert::alert')
</body>
</html>