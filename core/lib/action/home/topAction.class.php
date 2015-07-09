<?php
class topAction extends homeAction{
    public function index(){
        $data = $this->fetch('top');
        if(true){
            file_put_contents(ROOT_PATH . 'top.html', $data);
        }
        echo $data;
    }

    public function show(){
        $list_dir= $_GET['list_dir'];
        $cate = list_search(F('_ppvod/listtree'),'list_dir='.$list_dir);
        //print_r($cate);
        $this->assign('cate', $cate[0]);
        $this->assign('list_dir', $list_dir);

        $data = $this->fetch('top_show','utf-8','text/html');
        if(true){
            file_put_contents(ROOT_PATH . '/top/'.$list_dir.'.html', $data);
        }
        echo $data;
    }
    public function read(){
		$mapname = !empty($_GET['id']) ? trim($_GET['id']):'rss';
		$limit = !empty($_GET['limit']) ? intval($_GET['limit']):30;
		$page = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$this->assign('list_map',$this->Lable_Maps($mapname,$limit,$page));
		$this->display('./public/maps/'.$mapname.'.html','utf-8','text/xml');
	}

}
?>