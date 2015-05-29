<?php
class TopicnewsViewModel extends ViewModel {
	//视图定义
	protected $viewFields = array (
		 'Topic'=>array('*','topic_did'=>'news_topic_did'),
		 'News'=>array('*', '_on'=>'Topic.topic_did = News.news_id'),
	);
}
?>