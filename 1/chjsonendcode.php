<?php
	/* **********************************************************
	* 处理 json_encode() 不支持中文的情况
	*
	* 实际应用中，当有中文字符时，当直接使用 json_encode() 函数会使汉字不能正常显示
	* 所以有了这个适用性比较广的函数 ch_json_encode() [2] 来解决这个问题
	*
	* charset: UTF-8
	* create date: 2012-7-8
	* **********************************************************
	* @author Zhao Binyan
	* @copyright (C) 2011-2012 itbdw
	*//**
	* 处理 json_encode() 不支持中文的情况
	*
	* @param array|object $data
	* @return array|object
	*/
	function ch_json_encode($data) {
		/**
		* 将中文编码
		* @param array $data
		* @return string
		*/
		function ch_urlencode($data) {
			if (is_array($data) || is_object($data)) {
				foreach ($data as $k => $v) {
					if (is_scalar($v)) {
						if (is_array($data)) {
							$data[$k] = urlencode($v);
						} elseif (is_object($data)) {
							$data->$k = urlencode($v);
						}
					} elseif (is_array($data)) {
						$data[$k] = ch_urlencode($v);//递归调用该函数
					} elseif (is_object($data)) {
						$data->$k = ch_urlencode($v);
					}
				}
			}
			return$data;
		}
		$ret = ch_urlencode($data);
		$ret = json_encode($ret);
		return urldecode($ret);
	}
?>