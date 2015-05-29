<?php
class tagAction extends homeAction{
    public function vod(){
		$this->tagall('vod');
		$this->display('pp_vodtag');
    }
    public function news(){
		$this->tagall('news');
		$this->display('pp_newstag');
    }
	public function tagall($sidname = 'vod'){
		//通过地址栏参数支持筛选条件,$JumpUrl传递分页及跳转参数
		$Url = js_param_url();
		$JumpUrl = js_param_jump($Url);
		$JumpUrl['p'] = '{!page!}';
		C('jumpurl',UU('home-tag/'.$sidname,$JumpUrl,false,true));
		C('currentpage',$Url['page']);
		//变量赋值
		$tag_list = $this->Lable_Tags($Url);
		$this->assign($tag_list);	
	}	
}
?>