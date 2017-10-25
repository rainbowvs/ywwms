<?php
	header('Access-Control-Allow-Origin:*');
	require 'config.php';
	
	$query = mysql_query(
		"SELECT name 
		   FROM ywms_admin_info
		  WHERE token='{$_GET['token']}'"
	) or die("SQL 错误！");
	
	if(!!$result = mysql_fetch_array($query,MYSQL_ASSOC)){
		echo json_encode(array(
			"type" => "success",
			"msg" => "获取管理员名称成功",
			"name" => $result['name']
		),JSON_UNESCAPED_UNICODE);
	}else{
		echo '{"type":"error","msg":"token异常,请重新登录"}';
	}
?>