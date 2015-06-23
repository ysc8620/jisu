<?php
/**
 * JISUCMS
 * Author: ShengYue <jisucms@gmail.com>
 * Date: 13-12-18 下午4:39
 * Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 */
session_start();

if(empty($_SESSION['tools']))
{
    return header('Location: /tools/login.php');
}
if(empty($_GET['IMG'])):
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>小工具</title>
</head>

<body>
<div style="border: #DDDDDD 1px solid; line-height: 36px; width: 100%; background: #e9f1f7; clear: both; margin-bottom: 12px;">
<a href="/tools" style="font-size: 16px; color: #000080; padding-left: 12px;">返回</a>
</div>
<?php
endif;
?>