 <?php
class tagAction extends baseAction{
	// 显示标签列表
    public function show(){
		//生成查询参数
		$admin['p'] = '';
		//组合分页信息
		$limit = C('url_num_admin');
		$rs = new Model() ;
		$count = $rs->query("select count(1) as count from (select  *  from ".C('db_prefix')."tag  group by tag_sid,tag_name ) aa");
		$count = $count[0]['count'];
		$totalpages = ceil($count/$limit);
		$currentpage = !empty($_GET['p'])?intval($_GET['p']):1;
		$currentpage = get_maxpage($currentpage,$totalpages);//$admin['page'] = $currentpage;
		$pageurl = U('admin-tag/show',$admin,false,false).'{!page!}'.C('url_html_suffix');
		$admin['p'] = $currentpage;
		$pages = '共'.$count.'个标签&nbsp;当前:'.$currentpage.'/'.$totalpages.'页&nbsp;'.getpage($currentpage,$totalpages,8,$pageurl,'pagego(\''.$pageurl.'\','.$totalpages.')');
		$admin['pages'] = $pages;
		//查询数据
		$rs = D("Tag");
		$array = $rs->field('*,count(tag_name) as tag_count')->limit($limit)->page($currentpage)->group('tag_sid,tag_name')->order('tag_sid asc,tag_count desc')->select();
		foreach($array as $key=>$val){
			$array[$key]['tag_url'] = U('admin-'.(getsidname($array[$key]['tag_sid'])).'/show',array('tag'=>urlencode($array[$key]['tag_name'])),'',false,true);
		}		
		$this->assign($admin);
		$this->assign('list_tag',$array);
		$this->display('./public/system/tag_show.html');
    }	
	// 显示标签AJAX方式
    public function showajax(){
		$rs = D("Tag");
		$where['tag_sid'] = array('eq',intval($_GET['sid']));
		$array = $rs->field('*,count(tag_name) as tag_count')->where($where)->limit('60')->group('tag_name,tag_sid')->order('tag_count desc')->select();
		/*foreach($array as $key=>$val){
			if($array[$key]['tag_sid']==2){
				$array[$key]['tag_url']=UU('admin-news/show',array('tag_name'=>urlencode($array[$key]['tag_name']),'tag_type'=>2),'',false,true);
			}else{
				$array[$key]['tag_url']=UU('admin-vod/show',array('tag_name'=>urlencode($array[$key]['tag_name']),'tag_type'=>1),'',false,true);
			}
		}*/	
		$this->assign('tag_list',$array);
		$this->display('./public/system/tag_ajax.html','','text/html',true);
    }
	// 删除标签
    public function del(){
		$rs = D("Tag");
		$where['tag_name'] = trim($_GET['id']);
		$rs->where($where)->delete();
		$this->success('标签:'.$tag.'删除成功！');
    }									
}
?>