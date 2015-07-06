<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-7-6
 * Email: ysc8620@163.com
 * QQ: 372613912
 */

header("Content-type: application/json");
set_time_limit(0);
if(!file_exists(  dirname(__FILE__) .'/../runtime/conf/config.php')){
    die('conf config no exists');
}
$config = include_once(dirname(__FILE__) .'/../runtime/conf/config.php');

require_once dirname(__FILE__) .'/db.php';
$data['time'] = intval($_GET['time']);
$data['size'] = intval($_GET['size']);
$data['page'] = intval($_GET['page']);

$data['page'] = $data['page']<1?1:$data['page'];
$data['size'] = $data['size']<5?5:$data['size'];
$data['size'] = $data['size']>100 ? 100:$data['size'];
$md5 = trim($_GET['md5']);
$keyword = '2015!@#!';
asort($data);
$str = '';
foreach($data as $key=>$val){
    $str .= $key.'='.$val.'&';
}

$str .= $keyword;
$i = ($data['page'] -1 ) * $data['size'];
if($_GET['test'] == 'test'){
    print_r($str);
    print_r(md5($str));
    echo "SELECT * FROM js_vods WHERE update_time>'$time' ORDER BY id ASC LIMIT $i, {$data['size']}";
}
if(md5($str) != $md5){
    die(json_encode(array('error'=>100)));
}
$time = date("Y-m-d H:i:s", $data['time']);

$list_data = DB::init()->getList("SELECT * FROM js_vods WHERE update_time>'$time' ORDER BY id ASC LIMIT $i, {$data['size']}");
echo json_encode(array('error'=>200,'list'=>$list_data));