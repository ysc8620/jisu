<?php
//顶踩模块
class updownAction extends homeAction{
    public function vod(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			$this->ajaxReturn(-1,'数据非法！',-1);
		}
		$this->show($id,trim($_GET['type']),'vod');
    }
    public function news(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			$this->ajaxReturn(-1,'数据非法！',-1);
		}
		$this->show($id,trim($_GET['type']),'news');
    }	
	public function show($id,$type,$model='vod'){
		$rs = D(ucfirst($model));
		if($type){
			$cookie = $model.'-updown-'.$id;
			if(isset($_COOKIE[$cookie])){
				$this->ajaxReturn(0,'您已操作过，晚点再试！',0);
			}
			if ('up' == $type){
				$rs->setInc($model.'_up',$model.'_id = '.$id);
				setcookie($cookie, 'true', time()+intval(C('user_second')));
			}elseif( 'down' == $type){
				$rs->setInc($model.'_down',$model.'_id = '.$id);
				setcookie($cookie, 'true', time()+intval(C('user_second')));
			}elseif( 'general' == $type){
                $rs->setInc($model.'_general',$model.'_id = '.$id);
                setcookie($cookie, 'true', time()+intval(C('user_second')));
            }
		}
		$array = $rs->field(''.$model.'_up,'.$model.'_general,'.$model.'_down')->find($id);
		if (!$array) {
			$array[$model.'_up'] = 0;
            $array[$model.'_general'] = 0;

			$array[$model.'_down'] = 0;
		}
		$this->ajaxReturn($array[$model.'_up'].':'.$array[$model.'_general'].':'.$array[$model.'_down'],"感谢您的参与，操作成功！",1);
		//echo($array[$model.'_up'].':'.$array[$model.'_down']);			
	}	
}
?>