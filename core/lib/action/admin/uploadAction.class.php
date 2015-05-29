<?php
class uploadAction extends baseAction{
	// 列表		
    public function show(){
		$this->display('./public/system/upload_show.html','','text/html',true);
    }
	// 本地图片上传
	public function upload(){
		echo('<div style="font-size:12px; height:30px; line-height:30px">');
		$uppath = './'.C('upload_path').'/';
		$uppath_s = './'.C('upload_path').'-s/';
		$sid = trim($_POST['sid']);//模型
		$fileback = !empty($_POST['fileback']) ? trim($_POST['fileback']) : 'vod_pic';//回跳input
		if ($sid) {
			$uppath.= $sid.'/';
			$uppath_s.= $sid.'/';
			mkdirss($uppath);
			mkdirss($uppath_s);
		}
		import("ORG.Net.UploadFile");
		$up = new UploadFile();
		//$up->maxSize = 3292200;
		$up->savePath = $uppath;
		$up->saveRule = uniqid;
		$up->uploadReplace = true;
		$up->allowExts = explode(',',C('upload_class'));
		$up->autoSub = true;
		$up->subType = date;
		$up->dateFormat = C('upload_style');
        if (!$up->upload()) {
			$error = $up->getErrorMsg();
			if($error == '上传文件类型不允许'){
				$error .= '，可上传<font color=red>'.C('upload_class').'</font>';
			}
			exit($error.' [<a href="?s=admin-upload-show-sid-'.$sid.'-fileback-'.$fileback.'">重新上传</a>]');
			//dump($up->getErrorMsg());
		}
		$uploadList = $up->getUploadFileInfo();
		//是否添加水印
		if (C('upload_water')) {
		   import("ORG.Util.Image");
		   Image::water($uppath.$uploadList[0]['savename'],C('upload_water_img'),'',C('upload_water_pct'),C('upload_water_pos'));
		}
		//是否生成缩略图
		if (C('upload_thumb')) {
		   $thumbdir = substr($uploadList[0]['savename'],0,strrpos($uploadList[0]['savename'], '/'));
		   mkdirss($uppath_s.$thumbdir);
		   import("ORG.Util.Image");
		   Image::thumb($uppath.$uploadList[0]['savename'],$uppath_s.$uploadList[0]['savename'],'',C('upload_thumb_w'),C('upload_thumb_h'),true);
		}
		//是否远程图片
		if (C('upload_ftp')) {
			$img = D('Img');
			$img->ftp_upload($sid.'/'.$uploadList[0]['savename']);
		}
		echo "<script type='text/javascript'>parent.document.getElementById('".$fileback."').value='".$sid.'/'.$uploadList[0]['savename']."';</script>";
		echo '文件上传成功　[<a href="?s=admin-upload-show-sid-'.$sid.'-fileback-'.$fileback.'">重新上传</a>]';
		//<a href="'.$uppath.$uploadList[0]['savename'].'" target="_blank"><font color=red>'.$uploadList[0]['savename'].'</font></a>
		echo '</div>';
	}					
}
?>