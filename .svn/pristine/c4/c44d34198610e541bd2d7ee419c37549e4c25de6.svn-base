<?php
	/**
	* 处理memcache类
	*/
   require_once ('BaeMemcache.class.php');
   require_once('recodeDB.php');

	class HandleMem
	{
		/*
		*将二维数组转换成字符串
		*参数是一个二维数组
		*返回字符串:子数组元素用"$"分割,父数组用";"分割
		*/
		public function toString($arr)
		{
			# code...
			foreach ($arr as $key => $value) {
				# code...
				$arr[$key]=implode("$",$value); 
			}
			return implode(';', $arr);
		}
		/*
		*将一个字符串转换为一个二维数组(子数组元素用"$"分割,父数组用";"分割)
		*返回一个二维数组
		*/
		public function toArray($str)
		{
			$arr = explode(';', $str);
			foreach ($arr as $key => $value) {
				# code...
				$arr[$key] = explode('$', $value);
			}
			return $arr;
		}
		/*
		*数组转换为JSON字符串存入baememcache中,
		*$key为用户传回的ID
		*/
		public function storeInMem($key,$arr)
		{
			# code...
            include_once('chjsonendcode.php');
			$str = ch_json_encode($arr);
          //echo $str;
          // $key = "111";
			$mem = new BaeMemcache();
			if ($mem->get($key)) {
				# code...
				$mem->replace($key,$str,0,300);
			}else{
				 $mem->add($key,$str,0,300);
			}
          /*$recode = new Recode();
          $recode->insert($key);
          $recode->insert($str);*/
		}
		/*
		*从根据$key从内村中读取
		*返回至为读取的JSON字符串转换成的数组,读取失败返回false
		*/
		public function getFromMem($key)
		{
			# code...
          // $key = "111";
			$mem = new BaeMemcache();
			$str = $mem->get($key);
         /* $recode = new Recode();
          $recode->insert($key);
          $recode->insert($str);*/
			
			if ($str) {
				# code...
              // echo "get is $str";
				return json_decode($str,true);
			}else{
				return false;
			}
			
		}
		/*
		*根据key和time从内存中夺取books的列表
		*返回值为一个二维数组,读取失败则返回false
		*/
		public function getBooksByTimes($key,$times=1)
		{
			# code...
			if ($times>=1) {
				# code...
				$times = $times -1;
             /* $recode = new Recode();
              $recode->insert($times);*/
				$arr = $this->getFromMem($key);
				if ($arr) {
					 return array_slice($arr, $times*5,5);
				} else {
					return false;
				}
				
             
			} else {
				return false;
			}
			
			
		}
	}
/*$a = new HandleMem();
	$arr = array(0=>
  array(
    "name"=> "Frontiers of high performance computing and networking : ISPA 2006 Workshops : ISPA 2006 internation",
    "number"=> "73.923 I73-4# ISPA 2006 ",
    "publish"=> "/ Springer, / c2006.",
    "msg"=> "馆藏/可借：1/0"
  ),
  1=>
  array(
    "name"=>"PHP核心技术与最佳实践",
    "number"=> "TP312PH L478 ",
    "publish"=> "列旭松, 陈文著 / 机械工业出版社 / 2013",
    "msg"=>"馆藏/可借：6/4"
  ));
$a->storeInMem("1234",$arr);
var_dump($a->getBooksByTimes("1234",1));*/
?>