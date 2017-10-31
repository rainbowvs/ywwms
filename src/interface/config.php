<?php
//	header('Content-Type:text/html; charset=utf-8');
	header('Access-Control-Allow-Origin:*');
	
	define('DB_HOST', 'bdm310788302.my3w.com');
	define('DB_USER', 'bdm310788302');
	define('DB_PWD', 'rainbowvs');
	define('DB_NAME', 'bdm310788302_db');
	
	//连接数据库管理系统
	$con = @mysql_connect(DB_HOST, DB_USER, DB_PWD) or die('数据库系统链接失败：'.mysql_error());
	
	//连接数据库
	@mysql_select_db(DB_NAME) or die('数据库错误：'.mysql_error());
	
	//mysql_query() 函数执行一条 MySQL 查询。 内容是设置字符集的编码为utf8
	@mysql_query('SET NAMES UTF8') or die('字符集错误：'.mysql_error());
?>