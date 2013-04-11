<?php
	/*
	*return json like this
	*{ "state":"1", .......that state , if it is 1 mean you can request agina , if 2 or 3 ,you should not request 
	*   "list":[ ............a json array content the news' id ,title origin ,origin, author,time
	*			{"id":"10276", 
	*			"title":"关于组织参加辽宁省研究生首届数学建模竞赛的通知",
	*			"origin":"创新实践基地",
	*			"author":"丁宁",
	*			"time":"2013-04-03"},
*
	*			{"id":"10275",
	*			"title":"软件学院关于在全院学生党员中开展2012-2013学年“两优一先”评选表彰的通知",
				"origin":"学院分党委",
	*			"author":"辛永亮",
	*			"time":"2013-04-03"}
	*		]
	* }
	*/
	require_once("newsDB.php");
	$db = $handle_db = new newsDB();
	$last_id = $_POST['id'] or $last_id = $_GET['id'];
	$times = $_POST['times'] or $times = $_GET['times'];
	$state = 1;// 1 for ok, 2 no more, 3 empty ,
	if ($times == null) {
		# code...
		$times = 1;
	}
	$newslist = $db->get_newslist_by_times($times);
	if ( $last_id != null and $newslist[0] != false) {
		# code...
		foreach ($newslist as $key => $value) {
			# code...
			if ($value['id'] <= (int)$last_id ) {
				# code...
				array_splice($newslist, $key);
				array_push($newslist, false);
				break;
			}
			
		}
		if (count($newslist) < 11) {
				# code...
				$state = 2;
			}else{
				$state = 1;
			}
		//array_pop($newslist);//delete the elelments [11] => false

	}else if ($newslist[0] != false) {
		# code...
		if (count($newslist) < 11) {
				# code...
				$state = 2;
			}else{
				$state = 1;
			}

	} else {
		# code...
		$state = 3;
	}
	array_pop($newslist);
	$reslut = array('state' => $state , "list" => $newslist);
	include_once('chjsonendcode.php');
	echo ch_json_encode($reslut);
?>