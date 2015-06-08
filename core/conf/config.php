<?php
$config = require './runtime/conf/config.php';
$array = array(
	'USER_AUTH_KEY'=>'jsvod',// 用户认证SESSION标记
	'NOT_AUTH_ACTION'=>'index,show,add,main',// 默认无需认证操作
	'REQUIRE_AUTH_MODULE'=>'admin,list,vod,news,user,collect,data,upload,link,ads,cache,create,tpl,cm,gb,tag,special,nav,side,pic',// 默认需要认证模型
    'URL_PATHINFO_DEPR'=>'-',
	'APP_GROUP_LIST'=>'admin,home,plus,user',//项目分组
	'TMPL_FILE_DEPR'=>'_',//模板文件MODULE_NAME与ACTION_NAME之间的分割符，只对项目分组部署有效
	'LANG_SWITCH_ON'=>true,// 多语言包功能
	'LANG_AUTO_DETECT'=>false,//是否自动侦测浏览器语言
	'URL_CASE_INSENSITIVE'=>true,//URL是否不区分大小写 默认区分大小写
    'DB_FIELDTYPE_CHECK'=>true, //是否进行字段类型检查
	'DATA_CACHE_SUBDIR'=>true,//哈希子目录动态缓存的方式
    'TMPL_ACTION_ERROR'     => './public/jump/jumpurl.html', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => './public/jump/jumpurl.html', // 默认成功跳转对应的模板文件
	'DATA_PATH_LEVEL'=>2,
  	'url_model' => '3',
	'play_player' => array (
        'qiyi' => array('01','奇艺'),
        'youku' => array('02','优酷'),
        'ku6' => array('03','酷六'),
        'pptv' => array('04','PPTV'),
        'ifeng' =>array('05','凤凰网'),
        'tudou' => array('06','土豆'),
        'sina' => array('07','新浪'),
        'xunlei' => array('08','迅雷'),
        'letv' => array('09','乐视'),
        'kumi' => array('10','酷米'),
        'tianyi' => array('11', '天意'),
        'leshi' => array('12','乐视'),
        'm1905' => array('13', 'M905'),
        'taomi' => array('14','淘米'),
        '56com' => array('15','56'),
        'cntv' => array('16','CNTV'),
        'sohu' =>   array('17','搜狐'),
        'pps' => array('18','PPS'),
        'qq' => array('19','QQ'),
        'huashu' => array('20','华数'),
        'fengxing' => array('21','风行'),
        'zhejiang' => array('22','新篮网'),
        'cztv' => array('23','新篮网'),
        'beva' => array('24','贝瓦网'),
        'tangdou' => array('25','糖豆'),
        'baofeng' => array('26','暴风'),
        'boosj' => array('27','播视网'),
        'imgo' => array('28','芒果TV'),
        'v360' => array('29','360影视'),
        'brtn' => array('30','北京'),
        'other' => array('31','其他'),
	),
	//'APP_DEBUG'           =>true,    // 是否开启调试模式
    //'SHOW_RUN_TIME'		=> true,   // 运行时间显示
    //'SHOW_ADV_TIME'		=> true,   // 显示详细的运行时间
    //'SHOW_DB_TIMES'		=> true,   // 显示数据库查询和写入次数	
);
return array_merge($config,$array);
?>