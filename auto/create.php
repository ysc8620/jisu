<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-6-18
 * Email: ysc8620@163.com
 * QQ: 372613912
 */
$keyword = '2015!@#!';

set_time_limit(0);
if(!file_exists(  dirname(__FILE__) .'/../runtime/conf/config.php')){
    die('conf config no exists');
}
$config = include_once(dirname(__FILE__) .'/../runtime/conf/config.php');
if(!file_exists( dirname(__FILE__) .'/../runtime/data/_ppvod/area.php')){
    die('arealist config no exists');
}
$area = @include_once(dirname(__FILE__) .'/../runtime/data/_ppvod/area.php');

if(!file_exists( dirname(__FILE__) .'/../runtime/data/_ppvod/list.php')){
    die('list config no exists');
}
$list = @include_once(dirname(__FILE__) .'/../runtime/data/_ppvod/list.php');

if(!file_exists( dirname(__FILE__) .'/../runtime/data/_ppvod/listtree.php')){
    die('list config no exists');
}
$listtree = @include_once(dirname(__FILE__) .'/../runtime/data/_ppvod/listtree.php');

// 自动更新html
/*模拟浏览器*/
$user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)";
$header = array ();

function load($url){
    echo $url."\n";
    global $header,$agent;
    $curl = curl_init (); // 启动一个CURL会话
    curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
    curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
    curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
    curl_setopt ( $curl, CURLOPT_USERAGENT, $agent ); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  //设置头信息的地方
    @curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
    curl_setopt ( $curl, CURLOPT_HTTPGET, 1 ); // 发送一个常规的GET请求
    curl_setopt ( $curl, CURLOPT_TIMEOUT, 120 ); // 设置超时限制防止死循环
    curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 不显示返回的Header区域内容
    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
    $res = curl_exec ( $curl ); // 执行操作
    if (curl_errno ( $curl )) {
        return '失败:Errno' . curl_error ( $curl );
    }
    curl_close ( $curl ); // 关闭CURL会话
    #unset($res);
    return $res;
}

require_once dirname(__FILE__) .'/db.php';


/**----------------------------------------------------------
 * 在数据列表中搜索
+----------------------------------------------------------
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}
$url = $config['site_url'] . 'index.php?';
// 首页
 //   if(!file_exists($root . '/index.html')){
        load ($url."m=index&a=index");
//    }
// 分类页
foreach($listtree as $cate){
    load( $url.'m=vod&a=show&list_dir='.$cate['list_dir'] );

}
