<?php
class cacheAction extends baseAction{
	//缓存管理列表
    public function show(){    
		 $this->display('./public/system/cache_show.html');
    }
	//清除系统缓存AJAX
    public function del(){
		import("ORG.Io.Dir");
		$dir = new Dir;
		$this->ppvod_list();
		@unlink('./runtime/~app.php');
		@unlink('./runtime/~runtime.php');
		if(!$dir->isEmpty('./runtime/data/_fields')){$dir->del('./runtime/data/_fields');}
		if(!$dir->isEmpty('./runtime/temp')){$dir->delDir('./runtime/temp');}
		if(!$dir->isEmpty('./runtime/cache')){$dir->delDir('./tuntime/cache');}
		if(!$dir->isEmpty('./runtime/logs')){$dir->delDir('./tuntime/logs');}
		echo('清除成功');
    }
	// 删除静态缓存
	public function delhtml(){
		$id = $_GET['id'];
	    import("ORG.Io.Dir");
		$dir = new Dir;
		if('index' == $id){
			@unlink(HTML_PATH.'index'.C('html_file_suffix'));
		}elseif('vodlist'== $id){
			if(is_dir(HTML_PATH.'vod_show')){$dir->delDir(HTML_PATH.'vod_show');}
		}elseif('vodread' == $id){
			if(is_dir(HTML_PATH.'vod_read')){$dir->delDir(HTML_PATH.'vod_read');}
		}elseif('vodplay' == $id){
			if(is_dir(HTML_PATH.'vod_play')){$dir->delDir(HTML_PATH.'vod_play');}
		}elseif('newslist' == $id){
			if(is_dir(HTML_PATH.'news_show')){$dir->delDir(HTML_PATH.'news_show');}
		}elseif('newsread' == $id){
			if(is_dir(HTML_PATH.'news_read')){$dir->delDir(HTML_PATH.'news_read');}
		}elseif('ajax' == $id){
		    if(is_dir(HTML_PATH.'ajax_show')){$dir->delDir(HTML_PATH.'ajax_show');}
		}elseif('day' == $id){
		    $this->delhtml_day();    
		}else{
		    @unlink(HTML_PATH.'index'.C('html_file_suffix'));
			if(is_dir(HTML_PATH.'vod_show')){$dir->delDir(HTML_PATH.'vod_show');}
			if(is_dir(HTML_PATH.'vod_read')){$dir->delDir(HTML_PATH.'vod_read');}
			if(is_dir(HTML_PATH.'vod_play')){$dir->delDir(HTML_PATH.'vod_play');}
			if(is_dir(HTML_PATH.'news_show')){$dir->delDir(HTML_PATH.'news_show');}
			if(is_dir(HTML_PATH.'news_read')){$dir->delDir(HTML_PATH.'news_read');}
		    if(is_dir(HTML_PATH.'ajax_show')){$dir->delDir(HTML_PATH.'ajax_show');}
		}
		echo('清除成功');
	}
	//清理当天静态缓存文件
	public function delhtml_day(){
		$where = array();
		$where['vod_addtime']= array('gt',getxtime(1));
		$rs = D("Vod");
		$array = $rs->field('vod_id')->where($where)->order('vod_id desc')->select();
		if($array){
			foreach($array as $key=>$val){
			    $id = md5($array[$key]['vod_id']).C('html_file_suffix');
			    @unlink('./html/vod_read/'.$id);
				@unlink('./html/vod_play/'.$id);
			}
		    import("ORG.Io.Dir");
			$dir = new Dir;
			if(!$dir->isEmpty('./html/vod_show')){$dir->delDir('./html/vod_show');}
			if(!$dir->isEmpty('./html/ajax_show')){$dir->delDir('./html/ajax_show');}
			@unlink('./html/index'.C('html_file_suffix'));
		}
		echo('清除成功');
	}
	//清空所有数据缓存
    public function dataclear(){
		if(C('data_cache_type') == 'memcache'){
			$cache = Cache::getInstance();	
			$cache->clear();
		}else{
			import("ORG.Io.Dir");
			$dir = new Dir;
			if(!$dir->isEmpty(TEMP_PATH)){
				$dir->delDir(TEMP_PATH);
			}
		}
		echo('清除成功');
    }
	//循环标签调用
    public function dataforeach(){
		$config_old = require './runtime/conf/config.php';
		$config_new = array_merge($config_old, array('data_cache_foreach'=>uniqid()) );
		arr2file('./runtime/conf/config.php',$config_new);
		@unlink('./runtime/~app.php');
		echo('清除成功');
    }
	//当天视频
	public function datadayvod(){
		$where = array();
		$where['vod_addtime']= array('gt',getxtime(1));
		$rs = M("Vod");
		$array = $rs->field('vod_id')->where($where)->order('vod_id desc')->select();
		foreach($array as $key=>$val){
			S('data_cache_vod_'.$val['vod_id'],NULL);
		}						
		echo('清除成功');
	}	
	//当天新闻
	public function datadaynews(){
		$where = array();
		$where['news_addtime']= array('gt',getxtime(1));
		$rs = M("News");
		$array = $rs->field('news_id')->where($where)->order('news_id desc')->select();
		foreach($array as $key=>$val){
			S('data_cache_news_'.$val['news_id'],NULL);
		}						
		echo('清除成功');
	}
	//当天专题
	public function datadayspecial(){
		$where = array();
		$where['special_addtime']= array('gt',getxtime(1));
		$rs = M("Special");
		$array = $rs->field('special_id')->where($where)->order('special_id desc')->select();
		foreach($array as $key=>$val){
			S('data_cache_special_'.$val['special_id'],NULL);
		}						
		echo('清除成功');
	}			
}
?>