function getDesc(){
    var meta = document.getElementsByTagName('meta');
    var _desc = '';
    for(i in meta){
         if(typeof meta[i].name!="undefined"&&meta[i].name.toLowerCase()=="description"){
           _desc = meta[i].content.substr(0, 120) + '...';
         }
    }
    return _desc;
}
function getKwords(){
    var meta = document.getElementsByTagName('meta');
    var _summary = '';
    for(i in meta){
         if(typeof meta[i].name!="undefined"&&meta[i].name.toLowerCase()=="keywords"){
            _summary = meta[i].content;
         }
    }
    return _summary;
}
function getImage(){
    var meta = document.getElementsByTagName('meta');
    var _pic = '';
    for(i in meta){
         if(typeof meta[i].name!="undefined"&&meta[i].name=="shareImgUrl"){
            _pic = meta[i].content;
         }
    }
    return _pic;
}
var _title,_source,_sourceUrl,_pic,_showcount,_site,_url;
   // new QRCode(document.getElementById("qrcode"), "https://m.longshangyun.com/baike/details.html?id=" + GetQueryString('id'));
//分享到新浪微博   
function shareToSinaWB(event){
    var _shareUrl = 'http://v.t.sina.com.cn/share/share.php?appkey=3714563200';     //真实的appkey，必选参数
        _shareUrl += '&url='+ encodeURIComponent(_url||document.location);     //参数url设置分享的内容链接|默认当前页location，可选参数
        _shareUrl += '&title=' + encodeURIComponent(_title||document.title);    //参数title设置分享的标题|默认当前页标题，可选参数
        _shareUrl += '&source=' + encodeURIComponent(_source||'');
        _shareUrl += '&sourceUrl=' + encodeURIComponent(_sourceUrl||'');
        _shareUrl += '&content=' + 'utf-8';   //参数content设置页面编码gb2312|utf-8，可选参数
        _shareUrl += '&pic=' + encodeURIComponent(_pic||getImage());  //参数pic设置图片链接|默认为空，可选参数
        window.open(_shareUrl,'_blank');
}
//分享到QQ空间
function shareToQzone(event){
    var _shareUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?';
        _shareUrl += 'url=' + encodeURIComponent(_url||document.location);   //参数url设置分享的内容链接|默认当前页location
        _shareUrl += '&showcount=' + _showcount||0;      //参数showcount是否显示分享总数,显示：'1'，不显示：'0'，默认不显示
        _shareUrl += '&desc=' + encodeURIComponent(getDesc()||'分享的描述');    //参数desc设置分享的描述，可选参数
        _shareUrl += '&summary=' + encodeURIComponent(getKwords()||'分享摘要');    //参数summary设置分享摘要，可选参数
        _shareUrl += '&title=' + encodeURIComponent(_title||document.title);    //参数title设置分享标题，可选参数
        _shareUrl += '&site=' + encodeURIComponent(_site||'');   //参数site设置分享来源，可选参数
        _shareUrl += '&pics=' + encodeURIComponent(_pic||getImage());   //参数pics设置分享图片的路径，多张图片以＂|＂隔开，可选参数
        window.open(_shareUrl,'_blank');
}
//分享到qq
function shareToqq(event){
    var _shareUrl = 'https://connect.qq.com/widget/shareqq/iframe_index.html?';
        _shareUrl += 'url=' + encodeURIComponent(_url||location.href);   //分享的链接
        _shareUrl += '&title=' + encodeURIComponent(_title||document.title);     //分享的标题
    window.open(_shareUrl,'_blank');
}

function GetQueryString(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");  //寻找&+url参数名=参数值+&.&可以不存在
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  decodeURIComponent(r[2]); return null;
}
