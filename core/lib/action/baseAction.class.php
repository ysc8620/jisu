<?php
/*****************后台公用类库 继承全站公用类库*******************************/
class baseAction extends allAction{
	//构造
    public function _initialize(){
	    parent::_initialize();
		//检查登录
		if (!$_SESSION[C('USER_AUTH_KEY')]) {
			$this->assign('jumpUrl',C('cms_admin').'?s=admin-login');
			$this->error('对不起，您还没有登录，请先登录！');
		}
		//检查权限 不需要验证操作的除外
		if (!in_array(strtolower(ACTION_NAME),explode(',',C('NOT_AUTH_ACTION')))) {
			// 检索当前模块是否需要认证
			$model_id = array_search(MODULE_NAME,explode(',',C('REQUIRE_AUTH_MODULE')));
			if (is_int($model_id)) {
				$usertype = explode(',',$_SESSION['admin_ok']);
				if (!$usertype[$model_id]) {
					$this->error('对不起，您没有管理该模块的权限，请联系超级管理员授权！');
				}
			}
		}
    }

	//生成播放器列表
    public function ppvod_play(){
	    $this->assign('countplayer',count(C('PP_PLAYER')));
		$this->assign('playtree',C('play_player'));
    }	
	//生成前台分类缓存
    public function ppvod_list(){
		$rs = D("List");
		$where['list_status'] = array('eq',1);
		$list=$rs->where($where)->order('list_oid asc')->select();
		foreach($list as $key=>$val){
			if ($list[$key]['list_sid'] == 9){
				$list[$key]['list_url'] = $list[$key]['list_jumpurl'];
				$list[$key]['list_url_big'] = $list[$key]['list_jumpurl'];
			}else{
				$list[$key]['list_url'] = js_list_url(getsidname($list[$key]['list_sid']),array('id'=>$list[$key]['list_id']),1);
				$list[$key]['list_url_big'] = js_list_url(getsidname($list[$key]['list_sid']),array('id'=>$list[$key]['list_pid']),1);
				$list[$key]['list_name_big'] = getlistname($list[$key]['list_pid']);
				if($list[$key]['list_sid'] == 1){
					$list[$key]['list_limit'] = gettplnum('js_mysql_vod\(\'(.*)\'\)',$list[$key]['list_skin']);
				}else{
					$list[$key]['list_limit'] = gettplnum('js_mysql_news\(\'(.*)\'\)',$list[$key]['list_skin']);
				}
			}
		}
		//$list[999]['special'] = get_tpl_limit('<bdlist(.*)limit="([0-9]+)"(.*)>','special_list');//缓存专题分页数据	
		F('_ppvod/list',$list);
		F('_ppvod/listtree',list_to_tree($list,'list_id','list_pid','son',0));
		
		$where['list_sid'] = array('EQ',1);
		$list = $rs->where($where)->order('list_oid asc')->select();
		F('_ppvod/listvod',list_to_tree($list,'list_id','list_pid','son',0));
		
		$where['list_sid']=array('EQ',2);
		$list=$rs->where($where)->order('list_oid asc')->select();
		F('_ppvod/listnews',list_to_tree($list,'list_id','list_pid','son',0));
    }

    protected function display($templateFile='',$charset='',$contentType='text/html',$show_this=false)
    {
        if(false === $templateFile) {
            $this->showTrace();
        }else{
            if($show_this){
                $this->assign('sys_model_name', strtolower(MODULE_NAME));
                $this->assign('sys_action_name', strtolower(ACTION_NAME));
                $this->view->display($templateFile,$charset,$contentType);
            }else{
                $this->assign('sys_model_name', strtolower(MODULE_NAME));
                $this->assign('sys_action_name', strtolower(ACTION_NAME));
                $this->assign('main_file',$templateFile);
                $this->view->display('./public/system/frame.html',$charset,$contentType);
            }
        }
    }

     function _dispatch_jump($message,$status=1,$ajax=false)
    {
        // 判断是否为AJAX返回
        if($ajax || $this->isAjax()) $this->ajaxReturn($ajax,$message,$status);
        // 提示标题
        $this->assign('msgTitle',$status? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
        //如果设置了关闭窗口，则提示完毕后自动关闭窗口
        if($this->get('closeWin'))    $this->assign('jumpUrl','javascript:window.close();');
        $this->assign('status',$status);   // 状态
        $this->assign('message',$message);// 提示信息
        //保证输出不受静态缓存影响
        C('HTML_CACHE_ON',false);
        if($status) { //发送成功信息
            // 成功操作后默认停留1秒
            if(!$this->get('waitSecond'))    $this->assign('waitSecond',"1");
            // 默认操作成功自动返回操作前页面
            if(!$this->get('jumpUrl')) $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);
            $this->display(C('TMPL_ACTION_SUCCESS'),'','text/html',true);
        }else{
            //发生错误时候默认停留3秒
            if(!$this->get('waitSecond'))    $this->assign('waitSecond',"3");
            // 默认发生错误的话自动返回上页
            if(!$this->get('jumpUrl')) $this->assign('jumpUrl',"javascript:history.back(-1);");
            $this->display(C('TMPL_ACTION_ERROR'),'','text/html',true);
        }
        if(C('LOG_RECORD')) Log::save();
        // 中止执行  避免出错后继续执行
        exit ;
    }
}
?>