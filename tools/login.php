<?php
/**
 * JISUCMS
 * Author: ShengYue <jisucms@gmail.com>
 * Date: 13-12-18 下午4:40
 * Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 */
session_start();
if(isset($_POST['submit']))
{
    if($_POST['login_username'] ==='tools' && $_POST['login_password']=='jisu123')
    {
        $_SESSION['tools'] = 1;
        return header('Location: /tools');
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>小工具</title>
</head>

<body>
<form action="?" method="POST">
<table width="600" border="0" cellspacing="1" cellpadding="6" bgcolor="#CCCCCC">
    <tr>
        <td width="93" height="36" bgcolor="#FFFFFF">登录名</td>
        <td width="504" bgcolor="#FFFFFF"><input name="login_username"></td>
    </tr>
    <tr>
        <td height="36" bgcolor="#FFFFFF">密码</td>
        <td bgcolor="#FFFFFF"><input name="login_password" type="password"></td>
    </tr>

    <tr>
        <td height="36" bgcolor="#FFFFFF"><input type="submit" name="submit" value="登录"></td>
        <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
</table>
</form>
</body>
</html>

