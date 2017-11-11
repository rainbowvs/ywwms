<?php
	header('Access-Control-Allow-Origin:*');
	require 'config.php';
	$date = date("Y-m-d H:i:s",time());
	$r1 = (string)mt_rand(10,99);
	$r2 = (string)mt_rand(10,99);
	$oid = $r1.(string)$date.$r2;
	
	//总页数 = 总商品数 ÷ 每页显示数量
	$quantity = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ywms_order_info"),MYSQL_NUM)[0];//总商品数
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
		$query = mysql_query(
			"INSERT INTO ywms_order_info (oid,token,state,totalPrice,address,cdate) 
				  VALUES ('{$oid}','{$_GET['token']}','{$_GET['state']}','{$_GET['totalPrice']}','{$_GET['address']}','{$date}')"
		) or die("SQL 错误！");
		
		if(mysql_affected_rows() == 1){
			mysql_free_result($query);
			$query = mysql_query(
				"SELECT ywms_user_info.user,
						ywms_order_info.id,
						ywms_order_info.oid,
						ywms_order_info.state,
						ywms_order_info.totalPrice,
						ywms_order_info.address
				   FROM ywms_user_info INNER JOIN ywms_order_info
				     ON ywms_user_info.token='{$_GET['token']}'
				  WHERE ywms_order_info.cdate='{$date}'"
			);
			if(!!$result = mysql_fetch_array($query, MYSQL_ASSOC))
				echo json_encode(array(
					"type" => "success",
					"msg" => "商品添加成功",
					"order" => $result
				));
		}else{
			echo '{"type":"error","msg":"token异常,请重新登录"}';
		}
	}else if($_GET['handle'] == 'get'){
		$query = mysql_query(
			"SELECT ywms_user_info.user,
					ywms_order_info.id,
					ywms_order_info.oid,
					ywms_order_info.state,
					ywms_order_info.totalPrice,
					ywms_order_info.address
			   FROM ywms_user_info INNER JOIN ywms_order_info
			     ON ywms_user_info.token='{$_GET['token']}'
			  WHERE ywms_order_info.cdate='{$date}'
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
			"UPDATE ywms_order_info
				SET token='{$_GET['token']}',
					state='{$_GET['state']}',
					totalPrice='{$_GET['totalPrice']}',
					address='{$_GET['address']}',
					cdate='{$_GET['cdate']}' 
			  WHERE id='{$_GET['id']}' 
			  LIMIT 1"
		) or die('SQL 错误！');
		if (mysql_affected_rows() == 1){
			echo '{"type":"success","msg":"订单修改成功"}';
		}else{
			echo '{"type":"error","msg":"token异常,请重新登录"}';
		}
	}else if($_GET['handle'] == 'del'){
		$query = mysql_query(
			"DELETE FROM ywms_order_info 
				   WHERE id IN (".$_GET['ids'].")"
		) or die("SQL 错误！");
		
		if(mysql_affected_rows() != 0){
			echo '{"type":"success","msg":"订单删除成功"}';
		}else{
			echo '{"type":"error","msg":"token异常,请重新登录"}';
		}
	}
?>