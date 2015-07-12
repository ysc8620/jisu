<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-6-18
 * Email: ysc8620@163.com
 * QQ: 372613912
 */

function logs($file, $data){

    $fp = fopen($file, "a+");

    if (flock($fp, LOCK_EX)) { // 进行排它型锁定
        fwrite($fp, date("Y-m-d H:i:s") . "  " . $data."\r\n");
        flock($fp, LOCK_UN); // 释放锁定
    } else {
        die( "Couldn't lock the file !");
    }

    fclose($fp);
}
class DB{
    private  $conn = null;
    static private $ob = null;
    private  function __construct(){
        global $config;
        echo $config['db_name'].'=====';
        $this->conn = mysql_connect($config['db_host'], $config['db_user'], $config['db_pwd']) OR DIE('ERROR CONNECT');
        mysql_select_db($config['db_name'], $this->conn) OR DIE('ERROR DB');
        mysql_query('SET NAMES '.$config['db_charset']);
    }

    static public function init(){
        if(DB::$ob == null){

            DB::$ob = new DB();
        }
        return DB::$ob;
    }

    public function query($sql){
        try{
          //  echo $sql ."\r\n";
            $res = mysql_query($sql, $this->conn);
        }catch (Exception $e){
            //
            print_r($e);
            die('mysql run error');
        }
        return $res;
    }

    public function getOne($sql){
        try{
        return mysql_fetch_assoc($this->query($sql));
        }catch (Exception $e){

        }
    }

    public function getList($sql){
        try{
            $res = $this->query($sql);
            $data = array();
            while($row = mysql_fetch_assoc($res)){
                $data[] = $row;
            }
            return $data;
        }catch (Exception $e){

        }
    }

    /**
     * @param $data
     * @return string
     */
    private function parserField($data){
        $sql = "";
        foreach($data as $k=>$v){
            $v = str_replace("'",'',$v);
            $sql .= "`$k`='$v',";
        }

        return trim($sql,',');
    }

    public function insert($data,$bool=false){
        $sql = $this->parserField($data);
        if($bool){
           // echo ;
            logs(dirname(__FILE__).'/db.sql', "INSERT INTO js_vod SET ".$sql."\r\n");
        }
//echo "INSERT INTO js_vod SET ".$sql."<br/><br/>";
         return $this->query("INSERT INTO js_vod SET ".$sql);


    }

    public function update($data, $id,$bool= false){
        $sql = $this->parserField($data);
        if($bool){
            // echo ;
            logs(dirname(__FILE__).'/db.sql', "UPDATE js_vod SET {$sql} WHERE vod_id={$id}"."\r\n");
        }
       // echo "UPDATE js_vod SET {$sql} WHERE vod_id={$id}"."<br/><br/>";;
        return $this->query("UPDATE js_vod SET {$sql} WHERE vod_id={$id}");
    }

    public function __destruct(){
        try{
            mysql_close($this->conn);
        }catch (Exception $e){
            //
        }
    }
}