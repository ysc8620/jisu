<?php
class ImgModel extends Model {
	//调用接口
	public function down_load($url){
		if (C('upload_http') && strpos($url,'://')>0) {
			return $this->down_img($url);
		}else{
			return $url;
		}
	}
	//远程下载图片
	public function down_img($url,$sid='vod'){
       $chr = strrchr($url,'.');
	   $imgUrl = uniqid();
	   $imgPath = $sid.'/'.date(C('upload_style'),time()).'/';	
	   $imgPath_s = './'.C('upload_path').'-s/'.$imgPath;
	   $filename = './'.C('upload_path').'/'.$imgPath.$imgUrl.$chr;
	   $get_file = js_file_get_contents($url);
	   if ($get_file) {
		   write_file($filename,$get_file);
		   //是否添加水印
		   if(C('upload_water')){
			   import('ORG.Util.Image');
			   Image::water($filename,C('upload_water_img'),'',C('upload_water_pct'),C('upload_water_pos'));
		   }		   
		   //是否生成缩略图
		   if(C('upload_thumb')){
			   mkdirss($imgPath_s);
			   import('ORG.Util.Image');
			   Image::thumb($filename,$imgPath_s.$imgUrl.$chr,'',C('upload_thumb_w') ,C('upload_thumb_h'),true);
		   }
		   //是否上传远程
		   if (C('upload_ftp')) {
			   $this->ftp_upload($imgPath.$imgUrl.$chr);
		   }
		   return $imgPath.$imgUrl.$chr;
	   }else{
			return $url;
	   } 
	}	
	//远程ftp附件
	public function ftp_upload($imgurl){
		Vendor('Ftp.Ftp');
		$ftpcon = array(
			'ftp_host'=>C('upload_ftp_host'),
			'ftp_port'=>C('upload_ftp_port'),
			'ftp_user'=>C('upload_ftp_user'),
			'ftp_pwd'=>C('upload_ftp_pass'),
			'ftp_dir'=>C('upload_ftp_dir'),
		);
		$ftp = new ftp();
		$ftp->config($ftpcon);
		$ftp->connect();
		$ftpimg = $ftp->put(C('upload_path').'/'.$imgurl,C('upload_path').'/'.$imgurl);
		if(C('upload_thumb')){
			//$imgurl_s = strrchr($imgurl,"/");
			//$ftpimg_s = $ftp->put(C('upload_path').'/thumb'.$imgurl_s, 'thumb'.$imgurl_s);
			$ftpimg_s = $ftp->put(C('upload_path').'-s/'.$imgurl, C('upload_path').'-s/'.$imgurl);
		}
		if(C('upload_ftp_del')){
			if($ftpimg){
				@unlink(C('upload_path').'/'.$imgurl);
			}
			if($ftpimg_s){
				@unlink(C('upload_path').'/thumb'.$imgurl_s);
			}
		}
		$ftp->bye();
	}
}
?>