<?php
	header('Access-Control-Allow-Origin:*');
	require 'config.php';
	$date = date("Y-m-d H:i:s",time());
	
	//总页数 = 总商品数 ÷ 每页显示数量
	$totalPage = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ywms_shop_info"),MYSQL_NUM);
	echo json_encode(array(
		'count' => $totalPage[0]
		));
//	if($_GET['handle'] == 'set'){
//		$query = mysql_query(
//			"INSERT INTO ywms_shop_info (typeId,name,price,purchased,inventory,color,poster,pic1,pic2,cdate) 
//				  VALUES ('{$_GET['typeId']}','{$_GET['name']}','{$_GET['price']}','{$_GET['purchased']}','{$_GET['inventory']}','{$_GET['color']}','{$_GET['poster']}','{$_GET['pic1']}','{$_GET['pic2']}','{$date}')"
//		) or die("SQL 错误！");
//		
//		if(mysql_affected_rows() == 1){
//			mysql_free_result($query);
//			$query = mysql_query(
//				"SELECT *
//				   FROM ywms_shop_info
//				  WHERE cdate='{$date}'"
//			);
//			if(!!$result = mysql_fetch_array($query, MYSQL_ASSOC))
//				echo json_encode(array(
//					"type" => "success",
//					"msg" => "商品添加成功",
//					"shop" => $result
//				));
//		}else{
//			echo '{"type":"error","msg":"token异常,请重新登录"}';
//		}
//	}else if($_GET['handle'] == 'get'){
//		$query = mysql_query("SELECT * FROM ywms_shop_info") or die("SQL 错误！");
//		
//		$list = array();
//		while (!!$row = mysql_fetch_array($query, MYSQL_ASSOC))
//			$list[] = $row;
//			
//		echo json_encode(array(
//			"type" => "success",
//			"msg" => "商品获取成功",
//			"shops" => $list
//		),JSON_UNESCAPED_UNICODE);
//	}else if($_GET['handle'] == 'update'){
//		$query = mysql_query(
//			"UPDATE ywms_shop_info
//				SET typeId='{$_GET['typeId']}',
//					name='{$_GET['name']}',
//					price='{$_GET['price']}',
//					purchased='{$_GET['purchased']}',
//					inventory='{$_GET['inventory']}',
//					color='{$_GET['color']}',
//					poster='{$_GET['poster']}',
//					pic1='{$_GET['pic1']}',
//					pic2='{$_GET['pic2']}',
//					cdate='{$_GET['cdate']}' 
//			  WHERE id='{$_GET['id']}' 
//			  LIMIT 1"
//		) or die('SQL 错误！');
//		if (mysql_affected_rows() == 1){
//			echo '{"type":"success","msg":"商品修改成功"}';
//		}else{
//			echo '{"type":"error","msg":"token异常,请重新登录"}';
//		}
//	}else if($_GET['handle'] == 'del'){
//		$query = mysql_query(
//			"DELETE FROM ywms_shop_info 
//				   WHERE id IN (".$_GET['ids'].")"
//		) or die("SQL 错误！");
//		
//		if(mysql_affected_rows() != 0){
//			echo '{"type":"success","msg":"商品删除成功"}';
//		}else{
//			echo '{"type":"error","msg":"token异常,请重新登录"}';
//		}
//	}
?>