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
$i = 1;
$size = 20;
do{
// 入库
$data['time'] = 0;
$data['page'] = $i;
$data['size'] = $size;
asort($data);
$str = '';
foreach($data as $key=>$val){
    $str .= $key.'='.$val.'&';
}
$str .= $keyword;

$data['md5'] = md5($str);
$url = "http://www.php369.com/php.php?".http_build_query($data);
$html = load($url);
$list = json_decode($html);
    if(!$list){
        break;
    }
    print_r($list);

//    if(count($list) < $size){
//        break;
//    }echo count($list);
$i++;

}while(true);

return;
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
$url = 'http://www.kuaikan123.com/index.php?';
$time = file_get_contents(dirname(__FILE__).'/last_time.log');



file_put_contents(dirname(__FILE__).'/last_time.log', date("Y-m-d H:i:s"));
if(isset($argv[1])){
    $time = $argv[1];
}
$i = 0;
$j=0;
$size = 10;
do{
    $list_data = DB::init()->getList("SELECT * FROM js_vods WHERE update_time>'$time' ORDER BY id ASC LIMIT $i, $size");
    print "SELECT * FROM js_vods WHERE update_time>'$time' ORDER BY id ASC LIMIT $i, $size";
    if(count($list_data) < 1){break;}
    foreach($list_data as $row){
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
        //print_r($data);
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
            'vod_pic'=>$row['pic'],
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
            echo 'update';
            $id = $vod['vod_id'];
            DB::init()->update($data, $vod['vod_id']);
        }else{
            echo 'insert';
            DB::init()->insert($data);
            $id = mysql_insert_id();
        }

        //
        load ($url."m=vod&a=read&list_dir={$class[0]['list_dir']}&id={$id}");
        print $i.'='.$row['id'].'=' .$row['title'].'，'.$row['class_name'].'=' .$vod_class.'，'.$row['area'].'=' .$area_id."\r\n";
    }

    $i = $i + $size;

}while(true);



// 自动更新html
// 首页
//
load($url.'m=index&a=index');

foreach($listtree as $cate){
    load( $url.'m=vod&a=show&list_dir='.$cate['list_dir'] );
    load ($url.'m=vod&a=type&list_dir='.$cate['list_dir'] );
    foreach($cate['son'] as $son){
        load ($url.'m=vod&a=type&list_dir='.$cate['list_dir'].'&class_id='.$son['list_id'] );
    }
}

/*

rewrite ^/(dianying|dianshi|dongman|zongyi)/(\d+)-(\d+)-(\d+)(-(\d+))?.html /index.php?m=vod&a=type&list_dir=$1&class_id=$2&area=$3&year=$4&p=$6 last;
rewrite ^/(dianying|dianshi|dongman|zongyi)/(\d+).html$ /index.php?m=vod&a=read&list_dir=$1&id=$2 last;
rewrite ^/(dianying|dianshi|dongman|zongyi)/?$ /index.php?m=vod&a=show&list_dir=$1 last;
rewrite ^/test/(.*)$ /index.php?s=home-test-index-page-$1 last;
*/