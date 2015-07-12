<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-6-18
 * Email: ysc8620@163.com
 * QQ: 372613912
 */
date_default_timezone_set('asia/shanghai');
$keyword = '2015!@#!';
header("Content-type: text/html; charset=utf-8");

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
$time = date("Y-m-d");
if(isset($_GET['time']) ){
    if($_GET['time']){
        $time = $_GET['time'];
    }

}


$i = isset($_GET['i'])?intval($_GET['i']):1;
$i = $i<1?1:$i;
$size = 5;
do{
    echo "i=$i; time=$time<br/>";
// 入库
    $data =array();
    $data['time'] = strtotime($time);
    $data['page'] = $i;
    $data['size'] = $size;
    asort($data);
    $str = '';
    foreach($data as $key=>$val){
        $str .= $key.'='.$val.'&';
    }

    $str .= $keyword;

    $data['md5'] = md5($str);
    $url2 = "http://www.php369.com/php.php?".http_build_query($data);
    logs(dirname(__FILE__).'/import.log',$url2);
    $html = load($url2);
    $list_data = (array)json_decode($html);
    if($list_data['error'] != 200){
        // header("Location: /auto/create.php");
        break;
    }

    foreach($list_data['list'] as $row){

        $row = (array)$row;

        //`vod_id`, `vod_cid`, `vod_class`, `vod_class_name`, `vod_name`, `vod_title`, `vod_keywords`, `vod_color`, `vod_actor`,
        //`vod_director`, `vod_content`, `vod_watch`, `vod_pic`, `vod_area`, `vod_area_name`, `vod_language`, `vod_year`,
        //`vod_continu`, `vod_total`, `vod_isend`, `vod_addtime`, `vod_hits`, `vod_hits_day`, `vod_hits_week`, `vod_hits_month`,
        //`vod_hits_lasttime`, `vod_stars`, `vod_status`, `vod_up`, `vod_down`, `vod_play`, `vod_server`, `vod_url`, `vod_inputer`,
        //`vod_reurl`, `vod_jumpurl`, `jump_info`, `vod_letter`, `vod_skin`, `vod_gold`, `vod_golder`, `vod_isfilm`, `vod_filmtime`,
        //`vod_length`, `vod_weekday`, `sort`, `re_sort`, `hot_sort`, `top_sort`

        /*
         `id`, `title`, `pic`, `old_pic`, `website_id`, `site_id`, `cid_ids`, `cid_name`, `class_ids`, `class_name`, `area`, `year`,
        `actor`, `director`, `play_make`, `gold`, `golder`, `is_finish`, `update_remark`, `watch_str`, `is_vip`, `film_time`, `url`,
        `play_type`, `jump_url`, `jump_info`, `content`, `status`, `addtime`, `uptime`, `is_index`
         */

        $vod_class = $class_id = '';

        $class = explode('/', $row['class_name']);
        if($class){
            foreach($class as $c){
                $name = trim($c);
                $data = list_search($list,"list_name={$name}");
                if($data){
                    $new_name = list_search($data,"list_pid={$row['cid_ids']}");
                    if($new_name){
                        $new_cid = $new_name[0]['list_id'];
                        $class_id .= $new_cid.'/';
                    }
                }
            }

            $vod_class = trim($class_id,'/');
        }


        $area_name = explode('/', $row['area']);
        $area_id = '';
        $data = list_search($area,"list_id={$row['cid_ids']}");

        if($area_name){
            foreach($area_name as $a){
                $name = trim($a);
                if($data){
                    $new_name = list_search($data,"name={$name}");
                    if($new_name){
                        $area_id = $new_name[0]['id'];
                        break;
                    }else{
                        $new_name = list_search($data,"alias_name=/{$name}/");
                        if($new_name){
                            $area_id = $new_name[0]['id'];
                            break;
                        }
                    }

                }
            }
        }

        $data = array(
            'vod_cid'=>$row['cid_ids'],
            'vod_class'=>$vod_class,
            'vod_class_name'=>strip_tags($row['class_name']),
            'vod_name'=>$row['title'],
            'vod_actor'=>strip_tags($row['actor']),
            'vod_director'=>strip_tags($row['director']),
            'vod_continu'=>strip_tags($row['update_remark']),
            'vod_watch'=>strip_tags($row['watch_str']),
            'vod_pic'=>$row['pic']?$row['pic']:$row['old_pic'],
            'vod_isfilm'=>$row['is_finish']?'3':'1',
            'vod_area'=>$area_id,
            'vod_area_name'=>strip_tags($row['area']),
            'vod_year'=>strip_tags($row['year']),
            'vod_content'=>strip_tags($row['content']),
            'vod_addtime'=>$row['addtime'],
            'vod_play'=>$row['play_type'],
            'vod_url'=>$row['jump_url'],
            'vod_reurl'=>$row['url'],
            'jump_info'=>$row['jump_info'],
            'vod_gold'=>strip_tags($row['gold']),
            'vod_golder'=>strip_tags($row['golder']),
            'vod_filmtime'=>$row['film_time']
        );

        $class = list_search($list,"list_id={$row['cid_ids']}");
        $vod = Db::init()->getOne('SELECT vod_id, vod_reurl FROM js_vod WHERE vod_reurl="'.$row['url'].'"');
        if($vod){
            echo "update\n";
            $id = $vod['vod_id'];
            DB::init()->update($data, $vod['vod_id'],true);
        }else{
            echo "insert-{$row['id']}\n";
            DB::init()->insert($data,true);
            $id = mysql_insert_id();
        }

        load($url."m=vod&a=read&list_dir={$class[0]['list_dir']}&id=$id");
    }

    if(count($list_data['list']) < $size){
        //  return header("Location: /auto/create.php");
        break;

    }
    unset($list_data);
    unset($html);

    //break;

    $i++;

    echo <<<DOC
<script type='text/javascript'>

setTimeout(function(){ window.location.href="?i=$i&time=$time"}, 100);</script>
DOC;

    die();
}while(false);

$url = $config['site_url'] . 'index.php?';
// 首页
//   if(!file_exists($root . '/index.html')){
load ($url."m=index&a=index");
//    }
// 分类页
foreach($listtree as $cate){
    load( $url.'m=vod&a=show&list_dir='.$cate['list_dir'] );

}

