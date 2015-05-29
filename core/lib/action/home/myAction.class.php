<?php
class myAction extends homeAction{
    public function show(){
		$id = !empty($_GET['id'])?$_GET['id']:'new';
		$this->display('my_'.trim($id));
	}					
}
?>