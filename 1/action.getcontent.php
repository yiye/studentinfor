<?php
	/*
	*get news detail that with a special id 
	*it will return json like this
	*{
		"state" : "1" /// 1 for ok , 2 for bad.
		"detail":{"id":"10272",
				  "title":"《营养与健康》考试安排",
				  "content":"《营养与健康》课程考试时间为4月17日周三下午随堂考试，具体考场安排见附件文件。",
				  "time":"2013-04-03",
				  "author":"韩涛",
				  "origin":"教务办公室",
				  "accessory":"http://ssdut.dlut.edu.cn/Attachments/file/20130403153536_31794.xls"
				  }
	}
	*/
	require_once("newsDB.php");
	$db = $handle_db = new newsDB();
	$news_id = $_POST['id'] or $news_id = $_GET['id'];
	$state = 1; // 1 for ok , 2 for bad.
	if ($news_id == null) {
		# code...
		$state = 2;
		$detail = false;
	}else{
		
		$detail = $db->get_news_by_id($news_id);
		$state = 1;
	}
	$reslut = array('state' => $state , "detail" => $detail);
	include_once('chjsonendcode.php');
	echo ch_json_encode($reslut);
?>