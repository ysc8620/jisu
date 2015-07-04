<?php
class GbAction extends homeAction{
    //留言列表
    public function show(){
		$page = $_GET['page'];
        $vod_id = $_GET['vod_id'];
        $this->assign('page',$page);
		$this->assign('vod_id',$vod_id);
		$this->display('pp_guestbook');
    }
	// 添加留言
    public function insert(){
		$rs = D("Gb");
		C('TOKEN_ON',false);//关闭令牌验证
		if($rs->create()){
			if (false !== $rs->add()) {
				$cookie = 'gbook-'.intval($_POST['gb_cid']);
				setcookie($cookie, 'true', time()+intval(C('user_second')));
				if (C('user_check')) {
					$this->success('留言成功，我们会尽快审核您的留言！');
				}else{
					$this->success('恭喜您，留言成功！');
				}
			}else{
				$this->error('留言失败，请重试！');
			}
		}else{
		    $this->error($rs->getError());
		}
    }	
}
?>