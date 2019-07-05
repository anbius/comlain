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
