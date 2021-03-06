<?php
class videoAction extends homeAction{
    function channel(){
        echo '--';
        $Url = js_param_url();
        $JumpUrl = js_param_jump($Url);
        $JumpUrl['p'] = '{!page!}';
        C('jumpurl',UU('home-vod/show',$JumpUrl,false,true));
        C('currentpage',$Url['page']);

        $List = list_search(F('_ppvod/list'),'list_dir='.$Url['list_dir']);

        $channel = $this->Lable_Vod_List($Url,$List[0]);
        $this->assign($channel);

        $this->display($channel['list_skin']);
    }

    function detail(){
        $array_detail = $this->get_cache_detail( intval($_GET['id']) );

        if($array_detail){
            $this->assign($array_detail['show']);
            $this->assign($array_detail['read']);
            $this->display($array_detail['read']['vod_skin_detail']);
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
        $array = $rs->where($where)->find();
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
}
?>