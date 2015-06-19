<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-6-18
 * Email: ysc8620@163.com
 * QQ: 372613912
 */

class DB{
    private  $conn = null;
    static private $ob = null;
    private  function __construct(){
        global $config;
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
            $res = mysql_query($sql, $this->conn);
        }catch (Exception $e){
            //
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

    public function insert($data){
        $sql = $this->parserField($data);
        return $this->query("INSERT INTO js_vod SET ".$sql);

    }

    public function update($data, $id){
        $sql = $this->parserField($data);
        $this->query("UPDATE INTO js_vod SET {$sql} WHERE vod_id={$id}");
    }

    public function __destruct(){
        try{
            mysql_close($this->conn);
        }catch (Exception $e){
            //
        }
    }
}