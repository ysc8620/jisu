<?php
class vodAction extends homeAction{
    // 影视搜索
    public function search(){
		//获取地址栏参数
		$Url = js_param_url();

		//$JumpUrl传递分页及跳转参数
		$JumpUrl = js_param_jump($Url);
		$JumpUrl['p'] = '{!page!}';
		C('jumpurl',UU('home-vod/search',$JumpUrl,false,true));
		C('currentpage',$Url['page']);
		//变量赋值
		$search = $this->Lable_Search($Url,'vod');


		$this->assign($search);
		$this->display($search['search_skin']);
    }			
    // 影视列表页
    public function show(){
		$Url = js_param_url();
		$JumpUrl = js_param_jump($Url);
		$JumpUrl['p'] = '{!page!}';	
		C('jumpurl',UU('home-vod/show',$JumpUrl,false,true));
		C('currentpage',$Url['page']);
		$List = list_search(F('_ppvod/list'),'list_dir='.$Url['list_dir']);
		$channel = $this->Lable_Vod_List($Url,$List[0]);
		$this->assign($channel);
        $this->assign('select_list_dir', $Url['list_dir']);
        $data = $this->fetch($channel['list_skin']);
        $path = ROOT_PATH . $Url['list_dir'];
        if(file_exists($path)){
            mkdir($path, 0777, true);
        }
        if(true){

            file_put_contents(ROOT_PATH . $Url['list_dir'].'/index.html', $data);
        }
        echo $data;
    }

    public function type(){
        $Url = js_param_url();
		$JumpUrl = js_param_jump($Url);
		$JumpUrl['p'] = '{!page!}';
        $page = intval($_GET['page']);
		C('jumpurl',UU('home-vod/show',$JumpUrl,false,true));
		C('currentpage',$Url['page']);

		$List = list_search(F('_ppvod/list'),'list_dir='.$Url['list_dir']);
        $this->assign('select_list_dir', $Url['list_dir']);
        $this->assign($Url);
		$channel = $this->Lable_Vod_List($Url,$List[0]);


		$this->assign($channel);
		$data = $this->fetch($channel['list_skin_type']);
        $name =  "{$Url['class_id']}-{$Url['area']}-{$Url['year']}.html";

       if($page > 1){
           $name = "{$Url['class_id']}-{$Url['area']}-{$Url['year']}-{$page}.html";
       }
        $path = ROOT_PATH . $Url['list_dir'];
        if(file_exists($path)){
            mkdir($path, 0777, true);
        }
        if(true){

           file_put_contents(ROOT_PATH . $Url['list_dir'].'/'.$name, $data);
        }
        echo $data;
    }

    // 多分类筛选
    public function types(){
		$Url = js_param_url();
		$Type = $this->Lable_Vod_Type($Url);
		$this->assign($Type);
		$this->display($Type['type_skin']);
    }	
	// 影片内容页
    public function read(){
		$array_detail = $this->get_cache_detail( intval($_GET['id']) );
        $list_dir = $_GET['list_dir'];

		if($array_detail){
            $this->assign('select_list_dir', $list_dir);
			$this->assign($array_detail['show']);
			$this->assign($array_detail['read']);
			$data = $this->fetch($array_detail['read']['vod_skin_detail']);
            $path = ROOT_PATH . $list_dir;
            if(file_exists($path)){
                mkdir($path, 0777, true);
            }
            if(true){
                file_put_contents(ROOT_PATH .$list_dir.'/'.intval($_GET['id']) .".html", $data);
            }
            echo $data;
		}else{
			$this->assign("jumpUrl",C('site_path'));
			$this->error('此影片已经删除，请选择观看其它节目！');
		}
    }

	// 从数据库获取数据
	private function get_cache_detail($vod_id){
		if(!$vod_id){ return false; }
		//优先读取缓存数据
		if(C('data_cache_vod')){
			$array_detail = S('data_cache_vod_'.$vod_id);
			if($array_detail){
				return $array_detail;
			}
		}
		//未中缓存则从数据库读取
		$where = array();
		$where['vod_id'] = $vod_id;
		$where['vod_cid'] = array('gt',0);
		$where['vod_status'] = array('eq',1);
		$rs = D("Vod");
		$array = $rs->where($where)->relation('Tag')->find();
		if($array){
			//解析标签
			$array_detail = $this->Lable_Vod_Read($array);
			if( C('data_cache_vod') ){
				S('data_cache_vod_'.$vod_id, $array_detail, intval(C('data_cache_vod')));
			}
			return $array_detail;
		}
		return false;
	}

    // 播放
    function play(){
        $id = intval($_GET['id']);
        $url = urldecode(trim($_GET['url']));
        return header('Location:'.$url);
    }
}
?>