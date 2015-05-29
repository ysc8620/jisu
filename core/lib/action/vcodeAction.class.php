<?php
//生成验证码
class vcodeAction extends allAction{
    public function index(){
	    import("ORG.Util.Image");
		Image::buildImageVerify();//6,0,'png',1,20,'verify'
    }
}
?>