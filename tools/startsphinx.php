<?php
include ("config.php");
?>
<br />

<pre>
 关闭sphinx进程<br />
<?php

echo system('killall -9 searchd');
?>
    开启sphinx进程<br />
    <?php
    echo system('/usr/local/coreseek/bin/searchd -c /data/sphinx/sphinx.conf');
    ?>
    <br />
    操作完成
</pre>
<br /><br />
</body>
</html>