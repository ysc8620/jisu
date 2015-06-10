<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-5-28
 * Email: ysc8620@163.com
 * QQ: 372613912
 */
if(!file_exists(  '../runtime/conf/config.php')){
    die('conf config no exists');
}
$config = include_once('../runtime/conf/config.php');
if(!file_exists( '../runtime/data/_ppvod/area.php')){
    die('arealist config no exists');
}
$list = @include_once('../runtime/data/_ppvod/area.php');

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

#print_r($config);
$conn = mysql_connect($config['db_host'],$config['db_user'],$config['db_pwd']) or die('mysql connect error');
mysql_select_db($config['db_name']);
mysql_query('SET NAMES '.$config['db_charset']);
$i = 0;
$j=0;
$size = 1000;
do{
    $sql = "SELECT vod_id, vod_area_name,vod_area,vod_cid FROM js_vod WHERE vod_area=0 AND length(vod_area_name)>0 ORDER BY vod_id ASC LIMIT $i, $size";

    $res = mysql_query($sql);
    $count = mysql_num_rows($res);
    echo $count."\r\n";
    if($count < 1){break;}
    while($row = mysql_fetch_assoc($res)){
       $area_name = explode('/', $row['vod_area_name']);
        $area_id = '';
        $data = list_search($list,"list_id={$row['vod_cid']}");
        if($area_name){
            foreach($area_name as $i){
                $name = trim($i);
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

            echo $row['vod_id'].'='.$area_id.', '.$row['vod_cid'].',  '.$row['vod_area_name'].'='.($j++)."\r\n";
           // $vod_class = trim($class_id,'/');
            if($area_id){
                mysql_query("UPDATE js_vod SET vod_area='{$area_id}' WHERE vod_id='{$row['vod_id']}'");
            }
        }
    }
    $i = $i + $size;
}while(true);
mysql_close($conn);