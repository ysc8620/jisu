<?php 
class AreaModel extends AdvModel {
	//自动验证
	protected $_validate=array(
		array('name','require','必须填写地区名称！',1),
	);

}
?>