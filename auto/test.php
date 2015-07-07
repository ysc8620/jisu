<?php
echo date("Y-m-d H:i:s");

function logs($file, $data){

    $fp = fopen($file, "a+");

    if (flock($fp, LOCK_EX)) { // 进行排它型锁定
        fwrite($fp, $data."\r\n");
        flock($fp, LOCK_UN); // 释放锁定
    } else {
        die( "Couldn't lock the file !");
    }

    fclose($fp);
}
logs(dirname(__FILE__).'/test.log', date("Y-m-d H:i:s"));
