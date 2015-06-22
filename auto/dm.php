<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-6-18
 * Email: ysc8620@163.com
 * QQ: 372613912
 */

if(!file_exists(  '../runtime/conf/config.php')){
    die('conf config no exists');
}
$config = include_once('../runtime/conf/config.php');

require_once 'db.php';


$i = 0;
$j=0;
$size = 1000;
do{
    $list_data = DB::init()->getList("SELECT * FROM js_vod WHERE vod_cid=3 ORDER BY vod_id DESC LIMIT $i, $size");

    if(count($list_data) < 1){break;}

    foreach($list_data as $row){
       $vod_url = ($row['vod_url']);
        $vod_url = explode('$$$', $vod_url);
        $url = $old_url = $new_url = '';
        foreach($vod_url as $u){

            preg_match_all("/.*?http:\/\/(.*?)\/.*?/is", $u,$re);
            $new_url = @$re[1][0];
            if ($new_url){
                if($new_url != $old_url){
                    $url =trim($url). '$$$';

                }
            }
            $url .= trim($u)."\n";
            $old_url = $new_url;

        }

           DB::init()->update(array('vod_url'=>$url), $row['vod_id']);

        print $i.'='.$row['vod_id'].'=' ."\r\n";
    }

    $i = $i + $size;

}while(true);