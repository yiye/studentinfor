
 <?php
  require_once('connectDB.php');
      /**
      * 将用户最近的ID存放在数库中
      */
      class UserDB
      {
          private $db;
          private $conn;
          function __construct()
          {
              # code...
            $dbname = 'jJcwXhkFHcLARBdBzBda';
            $host = getenv('HTTP_BAE_ENV_ADDR_SQL_IP');
            $port = getenv('HTTP_BAE_ENV_ADDR_SQL_PORT');
            $user = getenv('HTTP_BAE_ENV_AK');
            $pwd = getenv('HTTP_BAE_ENV_SK');
            $this->db = new AdminDB('mySQL',$host.':'.$port,$user,$pwd,$dbname);
            $this->conn = $this->db->conn;
            //echo "conn ".$this->conn;
          }
          function updateNews($user,$newsId){
          	if ($this->getLastNews($user)) {
          		# code...
          		 $strQuery = "UPDATE `weixin` SET `lastNews`=$newsId WHERE `user`='$user'";
          	} else {
          		# code...
          		$strQuery = "INSERT INTO `weixin`(`user`, `lastNews`) VALUES ('$user',$newsId)";
          	}
        	//echo $strQuery.'\n';
            return mysql_fetch_array(mysql_query($strQuery,$this->conn));
          }
          /*
          *获取用户相对应的新闻id
          *成功返回id,失败返回false
          */
          public function getLastNews($user)
          {
          	# code...
          	$strQuery = "SELECT `lastNews` FROM `weixin` WHERE `user`='$user'";
          	//echo "$strQuery".'\n';
          	$result = mysql_query($strQuery,$this->conn);
          	$result =mysql_fetch_array($result);
          	//var_dump($result);
          	if ($result) {
          		# code...
          		return $result[0];
          	} else {
          		# code...
          		return false;
          		
          	}
          	
          }
          /*\
          *获取用户id用来作为memcache的key
          */
          public function getId($user)
          {
            $strQuery = "SELECT `id` FROM `weixin` WHERE `user`='$user'";
            $result = mysql_query($strQuery,$this->conn);
            $result =mysql_fetch_array($result);
            if ($result) {
              return $result[0];
            } else {
              $strInsert = "INSERT INTO `weixin`(`user`) VALUES ('$user')";
              mysql_query($strInsert,$this->conn);
              $result = mysql_query($strQuery,$this->conn);
              $result =mysql_fetch_array($result);
              return $result[0];
            }
            
          }
      }
      /*$a= new UserDB();
      var_dump($a->updateNews("123jkjk_djfs",1234));
      var_dump($a->updateNews("123jkjk_djfs",3456));
      var_dump($a->getLastNews("233"));
       var_dump($a->getLastNews("123jkjk_djfs"));*/
 ?>
