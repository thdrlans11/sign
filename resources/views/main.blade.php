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
<title>{{ config('site.common.info.name') }}</title>
{{-- <link rel="icon" href="/assets/image/favicon.ico"> --}}
<link type="text/css" rel="stylesheet" href="/assets/css/slick.css">
<link type="text/css" rel="stylesheet" href="/assets/css/jquery-ui.min.css">
<link type="text/css" rel="stylesheet" href="/assets/css/admin.css">
<script type="text/javascript" src="/assets/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/js/slick.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.rwdImageMaps.js"></script>
<script type="text/javascript" src="/assets/js/common.js"></script>
<script type="text/javascript" src="/devScript/common.js"></script>

<script>
// 로그인 유효성 검사
function login_check(f)
{
    if( $(f.id).val() == "" ){
        swalAlert('아이디를 입력해주세요.', '', 'warning', 'id');
        return false;
    }
    if( $(f.password).val() == "" ){
        swalAlert('비밀번호를 입력해주세요.', '', 'warning', 'password');
        return false;
    }
    return true;
}
</script>
</head>
<body>
    <div id="wrap" class="login">
        <div class="login-form-wrap">
            <form method="post" action="{{ route('loginProcess') }}" onsubmit="return login_check(this)">
                {{ csrf_field() }}

                @if(request()->referer)
                    <input type="hidden" name="referer" value="{{ session()->has('previous_url') ? session()->pull('previous_url') : request()->header('referer') }}">
                @endif

                <fieldset>
                    <legend class="hide">Login</legend>
                    <h1 class="login-tit">
                        {{ config('site.common.info.name') }}
                        <strong><img src="/assets/image/admin/img_login_tit.png" alt="Admin Login"></strong>
                    </h1>
                    <div class="login-wrap">
                        <div class="input-box">
                            <div class="form-group">
                                <label for="id">Admin ID *</label>
                                <input type="text" name="id" id="id" class="form-item id">
                            </div>
                            <div class="form-group">
                                <label for="id">Admin PW *</label>
                                <input type="password" name="password" id="password" class="form-item pw">
                            </div>
                        </div>
                        <div class="btn-wrap">
                            <button type="submit" class="btn btn-login"><img src="/assets/image/admin/ic_power.png" alt="">Login</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div> 

    @include('sweetalert::alert')

</body>
</html>