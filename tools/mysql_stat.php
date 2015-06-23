<?php
error_reporting(0);
$config_file = dirname(dirname(dirname(dirname(__FILE__)))).'/config/dbserver.config.php';
if(!file_exists($config_file))
{
    die('Config error.');
}else{
    $TS_DB = include_once($config_file);
    foreach($TS_DB as $ds){
        $TS_DB = $ds;
        break;
    }
}

$conn = mysql_pconnect($TS_DB['master']['hostname'], $TS_DB['master']['username'], $TS_DB['master']['password'])OR DIE('Mysql Connect error');

if($_GET['get'])
{


    $array = explode("  ", mysql_stat());
    foreach ($array as $value){
        $res .= $value . "<br />";
    }
    die($res);
}else{
    include ("config.php");
    echo "<div class='html'>";
    $array = explode("  ", mysql_stat());
    foreach ($array as $value){
        echo $value . "<br />";
    }
    echo "</div>";
}
?>
<script src="jquery-1.6.4.min.js?t=20131130" type="text/javascript"></script>
<script type="text/javascript">
    setInterval(function(){
    $('.html').load('?get=1');}, 1000);
</script>
</body>
        </html>
