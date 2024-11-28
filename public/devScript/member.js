// 로그인 유효성 검사
function login_check(f){

	if( $(f.id).val() == "" ){
		alert("아이디를 입력해주세요");
		$(f.id).focus();
		return false;
	}

	if( $(f.password).val() == "" ){
		alert("비밀번호를 입력해주세요");
		$(f.password).focus();
		return false;
	}

	return true;

}