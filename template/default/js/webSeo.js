// 头部
/*<div id="top"><div id="top"><div class="wrapper"><div class="fl"><a href="/" class="home" target="_blnak">首页</a><i class="sq">|</i><a href="/youxi/" target="_blnak">游戏</a><a href="/yinyue/" target="_blnak">音乐</a><a href="/meishi/" target="_blnak">美食</a><a href="/book/" target="_blnak">小说</a><a href="http://tuan.114la.com/" target="_blnak">团购</a><a href="http://app.114la.com/" target="_blank">实用工具</a></div>*/
var topMd = '<div id="top"><div id="top"><div class="wrapper"><div class="fl"><a href="/" class="home" target="_blnak">首页</a></div><div class="fr"><a href="/index.php?s=m=gb&a=show" target="_blnak">意见反馈</a><i class="sq">|</i><a href="javascript:;" title="收藏影视" id="collet">收藏本页</a></div></div></div></div>';
$("body").prepend(topMd);


//底部
var y=new Date().getFullYear();
var bottomMd = '<div id="footer"><div class="wrapper clearfix"><dl><dt>热门站点</dt><dd><a href="/dianying/">最新好看的电影</a><a href="/dianshi/">电视剧大全</a></dd></dl><dl><dt>热门排行</dt><dd><a href="/top/dianying-kehuan.html">科幻电影排行榜</a><a href="/top/dianying-jingsong.html">惊悚电影排行榜</a><a href="/top/dianying-xiju.html">喜剧电影排行榜</a><a href="/top/movie.html">电影排行榜</a><a href="/top/dongman.html">动漫电影排行榜</a><a href="/top/dianshi-hanju.html">韩国电视剧排行榜</a><a href="/top/dianshi-meiju.html">美国电视剧排行榜</a></dd></dl><dl><dt>节目分类</dt><dd><a href="/dianying/0-14-0.html">日本电影</a><a href="/dianying/0-12-0.html">香港电影</a><a href="/dianying/0-11-0.html">美国电影</a><a href="/dianying/0-13-0.html">韩国电影</a><a href="/dianshi/0-6-0.html">泰国电视剧</a><a href="/dianshi/0-8-0.html">好看的美剧</a><a href="/dianshi/0-5-0.html">韩国电视剧</a><a href="/dianshi/0-3-0.html">香港电视剧</a><a href="/dongman/0-22-0.html">日本动漫</a><a href="/zongyi/0-28-0.html">韩国综艺节目</a><a href="/zongyi/0-27-0.html">台湾综艺节目</a></dd></dl><p class="bhr"></p><ul class="fl"><li><a href="javascript:void(0)" onclick="divcenter()">意见反馈</a>|</li></ul><p class="fr">©2005- '+y+' 影视导航</p></div></div>';
$("body").append(bottomMd);

//登录
var userlog = '<a href="/?ct=login">登录</a><a href="/?ct=register">注册</a>';
$(".userlog").html('');

//影视名站
var siteLink ='<dt>影视名站<i></i></dt><dd><a href="http://v.qq.com/" target="_blnak" rel="nofollow">腾讯视频</a><i>|</i><a href="http://www.youku.com/" target="_blnak" rel="nofollow">优酷网</a><i>|</i><a href="http://www.iqiyi.com/" target="_blnak" rel="nofollow">爱奇艺</a><i>|</i><a href="http://www.letv.com/" target="_blnak" rel="nofollow">乐视网</a><i>|</i><a href="http://tv.sohu.com/" target="_blnak" rel="nofollow">搜狐视频</a><i>|</i><a href="http://www.kankan.com/" target="_blnak" rel="nofollow">迅雷看看</a><i>|</i><a href="http://www.v1.cn/" target="_blnak" rel="nofollow">第一视频</a><i>|</i><a href="http://www.tudou.com/" target="_blnak" rel="nofollow">土豆网</a><i>|</i><a href="http://www.m1905.com/vod/" target="_blnak" rel="nofollow">电影网</a><i>|</i><a href="http://www.pps.tv/" target="_blnak" rel="nofollow">PPS网络电视</a><i>|</i><a href="http://www.wasu.cn/" target="_blnak" rel="nofollow">华数TV</a><i>|</i><a href="/live/bjws.html" target="_blnak" rel="nofollow">电视直播</a><a href="/website/" target="_blnak" class="more" rel="nofollow">更多&gt;&gt;</a></dd>';
$(".siteLink").html(siteLink);