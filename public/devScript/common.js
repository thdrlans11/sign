// ajax Setup
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function(){

    // 텍스트 박스에 앞뒤 공백을 없애준다.
	$("input[type='text'], textarea").blur(function(){
		var text = $.trim($(this).val());
		$(this).val(text);
	});

	// datepicker
	jQuery(function(a){a.datepicker.regional.ko={closeText:"닫기",prevText:"이전달",nextText:"다음달",currentText:"오늘",monthNames:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],monthNamesShort:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],dayNames:["일","월","화","수","목","금","토"],dayNamesShort:["일","월","화","수","목","금","토"],dayNamesMin:["일","월","화","수","목","금","토"],weekHeader:"Wk",dateFormat:"yy-mm-dd",firstDay:0,isRTL:false,showMonthAfterYear:false,yearSuffix:"년"};a.datepicker.setDefaults(a.datepicker.regional.ko)});
	$('.dateCalendar').datepicker({showMonthAfterYear:true, changeMonth: true,	changeYear: true,	dateFormat: "yy-mm-dd", yearRange: 'c-100:c+3'});

    $(document).on("blur keyup",".engOnly", function() {
		var ex = /[^A-Za-z_\`\~\!\@\#\$\%\^\&\*\(\)\-\=\+\\\{\}\[\]\'\"\;\:\<\,\>\.\?\/\s]/gm;
		  if( ex.test( $(this).val() ) ) {
				alert('영문만 입력해 주세요.');
			  	$(this).val( $(this).val().replace( ex, '' ) ).focus();
		  }
	});

	$(document).on("blur keyup",".numOnly", function() {
		var ex = /[^0-9\+\-\s]/gm;
		if( ex.test( $(this).val() ) ) {
			alert('숫자만 입력해주세요.');
			$(this).val( $(this).val().replace( ex, '' ) ).focus();
		}
	});

    $(document).on("blur keyup",".korOnly", function() {
		var ex = /[^ㄱ-ㅎ|ㅏ-ㅣ|가-힣\s]/gm;
		if( ex.test( $(this).val() ) ) {
			alert('한글만 입력해 주세요.');
			$(this).val( $(this).val().replace( ex, '' ) ).focus();
		}
	});

	$(".Load_Base_fix").on('click',function(){
		var W_custom = $(this).attr('Wsize');
		var H_custom = $(this).attr('Hsize');
		var T_custom = $(this).attr('Tsize');
		var Reload = $(this).attr('Reload');
		var Browser_W = $(window).width();
		var Browser_H = $(window).height();

		var Active = $(this).attr('Active') ?? 'Y';
		if( Active == "N" ){ return false;}

		if((Browser_H-50)<H_custom){
			H_custom = "90%;";
		}
		if((Browser_W-50)<W_custom){
			W_custom = "80%;";
		}
		if(Reload=='Y'){
			$(".Load_Base_fix").colorbox({iframe:true, transition:"fade", width:W_custom, maxWidth:"100%", height:H_custom, maxHeight:"100%", top:T_custom,speed:150,fixed:true,closeButton:false,overlayClose:true,scrolling:true,escKey:true,opacity:0.5,reposition:true,onClosed:function(){
				location.reload();
			}});	
		}else if(Reload=='L'){
			$(".Load_Base_fix").colorbox({iframe:true, transition:"fade", width:W_custom, maxWidth:"100%", height:H_custom, maxHeight:"100%", top:T_custom,speed:150,fixed:true,closeButton:false,overlayClose:true,scrolling:true,escKey:true,opacity:0.5,reposition:true,onClosed:function(){
				
			}});
		}else{
			$(".Load_Base_fix").colorbox({iframe:true, transition:"fade", width:W_custom, maxWidth:"100%", height:H_custom, maxHeight:"100%", top:T_custom,speed:150,fixed:true,closeButton:false,overlayClose:true,scrolling:true,escKey:true,opacity:0.5,reposition:true});
		}
	});
	
	$(document).on("click",".colorClose",function(){
		parent.$.colorbox.close();
	});

	$("#allCheck").click(function(){
		if( $(this).is(":checked") ){
			$("input:checkbox[name='listSid[]']").prop('checked',true);
		}else{
			$("input:checkbox[name='listSid[]']").prop('checked',false);
		}
	});

});

function toAllOpper(f){
	var orgStr = $(f).val();
	if(orgStr != ''){
		var result = orgStr.toUpperCase();
		$(f).val(result);
	}
}

function file_check(file, name){
	if( file && $("#"+name).is(":checked") == false ){
		alert("이미 첨부되어있는 파일이 있습니다. 삭제 체크 후 변경해주세요.");
		return false;
	}else{
		return true;
	}
}

function openDaumPostcode(kind){
	if( kind == "office" ){
		var space = "office_";
	}else if(kind == 'recipient') {
		var space = 'recipient_'
	}else if(kind == 'order') {
		var space = 'order_'
	}else if( kind == "post" ){
		var space = "post_";
	}else if( kind == "none" ){
		var space = "";
	}else{
		var space = "home_";
	}

	new daum.Postcode({
		oncomplete: function(data) {
			$(":text[name='"+space+"zipcode']").val(data.zonecode);
			$(":text[name='"+space+"addr']").val(data.address).focus();
		}
	}).open();
}

// file accept check
const fileAcceptCheck = (_this, fileTxt, msg = null) => {
    const extArr = $(_this).attr('accept').replace(/\s+/g, '').split(',');
    const ext = '.' + $(_this).val().split('.').pop().toLowerCase();

    if ($.inArray(ext, extArr) == -1) {
        if (!msg) {
            msg = (ext + ' 파일은 업로드 하실 수 없습니다.');
        }
        
        $(_this).val('');
        $('#' + fileTxt).val('');
        alert(msg);
        return false;
    }

    return true;
}

const swalAlert = function(title, msg, type, target){

	formattedWidth = swalTitleWidth(title);
	
	Swal.fire({ 
		icon: type,
		title: title, 
		text: msg,
		width: formattedWidth,
		allowOutsideClick: false, 
		didClose: () => {
			$("#"+target).focus(); 
		}
	});
}

const swalConfirm = function(title, msg, callback){

	formattedWidth = swalTitleWidth(title);

	Swal.fire({ 
		icon: 'question',
		title: title, 
		text: msg, 
		width: formattedWidth,
		allowOutsideClick: false,
		confirmButtonText: '확인',
		showCancelButton: true,
		cancelButtonText: '취소'
	}).then( result => {
		if( result.isConfirmed ){
			if (callback) { callback(); }
		}
	});
}

const swalTitleWidth = function(title){

	inputText = title;
	font = "1rem";

	canvas = document.createElement("canvas");
	context = canvas.getContext("2d");
	context.font = font;
	width = context.measureText(inputText).width;
	formattedWidth = Math.ceil(width) + 200;

	return formattedWidth;

}
