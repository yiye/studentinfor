<?php
	include_once("action.searchbook.php");
	include_once("handleMem.php");
	$time1 = time();
	$a = new SearchBook("1234354","数据结构与算法");
	$b = $a->getFive()
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<?php
	print_r($b);
	$time2 = time();
	?>
	<hr/>
	<?php
	echo $time2-$time1;
	?>
	<hr/>
	<?php
		$c = new HandleMem();
		print_r($c->getBooksByTimes("1234354",2));
		sleep(3);
		$time3 = time();
	?>
	<hr/>
	<?php
	echo $time3-$time2;
	?>
	<hr/>
</body>
</html>