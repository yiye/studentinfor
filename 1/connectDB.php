<?php
	/**
	* for connect  database
	* just suport mySQL
	*/

	class AdminDB
	{
		var $dbType;
		var $dbHost;
		var $user;
		var $pwd;
		var $dbName;
		var $conn;
		var $error_msg;
		var $select;

		function __construct($dbType,$host,$user,$pwd,$dbName)
		{
			# code...
			$this->dbType = $dbType;
			$this->dbHost = $host;
			$this->user = $user;
			$this->pwd = $pwd;
			$this->dbName = $dbName;
			$this->error_msg =array("end");
			$this->connDB();
			$this->select = $this->selectDB($dbName);
			
		}
		public function connDB()
		{
			# code...
			if ($this->dbType !='mySQL') {
				# code...
				die('This class not suport '.$this->dbType);
			} 
			$this->conn = mysql_connect($this->dbHost,$this->user,$this->pwd) or die('Fail to connect databse '.msql_error());
          // echo "connect good".$this->conn ;
          
			return $this->conn;
		}
		public function selectDB($dbName)
		{
			# code...
			if ($dbName=='') {
				# code...
              die("You shoulde input the databse'name : ".$dbName);
			}
			
			$this->select = mysql_select_db($dbName,$this->conn) or die('Fail to select databse'.mysql_error());
			mysql_query("set names utf8");
			return $this->select;
		}

		public function close_connect()
		{
			# code...
			//mysql_close($this->conn);
		}
		function __destruct(){
			$this->close_connect();
			// echo "disConn";
		}
	}
/*$dbname = 'oDsqiqNLQABPLyettuhz';
          	$host = getenv('HTTP_BAE_ENV_ADDR_SQL_IP');
			$port = getenv('HTTP_BAE_ENV_ADDR_SQL_PORT');
			$user = getenv('HTTP_BAE_ENV_AK');
			$pwd = getenv('HTTP_BAE_ENV_SK');
			$a = new AdminDB('mySQL',$host.':'.$port,$user,$pwd,$dbname);
echo $a->selectDB($dbname);*/
?>