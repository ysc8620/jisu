<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-6-18
 * Email: ysc8620@163.com
 * QQ: 372613912
 */

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

$time = file_get_contents(dirname(__FILE__).'/last_time.log');

file_put_contents(dirname(__FILE__).'/last_time.log', date("Y-m-d H:i:s"));
if(isset($argv[1])){
    $time = $argv[1];
}
$i = 0;
$j=0;
$size = 1000;
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
            'vod_class_name'=>$row['class_name'],
            'vod_name'=>$row['title'],
            'vod_actor'=>$row['actor'],
            'vod_director'=>$row['director'],
            'vod_continu'=>$row['update_remark'],
            'vod_watch'=>$row['watch_str'],
            'vod_pic'=>$row['pic'],
            'vod_isfilm'=>$row['is_finish']?'3':'1',
            'vod_area'=>$area_id,
            'vod_area_name'=>$row['area'],
            'vod_year'=>$row['year'],
            'vod_content'=>$row['content'],
            'vod_addtime'=>$row['addtime'],
            'vod_play'=>$row['play_type'],
            'vod_url'=>$row['jump_url'],
            'vod_reurl'=>$row['url'],
            'jump_info'=>$row['jump_info'],
            'vod_gold'=>$row['gold'],
            'vod_golder'=>$row['golder'],
            'vod_filmtime'=>$row['film_time']
        );
        // print_r($row);
        // break;
        $vod = Db::init()->getOne('SELECT vod_id, vod_reurl FROM js_vod WHERE vod_reurl="'.$row['url'].'"');
        if($vod){
            DB::init()->update($data, $vod['vod_id']);
        }else{
            DB::init()->insert($data);
        }
        print $i.'='.$row['id'].'=' .$row['title'].'，'.$row['class_name'].'=' .$vod_class.'，'.$row['area'].'=' .$area_id."\r\n";
    }

    $i = $i + $size;

}while(true);