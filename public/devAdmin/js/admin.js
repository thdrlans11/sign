$(function (e) {
	$width = $(window).innerWidth(),
    wWidth = windowWidth();

	$(document).ready(function (e) {
		btnTop();
        wideLayout();
        toggleCon();

		if(wWidth < 1025){		
		}else{		
		}
		
		resEvt();
	});

	// resize
	function resEvt() {	      
		if (wWidth < 1025) {
		} else {	
		}

		if(wWidth < 769){
			touchHelp();
		}
	}

	$(window).resize(function (e) {
		$width = $(window).innerWidth(),
		wWidth = windowWidth();
		resEvt();
	});

	$(window).scroll(function(e){
		if($(this).scrollTop() > 200){
			$('.js-btn-top').addClass('on');
		}else{
			$('.js-btn-top').removeClass('on');
		}
	});
});

function Mobile() {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function windowWidth() {
	if ($(document).innerHeight() > $(window).innerHeight()) {
		if (Mobile()) {
			return $(window).innerWidth();
		} else {
			return $(window).innerWidth() + 17;
		}
	} else {
		return $(window).innerWidth();
	}
}

function subConHeight(){
    $(document).ready(function(e){
        var subConHeight = $(window).outerHeight() - $('.js-header').outerHeight() - $('#footer').outerHeight();
        setTimeout(function(e){
            $('.sub-contents').css('min-height',subConHeight);
        },100);
    });	
}

function btnTop(){
	$('.js-btn-top').on('click',function(e){
	  $('html, body').stop().animate({'scrollTop':0},400);
		return false;
	});
}

function touchHelp(){
	$('.scroll-x').each(function(e){
		if($(this).height() < 180){
			$(this).addClass('small');
		}
		$(this).scroll(function(e){
			$(this).removeClass('touch-help');
		});
	});
}

function wideLayout(){
    $('.js-btn-wide').on('click',function(e){
        var btnText = $(this).text();
        if($(this).hasClass('on')){
            $(this).removeClass('on').text('와이드화면 전환');
            $('.inner-layer, #container, #gnb, #footer').removeClass('wide');
        }else{
            $(this).addClass('on').text('기본화면 전환');
            $('.inner-layer, #container, #gnb, #footer').addClass('wide');
        }
    });
}

function toggleCon(){
    $('.js-btn-toggle').on('click',function(e){
        if($(this).hasClass('on')){
            $(this).removeClass('on').text('통계현황 열기');
            $(this).parent().next('.js-toggle-con').stop().slideUp();
        }else{
            $(this).addClass('on').text('통계현황 닫기');
            $(this).parent().next('.js-toggle-con').stop().slideToggle();
        }
    });
}