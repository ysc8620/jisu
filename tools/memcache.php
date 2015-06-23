<?php
include ("config.php");
?>

<br />
<br />
<?php
$connected = new Memcache();
$connected->addServer('127.0.0.1', 11211);
//$connected->addServer('127.0.0.1', 22122);
echo $connected->flush();
$connected->close();
?>
<br /><br />
</body>
</html>