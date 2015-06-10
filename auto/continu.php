<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-6-10
 * Email: ysc8620@163.com
 * QQ: 372613912
 */

if(!file_exists(  '../runtime/conf/config.php')){
    die('conf config no exists');
}
$config = include_once('../runtime/conf/config.php');
if(!file_exists( '../runtime/data/_ppvod/list.php')){
    die('list config no exists');
}
    $conn = mysql_connect($config['db_host'],$config['db_user'],$config['db_pwd']) or die('mysql connect error');
    mysql_select_db($config['db_name']);
    mysql_query('SET NAMES '.$config['db_charset']);
    $i = 0;
    $j=0;
    $size = 1000;
do{
    $sql = "SELECT vod_id,vod_cid, vod_continu FROM js_vod WHERE vod_cid=2 and length(vod_continu)>0 ORDER BY vod_id ASC LIMIT $i, $size";

    $res = mysql_query($sql);
    $count = mysql_num_rows($res);
    echo $count."\r\n";
    if($count < 1){break;}
    while($row = mysql_fetch_assoc($res)){
        $vod_continu = str_replace('剧集：','',strip_tags($row['vod_continu']));
        mysql_query('UPDATE js_vod SET vod_continu="'.$vod_continu.'" WHERE vod_id="'.$row['vod_id'].'"');
        echo $row['vod_continu'],'-',$vod_continu."\r\n";
    }
}while(true);