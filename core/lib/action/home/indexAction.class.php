<?php
class indexAction extends homeAction{
    public function index(){
		if (!is_file('./runtime/install/install.lock')) {
			$this->assign("jumpUrl",'index.php?s=admin-install');
			$this->error('您还没安装本程序，请运行 install.php 进入安装!');
		}
		if(C('url_html')){
			redirect('index'.C('url_html_suffix'));
		}
		$this->assign($this->Lable_Index());
	    $data = $this->fetch('pp_index');
        if(true){
            file_put_contents(ROOT_PATH . 'index.html', $data);
        }
        echo $data;
    }
    function music()
    {
        $this->display('music');
    }
}
?>