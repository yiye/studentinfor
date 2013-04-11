<?php
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
?>


<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title>学生周知</title>
	<style type="text/css">
		*{
			padding: 0;
			margin: 0;
		}
		.clearfix{
			overflow: auto;
		}
		.clearfix:after{
			height: 0;
			content: "";
			display: block;
			clear: both;
		}
		.content{
			width: 100%;
			height: auto;
			overflow: hidden;
		}
		.content ul{
			list-style-type: none;
			width: 100%;
		}
		.item{
			width: 100%;
			padding: 5px 5px;
			border-top: 2px solid #006805;
		}
		.item:hover{
			background-color: rgb(238, 238, 238);
		}
		.item a{
			text-decoration: none;
		}
		.item a:hover{
			text-decoration: none;
		}
		.item h1{
			color: rgb(37, 37, 37);
			font-size: 1.1em;
		}
		.origin{
			display: inline-block;
			float: left;
			font-size: 0.9em;
			color:rgb(111, 111, 111);
		}
		.date{
			display: inline-block;
			float: right;
			font-size: 0.9em;
			margin-right: 25px;
			color: rgb(111, 111, 111);
		}
		.btn{
			width: 100%;
			height: 1.5em;
			margin-top: 10px;
		}
		.btn a{
			text-align: center;
			text-decoration: none;
			line-height: 1.1em;
			display:inline-block;
			padding: 2px 5px;
			border-radius: 5px;
			background: #04BC01;
			color: black;
		}
		.btn a:hover{
			color: black;
			background:rgb(5, 145, 11);
		}
		.btn .next{
			float: right;
			margin-right: 20px;
		}
		.btn .up{
			float: left;
			margin-left: 20px;
		}
		.btn .disnone{
			display: none;
		}
	</style>
</head>
<body>
	<div class="content">
		<ul>
			<!-- <li class="item">
				<a href="detail.simple.php?id=">
					<h1>软件学院2012-13学年第二学期经济困难学生复查</h1>
					<div class="msg clearfix">
						<span class="origin">教务处</span>
						<span class="date">2012-3-4</span>
					</div>
				</a>
			</li> -->
			<?php
				if ($state == 1) {
					# code...
					$html = "<li class='item'>
				<a href='detail.simple.php?id=%s'>
					<h1>%s</h1>
					<div class='msg clearfix'>
						<span class='origin'>%s</span>
						<span class='date'>%s</span>
					</div>
				</a>
			</li>";
					foreach ($newslist as $key => $value) {
						echo sprintf($html,$value['id'],$value['title'],$value['origin'],$value['time']);
					}
				}else{
					echo "<li class='item'><h1>没有了</h1></li>";
				}
			?>
		</ul>
		<div class = "btn">
			<?php
				if ($times==1) {
					echo "<a href='#'class='up disnone'>上一页</a>";
				}else{
					echo "<a href='index.simple.php?times=".($times-1)."'class='up'>上一页</a>";
				}
				if ($state == 2 || $state ==3) {
					echo '<a href="#" class="next disnone">下一页</a>';
				} else {
					echo '<a href="index.simple.php?times='.($times+1).'" class="next">下一页</a>';
				}
			?> 
			
		</div>
	</div>
</body>
</html>