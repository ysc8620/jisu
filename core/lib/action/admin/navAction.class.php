<?php
class navAction extends baseAction{
    public function show(){
		$array = F('_nav/list');
		$this->assign('array_nav',$array);
        $this->display('./public/system/nav.html');
    }
	
    public function update(){
		$content = trim($_POST["content"]);
		if(empty($content)){
			$this->error('自定义菜单不能为空！');
		}
		foreach(explode(chr(13),$content) as $value){
			list($key,$val) = explode('|',trim($value));
			if(!empty($val)){
				$arrlist[trim($key)] = trim($val);
			}
		}
		F('_nav/list',$arrlist);
		$this->assign("jumpUrl",C('cms_admin').'?s=admin-nav-show-reload-1');
		$this->success('自定义菜单编辑成功！');
	}
}
?>