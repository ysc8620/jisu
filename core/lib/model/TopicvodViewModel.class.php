<?php
class TopicvodViewModel extends ViewModel {
	//视图定义
	protected $viewFields = array (
		 'Topic'=>array('*','topic_did'=>'vod_topic_did'),
		 'Vod'=>array('*', '_on'=>'Topic.topic_did = Vod.vod_id'),
	);
}
?>