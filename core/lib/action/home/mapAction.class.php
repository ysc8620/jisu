<?php
class mapAction extends homeAction{
    public function index(){
        $data = $this->fetch('map','utf-8','text/html');
        if(true){
            file_put_contents(ROOT_PATH . 'sitemap.html', $data);
        }
        echo $data;

    }

    public function news(){

        $data = $this->fetch('news','utf-8','text/html');
        if(true){
            file_put_contents(ROOT_PATH . 'new.html', $data);
        }
        echo $data;
    }
    public function show(){
		$mapname = !empty($_GET['id']) ? trim($_GET['id']):'rss';
		$limit = !empty($_GET['limit']) ? intval($_GET['limit']):30;
		$page = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$this->assign('list_map',$this->Lable_Maps($mapname,$limit,$page));
		$this->display('./public/maps/'.$mapname.'.html','utf-8','text/xml');
	}
    public function rss(){
		$rs = M("Vod");
		$where['vod_id'] = $_GET['id'];
		$where['vod_status'] = 1;
		$array_vod = $rs->where($where)->find();
		if($array_vod){
			$array = $this->Lable_Vod_Read($array_vod);
			$this->assign($array['show']);
			$this->assign($array['read']);
			$this->display('./public/maps/rssid.html','utf-8','text/xml');
		}
	}							
}
?>