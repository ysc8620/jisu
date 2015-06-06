 <?php
class areaAction extends baseAction{
	// 显示分类
    public function show(){

	    $rs = D("Area");
		$list = $rs->order('sort asc')->select();
		if($list){
			$this->assign('listarea',$list);
            $this->assign('list_tree',F('_ppvod/listvod'));

			$this->display('./public/system/area_show.html');
		}else{
		    $this->assign("jumpUrl",'?s=admin-area-add');
			$this->success('暂无地区数据请先添加！');
		}
    }
	// 添加编辑分类
    public function add(){
		$cid = intval($_GET['id']);
	    $rs = D("Area");
		if ($cid>0) {
            $where['id'] = $cid;
			$list = $rs->where($where)->find();
			$list['tpltitle'] = '编辑';
		}else{
		    $list['id'] = 0;
		    $list['sort'] = $rs->max('id')+1;
			$list['status'] = 1;
			$list['tpltitle'] = '添加';
		}

        $this->assign('list_tree',F('_ppvod/listvod'));
		$this->assign($list);
		$this->display('./public/system/area_add.html');
    }	

	public function insert(){
		$rs = D("Area");
		if ($rs->create()) {
			if ( false !==  $rs->add() ) {
			    $this->parea_list();
				$this->assign("jumpUrl",'?s=admin-area-show');
				$this->success('添加地区成功！');
			}else{
				$this->error('添加地区错误');
			}
		}else{
		    $this->error($rs->getError());
		}		
	}
	public function update(){
		$rs = D("Area");
		if ($rs->create()) {
			$list = $rs->save();
			if ($list !== false) {
			    $this->ppvod_list();
				$this->assign("jumpUrl",'?s=admin-area-show');
				$this->success('地区更新成功！');
			}else{
				$this->error("地区更新失败！");
			}
		}else{
			$this->error($rs->getError());
		}
	}

	// 隐藏与显示栏目
    public function status(){
		$where['id'] = intval($_GET['id']);

		$rs = D("Area");
		if (intval($_GET['sid'])) {
			$rs->where($where)->setField('status',1);
		}else{
			$rs->where($where)->setField('status',0);
		}	
		$this->parea_list();
		$this->redirect('admin-area/show');
    }
	// 删除数据
    public function del(){
		$rs = D("Area");
		$where['id'] = $_GET['id'];
		$rs->where($where)->delete();

		$this->parea_list();
		$this->success('成功删除该栏目分类与本类有关的内容！');
    }

}
?>