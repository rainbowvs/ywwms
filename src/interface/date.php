<?php
	header('Access-Control-Allow-Origin:*');
	require 'config.php';
//	$_pwd = sha1($_POST['pwd']);
	
	
//	$query = mysql_query("SELECT ldate FROM yw_admin WHERE user='{$_POST['user']}' AND pwd='{$_pwd}' LIMIT 1") or die('SQL 错误！');
//	echo json_encode(mysql_fetch_array($query, MYSQL_ASSOC));
//	echo strtotime("2017-10-22 20:03:11")*1000;
	$_token = sha1(($_POST['user']).($_POST['pwd']).($_POST['timestamp']));
	echo '{"token":'.'"'.$_token.'"'.'}';
?>