<?php
	// "http://ssdut.dlut.edu.cn/index.php/News/student.html"
	require_once('newsDB.php');
	$handle_db = new newsDB(); 
	$latest_id = $handle_db->latest_id;

	function fetch_url($url)
	{
		# code...
		$ch = curl_init();
 	 	curl_setopt($ch, CURLOPT_URL, $url);
 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 		curl_setopt($ch, CURLOPT_HEADER, 1);
 		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 	 	$output = curl_exec($ch);
 		 if ($output === FALSE) {
 	 		die("cURL Error: " .curl_error($ch));
 		 }
 		 curl_close($ch);
 		// echo $output;
 		 return $output;
	}

	function parse_list($dom)
	{
		$news_ids;
		preg_match_all("#(?<=\/index\.php\/News/)[0-9]+(?=\.html)#", $dom, $news_ids);
		return $news_ids[0];
	}
	function get_list()
	{
		
		$url = "http://ssdut.dlut.edu.cn/index.php/News/student/p/";
		$news_ids = array();
		global $latest_id;
		for ($i=1; $i < 3 ; $i++) { 
			# code...
			//;
			$temp = parse_list(fetch_url($url.$i));
				foreach ($temp as $value) {
				$value = (int)$value;
				//echo $value ."<br>";
				if ($value > $latest_id){
					array_push($news_ids, $value);
				}else{
					break 2;
				}
			}
		}
/*		$url = "http://ssdut.dlut.edu.cn/index.php/News/student.html";
		$news_ids = array();
		global $latest_id;
		$temp = parse_list(fetch_url($url));
		foreach ($temp as $value) {
				$value = (int)$value;
				echo $value ."<br>";
				if ($value > $latest_id){
					array_push($news_ids, $value);
				}else{
					break ;
				}
			}*/
		return $news_ids;
	}
	function get_content($news_id)
	{	
		$news_item = array('id'=>$news_id);
		if ($news_id) {
			$url = "http://ssdut.dlut.edu.cn/index.php/News/" .$news_id .".html";
			$dom = fetch_url($url);
			 $dom = preg_replace("#(href=\"|src=\")/#i", 'href="http://ssdut.dlut.edu.cn/', $dom);
			 preg_match_all("#(?<=class\=\"title\">).*(?=</td>)#iUs", $dom, $temp);
             $news_item['title'] = $temp[0][0];
             preg_match_all("#20[0-9]{2}\-(0[0-9]|12){1}\-[0|1|3][0-9]#", $dom, $temp);
             $news_item['date'] = $temp[0][0];
             preg_match_all("#(?<=来源:).*(?=关注)#u", $dom, $temp);
             $news_item['origin'] = $temp[0][0];
             preg_match_all("#(?<=width:80\%\">发表人：).*(?=<\/td>\s*<td\sstyle=\"width:8)#u",$dom, $temp);
             $news_item['author'] = $temp[0][0];
             preg_match_all("#(?<=class=\"content\">).*(</p>|</span>|</a>){1}(?=<\/td>)#us",$dom,$temp);
             //preg_match_all(pattern, subject, matches)
             $news_item['content'] = $temp[0][0];
             preg_match_all("#(?<=href\=\")http://ssdut.dlut.edu.cn\/Attachments\/file\/.*\.(?:docx|doc|pptx|ppt|xlsx|xls){1}(?=\"\starget=\"_blank\")#iuU", $dom,$temp);
             $news_item['accessory'] = $temp[0][0];
          //var_dump($temp);
		}
		return $news_item;
		
	}
	// var_dump(get_list());
	//var_dump(parse_list(fetch_url("http://ssdut.dlut.edu.cn/index.php/News/student.html")));
    //get_content("10272");
	$news_id_list = get_list();
   foreach ($news_id_list as $value) {
		# code...
   		$handle_db->insert_news(get_content($value));

	}


?>