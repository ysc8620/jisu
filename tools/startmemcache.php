<?php
include ("config.php");
?>
<br />

<pre>
 关闭memcached进程<br />
<?php

echo system('killall -9 memcached');
?>
    开启memcached进程<br />
    <?php
    echo system('/usr/local/memcached/bin/memcached -d -m 300 -l 127.0.0.1 -p 11211 -u www');
    ?>
    <br />
    操作完成
</pre>
<br /><br />
</body>
</html>