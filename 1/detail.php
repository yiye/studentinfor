<?php
	require_once("newsDB.php");
	$db = $handle_db = new newsDB();
	$news_id = $_POST['id'] or $news_id = $_GET['id'];
	$detail = $db->get_news_by_id($news_id);
?>	

<!doctype html>
<html>
<head>
	<title>学生周知</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.3.0.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
	<script src="http://libs.baidu.com/jquery/1.9.0/jquery.min.js"></script>
	<script src="http://libs.baidu.com/jquerymobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
</head>
<body>
<div data-role="page" id="detail-page" data-theme="c" data-add-back-btn="true">
    <div data-role="header"  data-theme="c"  data-position="fixed" >
        <h3><?php echo $detail['title'];?></h3>
    </div>
    <div data-role="content">
       <?php echo $detail['content'];?>
    </div>
    <div data-role="footer" data-theme="none"></div>
</div>
<script type="text/javascript" src="js/custom.js"></script>
</body></html>