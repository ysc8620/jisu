<?php
// 本后管理框架
class indexAction extends baseAction{
    public function index(){
        $this->display('./public/system/right.html');
    }
	
    public function phpinfo(){
        phpinfo();
    }	
}
?>