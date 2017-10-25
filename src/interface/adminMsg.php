<?php
	header('Access-Control-Allow-Origin:*');
	require 'config.php';
	if($_GET['handle'] == 'get'){
		$query = mysql_query("SELECT * FROM ywms_admin_msg WHERE receiver='{$_GET['receiver']}'") or die("SQL 错误！");
		
		$list = array();
		while (!!$row = mysql_fetch_array($query, MYSQL_ASSOC))
			$list[] = $row;
			
		echo json_encode(array(
			"type" => "success",
			"msg" => "信息获取成功",
			"msgs" => $list
		),JSON_UNESCAPED_UNICODE);
	}else if($_GET['handle'] == 'del'){
		$query = mysql_query(
			"DELETE FROM ywms_admin_msg 
				   WHERE id IN (".$_GET['ids'].")"
		) or die("SQL 错误！");
		
		if(mysql_affected_rows() != 0){
			echo '{"type":"success","msg":"信息删除成功"}';
		}else{
			echo '{"type":"error","msg":"token异常,请重新登录"}';
		}
	}
?>