<?php
	header('Access-Control-Allow-Origin:*');
	require 'config.php';
	
	$_user = $_POST['user'];
	//密码加密
	$_pwd = sha1($_POST['pwd']);
	$_date = date('Y-m-d H:i:s',$_POST['timestamp']/1000);

	$query = mysql_query(
		"UPDATE ywms_admin_info 
			SET ldate='{$_date}' 
		  WHERE user='{$_POST['user']}' AND pwd='{$_pwd}' 
		  LIMIT 1"
	) or die('SQL 错误！');
		
	//进行判断是否有值
	if (mysql_affected_rows() == 1){
		//sql-查询,账号和密码同时进行验证
		mysql_free_result($query);
		$query = mysql_query(
			"SELECT token
			   FROM ywms_admin_info
			  WHERE user='{$_POST['user']}' AND pwd='{$_pwd}'
			  LIMIT 1"
		) or die('SQL 错误！');
		if(!!$result = mysql_fetch_array($query,MYSQL_ASSOC)){
			echo json_encode(array(
				"type" => "success",
				"msg" => "登录成功",
				"token" => $result['token']
			),JSON_UNESCAPED_UNICODE);
//			$_token = sha1(($_POST['user']).($_POST['pwd']).($_POST['timestamp']));
//			echo '{"type":"success","msg":"登录成功","token":'.'"'.$result.'"'.'}';
		}else
			echo '{"type":"error","msg":"token异常,请联系管理员"}';
	}else{
		echo '{"type":"fail","msg":"账号或密码错误"}';
	}
	mysql_close();

?>