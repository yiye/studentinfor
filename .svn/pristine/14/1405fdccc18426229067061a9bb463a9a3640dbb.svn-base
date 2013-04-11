<?php
	require_once('connectDB.php');
	/**
	* get ,insert,delet News
	*/		
	class newsDB 
	{
		private $db;
		public $latest_id;
		function __construct()
		{
          	$dbname = 'jJcwXhkFHcLARBdBzBda';
          	$host = getenv('HTTP_BAE_ENV_ADDR_SQL_IP');
			$port = getenv('HTTP_BAE_ENV_ADDR_SQL_PORT');
			$user = getenv('HTTP_BAE_ENV_AK');
			$pwd = getenv('HTTP_BAE_ENV_SK');
 
          $this->db = new AdminDB('mySQL',$host.':'.$port,$user,$pwd,$dbname);
			$this->latest_id = $this->get_latest_news_id();
		}
		public function insert_news($n)
		{
			# code...
			//to keep the news 100
			$this->delete_news();
			$conn = $this->db->conn;
			if ($n['accessory'] != 0) {
				# code...
				$n['accessory'] = implode($n['accessory'],",");
			}
			
			if (!get_magic_quotes_gpc()) {
				foreach ($n as $key => $value) {
					# code...
					$n[$key] = addslashes($n[$key]);
				}
			}
			//var_dump($n);
			 $query_str = "insert into news value(" .$n['id'] .",'" .$n['title'] ."','" .$n['content'] ."','" .$n['date'] ."','" .$n['author'] ."','" .$n['origin'] ."','" .$n['accessory'] ."')";
			 return mysql_query($query_str,$conn);

		}
		public function get_news_by_id($value)
		{	
			$query_str = "select* from news where id=" .$value ;
			$conn = $this->db->conn;
			$result  = mysql_query($query_str,$conn);
			$n = mysql_fetch_array($result);
			if (!$n) {
				# code...
				return $n;
			}
			//var_dump($n);
			if (!get_magic_quotes_gpc()) {
				foreach ($n as $key => $value) {
					# code...
					if (is_numeric($key)) {
						# code...
						unset($n[$key]);
					} else {
						# code...
						$n[$key] = stripslashes($n[$key]);
					}
					
					
				}
			}
			if ($n['accessory'] != 0) {
				# code...
				$n['accessory'] = explode(",",$n['accessory']);
			}
			 
			return $n;

		}
		/*
		 *$times meat that the times to get the news , one time get 10 news
		 * return  two-dimensional array 
		 * like array([0]=>array(['id']=>"",['title']=>'',['origin']=>'',['author']=>'',['time']=>''),[2]=>array(...),...,[10]=>false));
		 * if the news laft in the database is less than 10 , the length $arr_ns is < 10
		 * if there is no news in the database  , it will be array([0]=> false);
		*/
		public function get_newslist_by_times($times=1)
		{
			# code...
			$start = ($times-1)*10 ;
			$query_str = "SELECT id,title,origin,author,time FROM `news` WHERE 1 ORDER BY id DESC LIMIT ".$start.",10";
			//echo $query_str;
			$conn = $this->db->conn;
			$result  = mysql_query($query_str,$conn);
			$arr_ns = array();
			while ($n = mysql_fetch_array($result)) {
				# code...

					if (!get_magic_quotes_gpc()) {
						foreach ($n as $key => $value) {
							# code...
							if (is_numeric($key)) {
								# code...
								unset($n[$key]);
							} else {
								# code...
								$n[$key] = stripslashes($n[$key]);
							}
							
							
						}
					}
					//var_dump($n);
					array_push($arr_ns, $n);

			}
			array_push($arr_ns, $n);
			
			return $arr_ns;

		}
		/*public function get_newslist_by_idandlength($value='')
		{
			# code...
		}*/
		public function delete_news(){
			
			$query_str = "SELECT COUNT(id) FROM `news` WHERE 1";
			//echo $query_str;
			$conn = $this->db->conn;
			$result  = mysql_query($query_str,$conn);
			$is_delete = (mysql_fetch_array($result));
			
			if ($is_delete[0]>100) {
				$query_str = "DELETE FROM `news` WHERE id IN (SELECT t.id FROM ( SELECT * FROM `news` ORDER BY id LIMIT 10) AS t  )";
				//echo $query_str;
				$conn = $this->db->conn;
				$result  = mysql_query($query_str,$conn);
			} else {
				# code...
				$result = false;
			}
			
			
			return $result;
		}
		public function get_latest_news_id()
		{
			$query_str ="SELECT ID FROM news ORDER BY ID DESC LIMIT 1";
			$conn = $this->db->conn;
			$result = mysql_query($query_str,$conn);
			$n = mysql_fetch_array($result);
			return (int)$n[0];
		}
	}
	
//$a = new newsDB();
//$arr = array('id' =>1099 , 'title' =>'清明节放假调课通知' , 'date'=>'2013-04-02','origin'=>'教务办公室','author'=>'李鹏	','content'=>'4月4日至6日放假调休，共3天。4月7日（星期日）上班。4月5日（星期五）的课调到4月7日（星期日）。4月4日（星期四）的课老师自行安排补课时间。','accessory'=>array('http://ssdut.dlut.edu.cn','http://ssdut.dlut.edu.cn','http://ssdut.dlut.edu.cn')); 
//$a->insert_news($arr);
	
	//var_dump($a->get_news_by_id(10275));
	//var_dump($a->get_latest_news_id());
	//var_dump($a->get_newslist_by_times(4));
	//var_dump($a->delete_news());
?>