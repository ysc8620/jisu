<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-6-18
 * Email: ysc8620@163.com
 * QQ: 372613912
 */
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
$url = 'http://www.php369.com/index.php?';
$time = @file_get_contents(dirname(__FILE__).'update_time.log');
$time = intval($time);
file_put_contents(dirname(__FILE__).'/update_time.log', time());
if(isset($argv[1])){
    $time = $argv[1];
}
$i = 0;
$j=0;
$size = 1000;
do{
    $list_data = DB::init()->getList("SELECT * FROM js_vod WHERE vod_addtime>'$time' ORDER BY vod_id ASC LIMIT $i, $size");
    print "SELECT * FROM js_vod WHERE vod_addtime>'$time' ORDER BY vod_id ASC LIMIT $i, $size \n";
    if(count($list_data) < 1){break;}
    foreach($list_data as $row){
        $id = $row['vod_id'];
        $class = list_search($list,"list_id={$row['vod_cid']}");


        //
        load ($url."m=vod&a=read&list_dir={$class[0]['list_dir']}&id={$id}")."\n";
    }

    $i = $i + $size;

}while(true);

echo "ok";