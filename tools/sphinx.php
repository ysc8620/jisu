<?php
include ("config.php");
?>

<br />
<br />
<?php
set_time_limit(0);
echo '<pre>';
echo system('/usr/local/coreseek/bin/indexer -c /data/sphinx/sphinx.conf --all --rotate');
echo '</pre>';
?>
<br /><br />
</body>
</html>