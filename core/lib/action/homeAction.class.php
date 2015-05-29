<?php
//前台公用类库
class homeAction extends allAction{
	//构造函数
    public function _initialize(){
		parent::_initialize();
        $this->assign($this->Lable_Style());
    }
}
?>