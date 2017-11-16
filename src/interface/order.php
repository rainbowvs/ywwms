<?php
	header('Access-Control-Allow-Origin:*');
	require 'config.php';
	list($tmp1, $tmp2) = explode(' ', microtime());
	$msectime = (float)sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
	$date = date("Y-m-d H:i:s",$tmp2);
	
	//总页数 = 总订单数 ÷ 每页显示数量
	$quantity = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ywms_order_info"),MYSQL_NUM)[0];//总订单数
	$pageSize = 10;//每页显示数量
	$totalPage = ceil($quantity / $pageSize);//总页数
	if(!isset($_GET['page'])){
	    $page = 1;//当前页数,默认为1,
	}else{
	    $page = $_GET['page'];
	    if($page > $totalPage)
	        $page = $totalPage;//如果加载更多到最后一页,永远显示最后一页
	}
	$limit = ($page - 1) * $pageSize;//算法,从第几条开始加载
	
	mysql_free_result();
	if($_GET['handle'] == 'set'){
		//生成oid(订单编号)
		$r1 = (string)mt_rand(10,99);
		$r2 = (string)mt_rand(10,99);
		$oid = $r1.(string)$msectime.$r2;
		
		$uid = mysql_fetch_array(mysql_query("SELECT uid FROM ywms_user_info WHERE {$_GET['user']}"),MYSQL_NUM)[0];
		if(!isset($uid))
			exit('{"type":"error","msg":"该用户不存在"}');
		
		mysql_free_result();
		
		$query = mysql_query(
			"INSERT INTO ywms_order_info (oid,uid,state,totalPrice,address,cdate) 
				  VALUES ('{$oid}','{$uid}','{$_GET['state']}','{$_GET['totalPrice']}','{$_GET['address']}','{$date}')"
		) or die("SQL 错误！");
		
		if(mysql_affected_rows() == 1){
			mysql_free_result($query);
			$query = mysql_query(
				"SELECT u.user AS user,
						o.oid AS oid,
						o.uid AS uid,
						o.state AS state,
						o.totalPrice AS totalPrice,
						o.address AS address
				   FROM ywms_user_info u INNER JOIN ywms_order_info o
				     ON u.uid=o.uid
				  WHERE o.oid='{$oid}'
				  LIMIT 1"
			);
			if(!!$result = mysql_fetch_array($query, MYSQL_ASSOC))
				echo json_encode(array(
					"type" => "success",
					"order" => "订单添加成功",
					"order" => $result
				));
		}else{
			echo '{"type":"error","msg":"token异常,请重新登录"}';
		}
	}else if($_GET['handle'] == 'get'){
		$query = mysql_query(
			"SELECT u.user AS user,
					u.name AS name,
					o.oid AS oid,
					o.uid AS uid,
					o.state AS state,
					o.totalPrice AS totalPrice,
					o.address AS address,
					o.cdate AS cdate 
			   FROM ywms_user_info u INNER JOIN ywms_order_info o
			     ON o.uid=u.uid
			  LIMIT {$limit},{$pageSize}"
		) or die("SQL 错误！");
		
		$list = array();
		while (!!$row = mysql_fetch_array($query, MYSQL_ASSOC))
			$list[] = $row;
			
		echo json_encode(array(
			"type" => "success",
			"msg" => "订单获取成功",
			"orders" => $list,
			"totalPage" => $totalPage
		),JSON_UNESCAPED_UNICODE);
	}else if($_GET['handle'] == 'update'){
		$query = mysql_query(
			"UPDATE ywms_order_info o
		 INNER JOIN ywms_user_info u
		 		 ON o.uid=u.uid 
				SET o.state='{$_GET['state']}',
					o.totalPrice='{$_GET['totalPrice']}',
					o.address='{$_GET['address']}',
					o.cdate='{$_GET['cdate']}',
					u.user = '{$_['user']}'
			  WHERE o.uid='{$_GET['uid']}' AND o.oid='{$_GET['oid']}'"
		) or die('SQL 错误！');
		if (mysql_affected_rows() > 0){
			echo '{"type":"success","msg":"订单修改成功"}';
		}else{
			echo '{"type":"error","msg":"token异常,请重新登录"}';
		}
	}else if($_GET['handle'] == 'del'){
		$query = mysql_query(
			"DELETE FROM ywms_order_info 
				   WHERE oid IN (".$_GET['oids'].")"
		) or die("SQL 错误！");
		
		if(mysql_affected_rows() != 0){
			echo '{"type":"success","msg":"订单删除成功"}';
		}else{
			echo '{"type":"error","msg":"token异常,请重新登录"}';
		}
	}
	
	mysql_close();
?>