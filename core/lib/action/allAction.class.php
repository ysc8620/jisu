<?php
/*项目入口根模块*/
class allAction extends Action{
    //构造函数
    public function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
    }
	// 影视多分类筛选变量定义
	public function Lable_Vod_Type($param){
		$array['sid'] = 1;
		$array['type_id'] = $param['id'];
		$array['type_year'] = $param['year'];
		$array['type_langaue'] = $param['langaue'];
		$array['type_area'] = $param['area'];
		$array['type_letter'] = $param['letter'];
		$array['type_actor'] = $param['actor'];
		$array['type_director'] = $param['director'];
		//
		$array['type_wd'] = $param['wd'];
		$array['type_page'] = $param['page'];
		$array['type_order'] = $param['order'];
		$array['type_skin'] = getlistname($param['id'],'list_skin_type');
		$array['type_pid'] = getlistname($param['id'],'list_pid');
		if (empty($array['type_skin'])) {
			$array['type_skin'] = 'home:pp_vodtype';
		}
		return $array;
    }
	// 影视栏目页变量定义
	public function Lable_Vod_List($param,$array_list){
		$array_list['sid'] = 1;
		$array_list['list_page'] = $param['page'];
		if ($param['page'] > 1) {
			$array_list['title'] = $array_list['list_name'].'-第'.$param['page'].'页'.'-'.C('site_name');
		}else{
			$array_list['title'] = $array_list['list_name'].'-'.C('site_name');
		}
		if (empty($array_list['list_skin'])) {
			$array_list['list_skin'] = 'home:pp_vodlist';
		}
		return $array_list;
    }
	/*****************影视内容,播放页公用变量定义******************************
	* @$array/具体的内容信息
	* @$array_play 为解析播放页
	* @返回赋值后的arrays 多维数组*/
	public function Lable_Vod_Read($array,$array_play=false){
		$array_list = list_search(F('_ppvod/list'),'list_id='.$array['vod_cid']);
		$array['sid'] = 1;
		$array['title'] = $array['vod_name'].'-'.C('site_name');		
		$array['vod_readurl'] = js_data_url('vod',$array['vod_id'],$array['vod_cid'],$array['vod_name'],1,$array['vod_jumpurl']);

		$array['vod_picurl'] = js_img_url($array['vod_pic'],$array['vod_content']);
		$array['vod_picurl_small'] = js_img_url_small($array['vod_pic'],$array['vod_content']);
		$array['vod_rssurl'] = UU('home-map/rss',array('id'=>$array['vod_id']),false,true);
		$array['vod_hits_insert'] = js_get_hits('vod','insert',$array);
		$array['vod_hits_month'] = js_get_hits('vod','vod_hits_month',$array);
		$array['vod_hits_week'] = js_get_hits('vod','vod_hits_week',$array);
		$array['vod_hits_day'] = js_get_hits('vod','vod_hits_day',$array);
		if($array['vod_skin']){
			$array['vod_skin_detail'] = 'home:'.trim($array['vod_skin']);
		}else{
			$array['vod_skin_detail'] = !empty($array_list['list_skin_detail']) ? 'home:'.$array_list['list_skin_detail'] : 'home:pp_vod';
		}
		//播放列表解析
		$array['vod_playlist'] = $this->js_playlist_all($array);
		$array['vod_playcount'] = count($array['vod_playlist']);
		//按顺序排列
		ksort($array['vod_playlist']);
		$arrays['show'] = $array_list[0];
		$arrays['read'] = $array;
		return $arrays;
	}

	//组合播放地址组列表为二维数组
	public function js_playlist_all($array){
		if(empty($array['vod_url'])){return false;}
		$playlist = array();
		$array_server = explode('$$$',$array['vod_server']);
		$array_player = explode('$$$',$array['vod_play']);
		$array_urllist = explode('$$$',$array['vod_url']);
		$player = C('play_player');
		$server = C('play_server');
		foreach($array_player as $sid=>$val){
			$playlist[$player[$val][0].'-'.$sid] = array('servername' => $array_server[$sid],'serverurl' => $server[$array_server[$sid]],'playername'=>$player[$val][1],'playname'=>$val,'son' => $this->js_playlist_one($array_urllist[$sid],$array['vod_id'],$sid,$array['vod_cid'],$array['vod_name']));
		}
		//ksort($playlist);
	    return $playlist;
	}


	//资讯栏目页变量定义
	public function Lable_News_List($param,$array_list){
		$array_list['sid'] = 2;
		$array_list['list_wd'] = $param['wd'];	
		$array_list['list_page'] = $param['page'];
		$array_list['list_letter'] = $param['letter'];
		$array_list['list_order'] = $param['order'];
		if ($param['page'] > 1) {
			$array_list['title'] = $array_list['list_name'].'-第'.$param['page'].'页';
		}else{
			$array_list['title'] = $array_list['list_name'];
		}
		$array_list['title'] = $array_list['title'].'-'.C('site_name');
		if (empty($array_list['list_skin'])) {
			$array_list['list_skin'] = 'pp_newslist';
		}
		return $array_list;
    }	
	//资讯内容页变量定义
	public function Lable_News_Read($array,$array_play = false){
		$array_list = list_search(F('_ppvod/list'),'list_id='.$array['news_cid']);
		$array['sid'] = 2;
		$array['title'] = $array['news_name'].'-'.C('site_name');
		$array['news_readurl'] = js_data_url('news',$array['news_id'],$array['news_cid'],$array['news_name'],1,$array['news_jumpurl']);
		$array['news_picurl'] = js_img_url($array['news_pic'],$array['news_content']);
		$array['news_picurl_small'] = js_img_url_small($array['news_pic'],$array['news_content']);
		$array['news_hits_insert'] = js_get_hits('news','insert',$array);
		$array['news_hits_month'] = js_get_hits('news','news_hits_month',$array);
		$array['news_hits_week'] = js_get_hits('news','news_hits_week',$array);
		$array['news_hits_day'] = js_get_hits('news','news_hits_day',$array);
		if($array['news_skin']){
			$array['news_skin_detail'] = 'home:'.trim($array['news_skin']);
		}else{
			$array['news_skin_detail'] = !empty($array_list['list_skin_detail']) ? 'home:'.$array_list['list_skin_detail'] : 'home:pp_news';
		}		
		$arrays['show'] = $array_list[0];
		$arrays['read'] = $array;		
		return $arrays;
	}
	//专题列表页变量定义
	public function Lable_Special_List($param){
		$array_list = array();
		$array_list['sid'] = 3;
		$array_list['special_skin'] = 'pp_speciallist';
		$array_list['special_page'] = $param['page'];
		$array_list['special_order'] = 'special_'.$param['order'];
		if ($param['page'] > 1) {
			$array_list['title'] = '专题列表-第'.$param['page'].'页'.'-'.C('site_name');
		}else{
			$array_list['title'] = '专题列表'.'-'.C('site_name');
		}
		return $array_list;
    }
	//专题内容页变量定义
	public function Lable_Special_Read($array,$array_play = false){
		$array_ids = array();$where = array();
		$array['special_readurl'] = js_data_url('special',$array['special_id'],0,$array['special_name'],1,0);
		$array['special_logo'] = js_img_url($array['special_logo'],$array['special_content']);
		$array['special_banner'] = js_img_url($array['special_banner'],$array['special_content']);
		$array['special_hits_insert'] = js_get_hits('special','insert',$array);
		$array['special_hits_month'] = js_get_hits('special','special_hits_month',$array);
		$array['special_hits_week'] = js_get_hits('special','special_hits_week',$array);
		$array['special_hits_day'] = js_get_hits('special','special_hits_day',$array);
		$array['special_skin'] = !empty($array['special_skin']) ? 'home:'.$array['special_skin'] : 'home:pp_special';
		$array['title'] = $array['special_name'].'-专题-'.C('site_name');
		$array['sid'] = 3;
		//收录影视
		$rs = D('TopicvodView');
		$where['topic_sid'] = 1;
		$where['topic_tid'] = $array['special_id'];
		$list_vod = $rs->where($where)->order('topic_oid desc,topic_did desc')->select();
		foreach($list_vod as $key=>$val){
			$list_vod[$key]['list_id'] = $list_vod[$key]['vod_cid'];
			$list_vod[$key]['list_name'] = getlistname($list_vod[$key]['list_id'],'list_name');
			$list_vod[$key]['list_url'] = getlistname($list_vod[$key]['list_id'],'list_url');
			$list_vod[$key]['vod_readurl'] = js_data_url('vod',$list_vod[$key]['vod_id'],$list_vod[$key]['vod_cid'],$list_vod[$key]['vod_name'],1,$list_vod[$key]['vod_jumpurl']);
			$list_vod[$key]['vod_playurl'] = js_play_url($list_vod[$key]['vod_id'],0,1,$list_vod[$key]['vod_cid'],$list_vod[$key]['vod_name']);
			$list_vod[$key]['vod_picurl'] = js_img_url($list_vod[$key]['vod_pic'],$list_vod[$key]['vod_content']);
			$list_vod[$key]['vod_picurl_small'] = js_img_url_small($list_vod[$key]['vod_pic'],$list_vod[$key]['vod_content']);
		}
		//收录资讯
		$rs = D('TopicnewsView');
		$where['topic_sid'] = 2;
		$where['topic_tid'] = $array['special_id'];
		$list_news = $rs->where($where)->order('topic_oid desc,topic_did desc')->select();
		foreach($list_news as $key=>$val){
			$list_news[$key]['list_id'] = $list_news[$key]['news_cid'];
			$list_news[$key]['list_name'] = getlistname($list_news[$key]['list_id'],'list_name');
			$list_news[$key]['list_url'] = getlistname($list_news[$key]['list_id'],'list_url');
			$list_news[$key]['news_readurl'] = js_data_url('news',$list_news[$key]['news_id'],$list_news[$key]['news_cid'],$list_news[$key]['news_name'],1,$list_news[$key]['news_jumpurl']);
			$list_news[$key]['news_picurl'] = js_img_url($list_news[$key]['news_pic'],$list_news[$key]['news_content']);
			$list_news[$key]['news_picurl_small'] = js_img_url_small($list_news[$key]['news_pic'],$list_news[$key]['news_content']);
		}
		$arrays['read'] = $array;
		$arrays['list_vod'] = $list_vod;
		$arrays['list_news'] = $list_news;
		return $arrays;
	}			
	//搜索页变量定义
	public function Lable_Search($param,$sidname = 'vod'){
		$array_search = array();
		if($sidname == 'vod'){
			$array_search['search_actor'] = $param['actor'];
			$array_search['search_director'] = $param['director'];			
			$array_search['search_area'] = $param['area'];
			$array_search['search_langaue'] = $param['langaue'];
			$array_search['search_year'] = $param['year'];
			$array_search['sid'] = 1;
		}else{
			$array_search['sid'] = 2;
		}
		$array_search['search_wd'] = $param['wd'];
		$array_search['search_name'] = $param['name'];
		$array_search['search_title'] = $param['title'];
		$array_search['search_page'] = $param['page'];
		$array_search['search_letter'] = $param['letter'];
		$array_search['search_order'] = $param['order'];
		$array_search['search_skin'] = 'pp_'.$sidname.'search';
		if ($param['page'] > 1) {
			$array_search['title'] = $array_search['search_wd'].'-第'.$param['page'].'页';
		}else{
			$array_search['title'] = $array_search['search_wd'];
		}
		$array_search['title'] = $array_search['search_area'].$array_search['search_langaue'].$array_search['search_actor'].$array_search['search_director'].$array_search['title'].'-'.C('site_name');
		return $array_search;
    }	
	//Tag页变量定义
	public function Lable_Tags($param){
		$array_tag = array();
		$array_tag['tag_name'] = $param['wd'];
		$array_tag['tag_url'] = js_tag_url($param['wd']);
		$array_tag['tag_page'] = $param['page'];
		if ($param['page'] > 1) {
			$array_tag['title'] = $array_tag['tag_name'].'-第'.$param['page'].'页-'.C('site_name');
		}else{
			$array_tag['title'] = $array_tag['tag_name'].'-'.C('site_name');
		}
		return $array_tag;
    }	
	//首页标签定义
	public function Lable_Index(){
		$array = array();
		$array['title'] = C('site_name');
		$array['model'] = 'index';
		return $array;
	}
	//地图页标签定义
	public function Lable_Maps($mapname,$limit,$page){
		$rs = M("Vod");
		$list = $rs->order('vod_addtime desc')->limit($limit)->page($page)->select();
		if($list){
			foreach($list as $key=>$val){
				$list[$key]['vod_readurl'] = js_data_url('vod',$list[$key]['vod_id'],$list[$key]['vod_cid'],$list[$key]['vod_name'],1,$list[$key]['vod_jumpurl']);
				$list[$key]['vod_playurl'] = js_play_url($list[$key]['vod_id'],0,1,$list[$key]['vod_cid'],$list[$key]['vod_name']);
			}
			return $list;			
		}
		return false;
    }
	//全局标签定义
	public function Lable_Style(){
		$array = array();
		$array['model'] = strtolower(MODULE_NAME);
		$array['action'] = strtolower(ACTION_NAME);	
		C('TOKEN_ON',false);//C('TOKEN_NAME','form_'.$array['model']);取消前端的表单令牌
		$array['root'] = __ROOT__.'/';
		$array['tpl'] = $array['root'].str_replace('./','',TEMPLATE_PATH).'/';
		$array['css'] = '<link rel="stylesheet" type="text/css" href="'.$array['tpl'].'style.css">'."\n";
		$array['sitename'] = C('site_name');
		$array['siteurl'] = C('site_url');
		$array['sitehot'] = js_hot_key(C('site_hot'));
		$array['keywords'] = C('site_keywords');
		$array['description'] = C('site_description');
		$array['email'] = C('site_email');
		$array['copyright'] = C('site_copyright');
		$array['tongji'] = C('site_tongji');
		$array['icp'] = C('site_icp');
		$array['hotkey']   = js_hot_key(C('site_hot'));
		$array['url_tag'] = UU('home-tag/show','',false,true);
		$array['url_guestbook'] = UU('home-gb/show','',false,true);
		$array['url_special'] = js_special_url(1);
		$array['url_map_rss'] = js_map_url('rss');
		$array['url_map_baidu'] = js_map_url('baidu');
		$array['url_map_google'] = js_map_url('google');
		$array['url_map_soso'] = js_map_url('soso');
		$array['list_slide'] = F('_ppvod/slide');
		$array['list_link'] = F('_ppvod/link');
		$array['list_menu'] = F('_ppvod/listtree');		
		return $array;		
	}					
}
?>