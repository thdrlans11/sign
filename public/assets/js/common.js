$(function (e) {
	$width = $(window).innerWidth(),
    wWidth = windowWidth();

	$(document).ready(function (e) {
		btnTop();
        datepicker();
        gnb();
        mainVisual();
        speakersRolling();
        sponsorRolling();
		boardRolling();
		popup();
        subMenu();
        tabMenu();
        slideTabMenu();
        fileUpload();
        imgMap();
        imgRolling();

		if(wWidth < 1025){		
		}else{
		}
	});

	// resize
	function resEvt() {	
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

function gnb() {
	var max_h = 0,
        h = 0;
    $('.js-gnb > li > ul').each(function(e){
        $(this).height('');
        var h = parseInt($(this).height());
        if (max_h < h) {
            max_h = h;
        }
    });
    $('.js-gnb > li > ul').height(max_h);
	
	$('.js-gnb > li').on('mouseenter',function(e){
        $('#gnb').addClass('active');
    });
    $('.js-gnb').on('mouseleave', function(e){
        $('#gnb').removeClass('active');
    });
}

function mainVisual(){
    if($('.js-main-visual .main-visual-con').length > 1){
        $('.js-main-visual').not('.slick-initialized').slick({
            dots: true,
            arrows: false,
			autoplay: true,
			autoplaySpeed: 3000,
			speed: 1000,
			infinite: true,
            fade: true
		});
    }
}

function boardRolling(){
    if($('.js-board-rolling > .main-board-con').length > 3){
        $('.js-board-rolling').not('.slick-initialized').slick({
            dots: false,
            arrows: true,
			prevArrow: $('.btn-news-prev'),
			nextArrow: $('.btn-news-next'),
            autoplay: true,
            autoplaySpeed: 3000,
            speed: 1000,
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            adaptiveHeight: true
        });
	}
}

function speakersRolling(){
    if($('.js-speakers-rolling .speakers-con').length > 4){
        $('.js-speakers-rolling').not('.slick-initialized').slick({
            dots: false,
            arrows: true,
			prevArrow: $('.btn-speakers-prev'),
			nextArrow: $('.btn-speakers-next'),
            autoplay: true,
            autoplaySpeed: 3000,
            speed: 1000,
            infinite: true,
            slidesToShow: 5,
            slidesToScroll: 1
        });
    } else {
		$('.js-speakers-rolling .slick-arrow').addClass('slick-hidden');
	}
}

function sponsorRolling(){
    $('.js-sponsor-rolling').each(function(e){
		$(this).not('.slick-initialized').slick({
			dots: false,
			arrows: true,
			autoplay: true,
			autoplaySpeed: 3000,
			speed: 1000,
			infinite: true,
			slidesToShow: 6,
			slidesToScroll: 1
		});
    });
}

function subMenu(){
	$('.js-btn-sub-menu').off().on('click', function (e) {
		$(this).next('ul').stop().slideToggle();
		$(this).toggleClass('on');
		$('.js-btn-sub-menu').not(this).removeClass('on').next('ul').stop().slideUp();
		return false;
	});
	$('body').off().on('click', function (e) {
		if ($('.js-sub-menu-list').has(e.target).length == 0) {
			$('.js-btn-sub-menu').removeClass('on');
			$('.js-btn-sub-menu:visible +  ul').stop().slideUp();
		}
	});
}

function tabMenu(){
    $('.js-tab-menu').each(function(e){
        var cnt = $(this).children('li').length;
        $(this).addClass('n'+cnt+'');
    });

	$('.js-tab-menu > li').off('click');	
	$('.js-tab-menu > li').on('click',function(e){	
		var cnt = $(this).index();
		$(this).addClass('on');
		$(this).siblings().removeClass('on');
		$('.js-tab-con').hide().eq(cnt).stop().fadeIn();
		return false;
	});
}

function mTabMenu(){
	var activeTab = $('.js-btn-tab-menu + .js-tab-menu > li.on > a').html();
	$('.js-btn-tab-menu').html(activeTab);
	$('.js-btn-tab-menu').off().on('click',function(e){
		$(this).toggleClass('on');
		$(this).next('ul').stop().slideToggle();
		return false;
	});
	$('.js-btn-tab-menu + .js-tab-menu > li').off().on('click',function(e){		
		var currentTab = $(this).html();
		$('.js-btn-tab-menu').html(currentTab);

		$(this).addClass('on');
		$(this).siblings().removeClass('on');

		$(this).parent('ul').stop().slideUp();
		$('.js-btn-tab-menu').removeClass('on');
	});
}

function slideTabMenu(){
    $('.js-tab-slide > li').on('click',function(e){        
        $(this).addClass('on');
        $('.js-tab-slide > li').not(this).removeClass('on');
        if(!$(this).hasClass('all')){
            var cnt = $(this).index() - 1;
            $('.js-tab-slidecon').stop().slideUp().eq(cnt).stop().slideDown();
        }else{
            $('.js-tab-slidecon').stop().slideDown();
        }
    });
}

function linkTabMenu(h){
    $('.js-tab-link > li > a').on('click',function(e){
        $(this).parent('li').addClass('on');
        $('.js-tab-link > li > a').not(this).parent('li').removeClass('on');
        if($(this).attr('href')){
            $('html, body').stop().animate({
                scrollTop: $(this.hash).offset().top - h
            }, 400);
        }
    });
}

function quickMenu(){
	var currentPosition = parseInt($('.js-quick-menu').css('top')); 
	$(window).scroll(function() { 		
		$('.js-quick-menu').show();
		var position = $(window).scrollTop();
		
		if($(window).scrollTop() + $(window).height() > $(document).height() - 200){ 
			$('.js-quick-menu').stop().animate({'top':position + currentPosition - 200 + "px"},800); 
		}else{
			$('.js-quick-menu').stop().animate({'top':position + currentPosition + "px"},800); 
		}
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

function fileUpload(option=null){
    $('.file-upload').each(function(e){
        $(this).parent().find('.upload-name').attr('readonly','readonly');
        $(this).on('change',function(){
            var fileName = $(this).val();
            $(this).parent().find('.upload-name').val(fileName);
        });
    });
}

function datepicker(){
	if($('.datepicker').length){
		$('.datepicker').datepicker({
			dateFormat : "yy-mm-dd",
			dayNamesMin : ["월", "화", "수", "목", "금", "토", "일"],
			monthNamesShort : ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
			showMonthAfterYear: true, 
			changeMonth : true,
			changeYear : true
		});
	}
}

function popup(){
    $('.js-pop-open').on('click',function(e){
        var popCnt = $(this).attr('href');
        $('html, body').addClass('ovh');
        $(popCnt).css('display','flex');
        return false;
    });
    $('.js-pop-close').on('click',function(e){
        $('html, body').removeClass('ovh');
        $(this).parents('.popup-wrap').css('display','none');
        return false;
    });
    // $('.popup-wrap').off().on('click', function (e){
    // 	if ($('.popup-contents').has(e.target).length == 0){
    // 		$('html, body').removeClass('ovh');
    // 		$('.popup-wrap').css('display','none');
    // 	}
    // });
}

function imgMap(){
    $('img[usemap]').each(function(e){
        $('img[usemap]').rwdImageMaps();
    });
}

function imgRolling(){
    if($('.js-img-rolling').each(function(e){
        $(this).not('.slick-initialized').slick({
            dots: false,
            arrows: true,
            autoplay: true,
            autoplaySpeed: 3000,
            speed: 1000,
            infinite: true,
        });
    }));
}