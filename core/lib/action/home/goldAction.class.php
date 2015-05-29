<?php
class GoldAction extends homeAction{
  	public function vod(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			$this->ajaxReturn(-1,'数据非法！',-1);
		}
		$this->show($id,intval($_GET['type']),'vod');
    }
    public function news(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			$this->ajaxReturn(-1,'数据非法！',-1);
		}
		$this->show($id,intval($_GET['type']),'news');
    }	
	public function show($id,$type,$model='vod'){
		$rs = D(ucfirst($model));
		$array = $rs->field(''.$model.'_gold,'.$model.'_golder')->find($id);
		if ($array) {
			if($type){
				$cookie = $model.'-gold-'.$id;
				if(isset($_COOKIE[$cookie])){
					$this->ajaxReturn(0,'您已评分！',0);
				}			
				$array[$model.'_gold'] = number_format(($array[$model.'_gold']*$array[$model.'_golder']+$type)/($array[$model.'_golder']+1),1);
				$array[$model.'_golder'] = $array[$model.'_golder']+1;
				$rs->where($model.'_id = '.$id)->save($array);
				setcookie($cookie,'t',time()+intval(C('user_second')));
			}else{
				$array = $array;
			}			
		}else{
			$array[$model.'_gold'] = 0.0;
			$array[$model.'_golder'] = 0;
		}
		$this->ajaxReturn($array[$model.'_gold'].':'.$array[$model.'_golder'],"感谢您的参与，评分成功！",1);		
	}
}
?>