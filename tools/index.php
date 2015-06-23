<?php
include ("config.php");
?>

<table width="600" border="0" cellspacing="1" cellpadding="6" bgcolor="#CCCCCC">
    <tr>
        <td width="93" height="36" bgcolor="#FFFFFF"></td>
        <td width="504" bgcolor="#FFFFFF"><b>管理员快捷操作2014</b></td>
    </tr>

    <tr>
        <td width="93" height="36" bgcolor="#FFFFFF">开启Memcached缓存</td>
        <td width="504" bgcolor="#FFFFFF"><a href="startmemcache.php?t=<?php echo time();?>">开启Memcached缓存</a> 注意： 不要随便操作， </td>
    </tr>
    <tr>
        <td width="93" height="36" bgcolor="#FFFFFF">开启Sphinx搜索引擎</td>
        <td width="504" bgcolor="#FFFFFF"><a href="startsphinx.php?t=<?php echo time();?>">开启Sphinx搜索引擎</a> 注意： 不要随便操作</td>
    </tr>

    <tr>
        <td width="93" height="36" bgcolor="#FFFFFF">重启Web服务器</td>
        <td width="504" bgcolor="#FFFFFF"><a href="monitor.php?t=<?php echo time();?>">重启Web服务器</a> 注意： 不要随便操作</td>
    </tr>

  <tr>
    <td height="36" bgcolor="#FFFFFF">cache更新</td>
    <td bgcolor="#FFFFFF"><a href="cache.php?t=<?php echo time();?>">cache更新</a></td>
  </tr>
    <tr>
        <td height="36" bgcolor="#FFFFFF">Memcache 状态</td>
        <td bgcolor="#FFFFFF"><a href="mem.php">Memcache 状态</a></td>
    </tr>
  <tr>
    <td height="36" bgcolor="#FFFFFF">Memcache Flush</td>
    <td bgcolor="#FFFFFF"><a href="memcache.php">Memcache Flush</a></td>
  </tr>
    <tr>
        <td height="36" bgcolor="#FFFFFF">Sphinx 索引重建</td>
        <td bgcolor="#FFFFFF"><a href="sphinx.php?t=<?php echo time();?>">Sphinx Index</a> 注意：需要更新列表数据操作</td>
    </tr>
    <tr>
        <td height="36" bgcolor="#FFFFFF">PHPINFO</td>
        <td bgcolor="#FFFFFF"><a href="phpinfo.php">PHPINFO</a></td>
    </tr>
    <tr>
        <td height="36" bgcolor="#FFFFFF">系统信息</td>
        <td bgcolor="#FFFFFF"><a href="p.php">系统信息</a></td>
    </tr>
    <tr>
        <td height="36" bgcolor="#FFFFFF">MYSQL信息</td>
        <td bgcolor="#FFFFFF"><a href="mysql_stat.php">MYSQL信息</a></td>
    </tr>

  <tr>
    <td height="36" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>
</body>
</html>
