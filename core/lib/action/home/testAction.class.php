<?php
/**
 * Created with JetBrains PhpStorm.
 * User: ShengYue
 * Date: 15-6-7
 * Email: ysc8620@163.com
 * QQ: 372613912
 */
class testAction extends homeAction{
    function index(){
        $page = $_GET['page'];
        $page = $page?$page:'index';
        $this->display($page);
    }
}