$(window).on('scroll',function(){
	//返回顶部
	if($(this).scrollTop()>500){
		$('.backTop').css({"display":"block"});
	}else{
		$('.backTop').css({"display":"none"});
	}
});

$(function(){
	//咨询页跳转
	$('.detailOnclickALink').click(function(){
		var reg = /^[1-9]+[0-9]*]*$/;
		var dataId = $(this).attr('data-id');
		if(reg.test(dataId)){
			var domain = window.location.host;
			window.open('http://' + domain + '/baike/details.html?id=' + dataId,'_blank');
		}
	});
});

function backTop(){
	$("html,body").animate({scrollTop:0},1000);
}
