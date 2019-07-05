$(window).on('scroll',function(){
	var ww = $(window).width();
	if($(this).scrollTop()>0 && ww>=992){
		$('.header1').addClass('header1Fixed')
	}else{
		$('.header1').removeClass('header1Fixed')
	}
});
$(function(){
	setTimeout(function(){
		var $tab_li = $('header ul.nav li');
		$tab_li.click(function(){
			$(this).addClass('liOn').siblings().removeClass('liOn');
		});
	},100)
});
function visual_button_scroll_down(){
	$(".scroll_down").each(function(){
		$(this).on('click',function(){
	        var target = $(this).attr('href');
	        var target_top = $(target).offset().top;
	        $('html,body').animate({
	            scrollTop : target_top-100
	        },1000, 'easeInOutQuad');
	        return false;
	    });
	})
}

visual_button_scroll_down()
