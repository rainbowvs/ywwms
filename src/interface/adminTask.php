<?php
	header('Access-Control-Allow-Origin:*');
	$date = date("Y-m-d H:i:s",time());
	require 'config.php';
	if($_GET['handle'] == 'add'){
		$query = mysql_query(
			"INSERT INTO ywms_admin_task (content,token,cdate) 
				  VALUES ('{$_GET['content']}','{$_GET['token']}','{$date}')"
		) or die("SQL 错误！");
		
		if(mysql_affected_rows() == 1){
			mysql_free_result($query);
			$query = mysql_query(
				"SELECT *
				   FROM ywms_admin_task
				  WHERE cdate='{$date}'"
			);
			if(!!$result = mysql_fetch_array($query, MYSQL_ASSOC))
				echo json_encode(array(
					"type" => "success",
					"msg" => "任务添加成功",
					"task" => $result
				));
		}else{
			echo '{"type":"error","msg":"token异常,请重新登录"}';
		}
	}else if($_GET['handle'] == 'get'){
		$query = mysql_query(
			"SELECT * 
			   FROM ywms_admin_task 
			  WHERE token='{$_GET['token']}'
		   ORDER BY id"
		) or die("SQL 错误！");
		
		$list = array();
		while (!!$row = mysql_fetch_array($query, MYSQL_ASSOC))
			$list[] = $row;
			
		echo json_encode(array(
			"type" => "success",
			"msg" => "任务获取成功",
			"tasks" => $list
		),JSON_UNESCAPED_UNICODE);
	}else if($_GET['handle'] == 'del'){
		$query = mysql_query(
			"DELETE FROM ywms_admin_task 
				   WHERE id='{$_GET['id']}' AND token='{$_GET['token']}'"
		) or die("SQL 错误！");
		
		if(mysql_affected_rows() == 1){
			echo '{"type":"success","msg":"任务删除成功"}';
		}else{
			echo '{"type":"error","msg":"token异常,请重新登录"}';
		}
	}
	mysql_close();
?>