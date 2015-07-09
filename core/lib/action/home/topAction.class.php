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
        $list_dir= $_GET['list_dir'];
        $id = $_GET['id'];
        $cate = list_search(F('_ppvod/listtree'),'list_dir='.$list_dir);
        //print_r($cate);
        $this->assign('cate', $cate[0]);
        $this->assign('id', $id);
        $data = $this->fetch('top_read','utf-8','text/html');
        if(true){
            file_put_contents(ROOT_PATH . '/top/'.$list_dir.'-'.$id.'.html', $data);
        }
        echo $data;
	}

}
?>